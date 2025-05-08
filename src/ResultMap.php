<?php declare(strict_types=1);
/**
 * (c) 2005-2025 Dmitry Lebedev <dl@adios.ru>
 * This source code is part of the Ultra library.
 * Please see the LICENSE file for copyright and licensing information.
 */
namespace Ultra;

/**
 * Обёртка для ассоциативного массива.
 * Класс использует для имплементации интерфейса \Ultra\State типаж Ultra\ArrayWrapper.
 */
class ResultMap implements State {
	use ArrayWrapper;

	final public function __construct() {
		$this->_value = [];
	}

	final public function __set(string $name, mixed $value): void {
		if ($value instanceof State) {
			$this->_value[$name] = $value;
		}
		else {
			$this->_value[$name] = new Result($value);
		}
	}

	final public function __get(string $name): Result|null {
		if (isset($this->_value[$name])) {
			return $this->_value[$name];
		}

		return null;
	}
}
