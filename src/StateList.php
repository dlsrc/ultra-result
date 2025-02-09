<?php declare(strict_types=1);
/**
 * (c) 2005-2024 Dmitry Lebedev <dl@adios.ru>
 * This source code is part of the Ultra library.
 * Please see the LICENSE file for copyright and licensing information.
 */
namespace Ultra;

/**
 * Обёртка для списка.
 * Класс использует для имплементации интерфейса \Ultra\State
 * типаж Ultra\ArrayWrapper.
 */
class StateList implements State {
	use ArrayWrapper;

	final public function __construct(State ...$values) {
		$this->_value = $values;
	}

	final public function append(State $value): void {
		$this->_value[] = $value;
	}

	final public function last(): State {
		if (null === ($key = array_key_last($this->_value))) {
			return new Fail(Status::Range, 'Trying to find a State in an empty array.');
		}

		return $this->_value[$key];
	}

	final public function __invoke(int $id): State {
		if (isset($this->_value[$id])) {
			return $this->_value[$id];
		}

		return new Fail(Status::Range, 'Index #'.$id.' not exists.');
	}
}