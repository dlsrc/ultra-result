<?php declare(strict_types=1);
/**
 * (c) 2005-2025 Dmitry Lebedev <dl@adios.ru>
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
		assert(AssertionStateClosure::isValidList($handlers));

		if (!$this->valid()) {
			return $this;
		}

		$list = new ResultList($this);

		foreach ($handlers as $key => $handler) {
			$last = $list($key);

			if ($last instanceof State) {
				if (!$last->valid()) {
					return $last;
				}

				$list->append($handler($last));
			}
			else {
				$list->append($handler($this));
			}
		}

		$last = $list->last();

		if ($last instanceof State) {
			if (!$last->valid()) {
				return $last;
			}
		}

		return $list;
	}
}
