<?php declare(strict_types=1);
/**
 * (c) 2005-2024 Dmitry Lebedev <dl@adios.ru>
 * This source code is part of the Ultra library.
 * Please see the LICENSE file for copyright and licensing information.
 */
namespace Ultra;

/**
 * Класс заменяющий стандартную обёртку.
 * Обёртка порождается другим экземпляром интерфейса Ultra\State, для замены ошибочных
 * или пустых и недействительных значений значениями по умолчанию, либо в случае ошибки
 * результатом её обработки.
 * Экземпляры класса содержат в себе ссылку на породжающий интерфейс.
 * Класс использует для имплементации интерфейса \Ultra\State типаж Ultra\Wrapper.
 */
class Substitute implements State {
	use Wrapper;

	/**
	 * Аргумент $previous должен содержать объект интерфейса State, который инстанцировал
	 * экземпляр класса.
	 */
	public readonly State $previous;

	final public function __construct(mixed $value, State $previous) {
		$this->_value = $value;
		$this->previous = $previous;
	}
}
