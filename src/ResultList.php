<?php declare(strict_types=1);
/**
 * (c) 2005-2025 Dmitry Lebedev <dl@adios.ru>
 * This source code is part of the Ultra library.
 * Please see the LICENSE file for copyright and licensing information.
 */
namespace Ultra;

use Generator;

/**
 * Обёртка для списка.
 * Класс использует для имплементации интерфейса \Ultra\State
 * типаж Ultra\ArrayWrapper.
 */
class ResultList implements State {
	use ArrayWrapper;

	final public function __construct(mixed ...$values) {
		$this->_value = $values;
	}

	final public function append(mixed $value): void {
		$this->_value[] = $value;
	}

	final public function last(): mixed {
		if (null === ($key = array_key_last($this->_value))) {
			return null;
		}

		return $this->_value[$key];
	}

	final public function iterator(): Generator {
		$count = count($this->_value);

		for ($i = 0; $i < $count; $i++) {
			yield $i => $this->_value[$i];
		}
	}

	final public function __invoke(int $id): mixed {
		if (isset($this->_value[$id])) {
			return $this->_value[$id];
		}

		return null;
	}
}