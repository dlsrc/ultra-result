<?php declare(strict_types=1);
/**
 * (c) 2005-2024 Dmitry Lebedev <dl@adios.ru>
 * This source code is part of the Ultra library.
 * Please see the LICENSE file for copyright and licensing information.
 */
namespace Ultra;

/**
 * Интерфейс для перечислений, типизированных целочисленными значениями,
 * варианты которых являются кодами состояния (ошибки).
 * Наиболее общие коды состояния собраны в перечислении Ultra\Status.
 */
interface Condition /*extends \BackedEnum*/ {
	/**
	 * Вернуть значение, указывающее на необходимость остановки исполнения программы,
	 * в зависимости от варианта.
	 */
	public function isFatal(): bool;
}
