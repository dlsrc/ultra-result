<?php declare(strict_types=1);
/**
 * (c) 2005-2025 Dmitry Lebedev <dl@adios.ru>
 * This source code is part of the Ultra library.
 * Please see the LICENSE file for copyright and licensing information.
 */
namespace Ultra;

use Closure;

/**
 * Типаж для имплементации интерфейса \Ultra\State в классах, служащих обёрткой массивам.
 * На основе типажа Ultra\ArrayWrapper реализованы стандартные обёртки Ultra\ResultList и Ultra\ResultMap.
 */
trait ArrayWrapper {
	use Valid;

	private array $_value;

	public function unwrap(mixed $default = null): array {
		return $this->_value;
	}

	public function expect(Closure|null $reject = null): array {
		return $this->_value;
	}
}
