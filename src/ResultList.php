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
 * типаж Ultra\Wrapper.
 */
class ResultList implements State {
	use Wrapper;

	final public function __construct(array $value) {
		if (array_is_list($value)) {
			$this->_value = $value;
		}
		else {
			$this->_value = array_values($value);
		}
	}

	final public function __invoke(int $id): mixed {
		if (isset($this->_value[$id])) {
			return $this->_value[$id];
		}

		return null;
	}
}