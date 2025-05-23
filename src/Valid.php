<?php declare(strict_types=1);
/**
 * (c) 2005-2025 Dmitry Lebedev <dl@adios.ru>
 * This source code is part of the Ultra library.
 * Please see the LICENSE file for copyright and licensing information.
 */
namespace Ultra;

use Closure;

/**
 * Типаж частично реализует интерфейс Ultra\State для валидных состояний и не предназначен
 * для использования в классах, он только является основой для типажей Ultra\Instance и Ultra\Wrapper.
 * Большая часть реализаций методов интерфейса Ultra\State для имплементирующих его классов и
 * для классов обёрток будет одинакова. Различаться будут только два метода,
 * Ultra\State::unwrap() и Ultra\State::expect().
 * Типажи Ultra\Instance и Ultra\Wrapper реализуют эти методы и должны использоваться
 * в конкретных классах.
 */
trait Valid {
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
	public function call(mixed $default = null): State {
		return $this;
	}

	/**
	 * Так как код из $reject для действующего результата никогда не будет выполнен, то NULL
	 * никогда не будет возвращен (см. ковариантность).
	 * В данном контексте метод будет синонимом Ultra\State::follow().
	 */
	public function fetch(Closure|null $resolve = null, Closure|null $reject = null): State {
		return $this->follow($resolve);
	}

	public function follow(Closure|null $resolve = null, Closure|null $reject = null): State {
		assert(AssertionState::isValidOrNull($resolve));

		if (null === $resolve) {
			return $this;
		}

		return $this->commit($resolve);
	}

	public function commit(Closure $resolve): State {
		assert(AssertionState::isValid($resolve));

		if (null === ($result = $resolve($this))) {
			return $this;
		}

		if ($result instanceof State) {
			return $result;
		}

		return new Result($result);
	}

	/**
	 * Поскольку результат действующий, восстановление после ошибки не требуется.
	 * Сразу возвращается сам интерфейс.
	 */
	public function recover(Closure $reject): State {
		return $this;
	}
}
