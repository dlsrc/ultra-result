<?php declare(strict_types=1);
/**
 * (c) 2005-2024 Dmitry Lebedev <dl@adios.ru>
 * This source code is part of the Ultra library.
 * Please see the LICENSE file for copyright and licensing information.
 */
namespace Ultra;

use Closure;

/**
 * Типаж реализует интерфейс Ultra\Pipeline для всех состояний.
 */
trait Pipe {
	/**
	 * Выполнять цепочку замыканий до тех пор, пока одно из них не верёт ошибочное состояние.
	 * Результат работы каждого замыкания записать в массив обернутый в объект Ultra\ResultList.
	 * Первый элемент массива с идексом 0, должен содержать исходное состояние. Последующие
	 * элементы массива должны содержать разультаты работы кадого замыкания в том порядке,
	 * в каком они переданы в метод.
	 * Первое замыкание в качестве аргумента примет текущее состояние, последующие замыкания
	 * будут принимать текущее состояние только в том случае, если предыдущее замыкание вернуло
	 * значение отличное от типа Ultra\State.
	 * Если предыдущее замыкание вернуло интерфейс Ultra\State, то следующее замыкание
	 * должно принять его в качестве аргумента.
	 * Метод вернёт результаты работы всех замыканий в обертке Ultra\ResultList в том случае,
	 * если ни одна из функций не вернула интерфейс Ultra\State, являющийся ошибочным.
	 * Если одна из функций в цепочке вернула ошибочное состояние, должно быть возвращено это состояние.
	 * Ожидаемая сигнатура замыкания:
	 * fn handler(Ultra\State $result): mixed;
	 */
	public function pipe(Closure ...$handlers): State {
		if (!$this->valid()) {
			return $this;
		}

		$state = [$this];

		foreach ($handlers as $key => $handler) {
			$id = $key + 1;

			if ($state[$key] instanceof State) {
				$state[$id] = $handler($state[$key]);
			}
			else {
				$state[$id] = $handler($this);
			}

			if (!$state[$id]->valid()) {
				return $state[$id];
			}
		}

		return new ResultList($state);
	}
}
