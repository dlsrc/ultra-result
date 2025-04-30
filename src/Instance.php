<?php declare(strict_types=1);
/**
 * (c) 2005-2025 Dmitry Lebedev <dl@adios.ru>
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
	use Valid;

	/**
	 * Так как результатом является сам объект, он будет возвращать сам себя в качестве
	 * значения.
	 */
	public function unwrap(mixed $default = null): self {
		return $this;
	}

	/**
	 * Так как результатом является сам объект, он будет возвращать сам себя в качестве
	 * значения.
	 */
	public function expect(Closure|null $reject = null): self {
		return $this;
	}
}
