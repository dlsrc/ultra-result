<?php declare(strict_types=1);
/**
 * (c) 2005-2026 Dmitry Lebedev <dlsrc.extra@gmail.com>
 * This source code is part of the Ultra library.
 * Please see the LICENSE file for copyright and licensing information.
 */
namespace Ultra;

/**
 * Стандартная обёртка для валидных значений любых типов.
 * Класс использует для имплементации интерфейса \Ultra\State
 * типаж Ultra\Wrapper.
 */
class Result implements State {
	use Wrapper;

	final public function __construct(mixed $value) {
		$this->_value = $value;
	}
}
