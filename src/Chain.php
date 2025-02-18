<?php declare(strict_types=1);
/**
 * (c) 2005-2025 Dmitry Lebedev <dl@adios.ru>
 * This source code is part of the Ultra library.
 * Please see the LICENSE file for copyright and licensing information.
 */
namespace Ultra;

use Closure;

/**
 * Типаж реализует интерфейс Ultra\Sequence для любых состояний.
 */
trait Chain {
	/**
	 * Выполнять последовательность замыканий до тех пор, пока одно из них не вернёт значение
	 * отличное от текущего состояния, вне зависимости валидно оно или нет.
	 * Если ни одно из замыканий не оказалось способно вернуть новое состояние, вернуть текущее состояние.
	 * Ожидаемая сигнатура замыкания:
	 * fn handler(Ultra\State $state): mixed;
	 * Если функция замыкания неспособна обработать переданное ей состояние, она должна вернуть это состояние.
	 * В противном случае, функция замыкание может возврашать значение любого типа, Метод должен
	 * обернуть новое значение не являющееся интерфейсом Ultra\State в объект Ultra\Result.
	 */
	public function chain(Closure ...$handlers): State {
		if (!$this->valid()) {
			return $this;
		}

		foreach ($handlers as $handler) {
			$result = $handler($this);

			if ($result === $this) {
				continue;
			}

			if ($result instanceof State) {
				return $result;
			}

			return new Result($result);
		}

		return $this;
	}
}
