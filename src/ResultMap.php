<?php declare(strict_types=1);
/**
 * (c) 2005-2024 Dmitry Lebedev <dl@adios.ru>
 * This source code is part of the Ultra library.
 * Please see the LICENSE file for copyright and licensing information.
 */
namespace Ultra;

/**
 * Обёртка для ассоциативного массива.
 * Класс использует для имплементации интерфейса \Ultra\State
 * типаж Ultra\Wrapper.
 */
class ResultMap implements State {
	use Wrapper;

	final public function __construct(array $value) {
		$this->_value = $value;
	}

	final public function __get(string $name): mixed {
		if (isset($this->_value[$name])) {
			return $this->_value[$name];
		}

		return null;
	}
}
