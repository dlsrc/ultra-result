<?php declare(strict_types=1);
/**
 * (c) 2005-2024 Dmitry Lebedev <dl@adios.ru>
 * This source code is part of the Ultra library.
 * Please see the LICENSE file for copyright and licensing information.
 */
namespace Ultra;

/**
 * Класс определяет состояние или результат, находящийся в ошибочном, неприемлемом или
 * недоступном для использования состоянии.
 * Хранит основные параметры ошибочного состояния: сообщение об ошибке; статус ошибки;
 * трассировку (опционально); имя файла и номер строки, в которых зафиксировано ошибочное
 * состояние.
 * Класс использует для имплементации интерфейса Ultra\State типаж Ultra\Suspense.
 */
readonly class Fail implements State {
	use Suspense;

	final public function __construct(
		public  Condition $type,
		public     string $message,
		public     string $file  = "",
		public        int $line  = 0,
		public array|null $trace = null,
	) {}
}
