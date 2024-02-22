<?php declare(strict_types=1);
/**
 * (c) 2005-2024 Dmitry Lebedev <dl@adios.ru>
 * This source code is part of the Ultra library.
 * Please see the LICENSE file for copyright and licensing information.
 */
namespace Ultra\Result;

/**
 * Стандартная обёртка для валидных значений любых типов.
 * Класс использует для имплементации интерфейса \Ultra\Result\State
 * типаж Ultra\Result\Wrapper.
 */
class Success implements State {
	use Wrapper;

	final public function __construct(mixed $value) {
		$this->_value = $value;
	}
}
