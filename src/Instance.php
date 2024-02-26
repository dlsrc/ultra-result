<?php declare(strict_types=1);
/**
 * (c) 2005-2024 Dmitry Lebedev <dl@adios.ru>
 * This source code is part of the Ultra library.
 * Please see the LICENSE file for copyright and licensing information.
 */
namespace Ultra;

use Closure;

/**
 * Типаж реализует интерфейс Ultra\State у классов напрямую.
 * Типаж для классов, экземпляры которых не являются обёртками и не должны оборочиваться
 * интерфейсом Ultra\State, а должны имплементировать интерфейс Ultra\State напрямую.
 * Подразумевается что экземпляры классов использующих типаж Ultra\Instance реализуют
 * интерфейс Ultra\State для валидных значений.
 * Для аналогичной реализации интерфейса Ultra\State для ошибочных и неопределённых
 * значений нужно использовать типах Ultra\Suspense.
 */
trait Instance {
	/**
	 * Так как действительный результат всегда истинный, то возвращается всегда TRUE
	 * (см. ковариантность).
	 */
	public function valid(): true {
		return true;
	}

	/**
	 * Так как действительный результат всегда готов к использованию, то возвращается всегда
	 * сам объект (см. ковариантность).
	 */
	public function call(mixed $default = null): self {
		return $this;
	}

	/**
	 * Так как результатом является сам объект, он будет возвращать сам себя в качестве
	 * значения.
	 */
	public function unwrap(mixed $default = null): mixed {
		return $this;
	}

	/**
	 * Так как результатом является сам объект, он будет возвращать сам себя в качестве
	 * значения.
	 */
	public function expect(Closure|null $reject = null): mixed {
		return $this;
	}

	/**
	 * Так как код из $reject для действующего результата никогда не будет выполнен, то NULL
	 * никогда не будет возвращен (см. ковариантность).
	 * В данном контексте метод будет синонимом Ultra\State::follow().
	 */
	public function fetch(Closure|null $resolve = null, Closure|null $reject = null): self {
		return $this->follow($resolve);
	}

	public function follow(Closure|null $resolve = null, Closure|null $reject = null): self {
		if (null === $resolve) {
			return $this;
		}

		return $this->commit($resolve);
	}

	public function commit(Closure $resolve): self {
		if (null === ($result = $resolve($this))) {
			return $this;
		}

		if (is_object($result) && ($result instanceof State)) {
			return $result;
		}

		return new Result($result);
	}

	/**
	 * Поскольку результат действующий, восстановление после ошибки не требуется.
	 * Сразу возвращается сам интерфейс.
	 */
	public function recover(Closure $reject): self {
		return $this;
	}
}
