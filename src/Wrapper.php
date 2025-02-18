<?php declare(strict_types=1);
/**
 * (c) 2005-2025 Dmitry Lebedev <dl@adios.ru>
 * This source code is part of the Ultra library.
 * Please see the LICENSE file for copyright and licensing information.
 */
namespace Ultra;

use Closure;

/**
 * Типаж для имплементации интерфейса \Ultra\State в классах, служащих обёрткой валидным
 * значениям любого типа.
 * На основе типажа Ultra\Wrapper реализована стандартная обёртка Ultra\Result.
 */
trait Wrapper {
	use Valid;

	/**
	 * Поле с действующим значением, которое нужно обернуть в интерфейс State.
	 * Классу использующему типаж нужно в консрукторе заполнить это поле значением.
	 */
	private mixed $_value;

	public function unwrap(mixed $default = null): mixed {
		return $this->_value;
	}

	public function expect(Closure|null $reject = null): mixed {
		return $this->_value;
	}
}
