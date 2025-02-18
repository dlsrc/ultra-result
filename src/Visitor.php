<?php declare(strict_types=1);
/**
 * (c) 2005-2025 Dmitry Lebedev <dl@adios.ru>
 * This source code is part of the Ultra library.
 * Please see the LICENSE file for copyright and licensing information.
 */
namespace Ultra;

use Closure;

interface Visitor {
	/**
	 * Выполнить список замыканий, передав каждому в качестве аргумента текущее состояние.
	 * Вернуть текущее состояние.
	 * Если текущее состояние ошибочно, сразу вернуть его.
	 * Ожидаемая сигнатура замыкания:
	 * fn acceptor(Ultra\State $self): void;
	 */
	public function visit(Closure ...$acceptors): self;
}
