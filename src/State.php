<?php declare(strict_types=1);
/**
 * (c) 2005-2024 Dmitry Lebedev <dl@adios.ru>
 * This source code is part of the Ultra library.
 * Please see the LICENSE file for copyright and licensing information.
 */
namespace Ultra;

use Closure;

/**
 * Интерфейс наделяет как ожидаемые, так и неожиданные, и ошибочные результаты и состояния
 * единым поведением.
 * Позволяет распространять ошибочные состояния в вызывающие интерфейсы.
 */
interface State {
	/**
	 * Проверить существование действующего результата и вертуть TRUE, если результат
	 * существует, FALSE, если результат отсутствует или ошибочен.
	 */
	public function valid(): bool;

	/**
	 * Вернуть текущий интерфейс, если он соответствует действительному результату,	в противном
	 * случае вернуть NULL.
	 * Если будет передан необязательный аргумент $default, отличный от NULL, то вместо NULL
	 * вернётся новый интерфейс Ultra\State, созданный из $default.
	 */
	public function call(mixed $default = null): self|null;

	/**
	 * Развернуть результат и получить реальное значение.
	 * При ошибочном результате или при отсутствии значения вернуть значение по умолчанию,
	 * указанное в необязательном аргументе $default.
	 * Если результат ошибочен или недействителен и значение по умолчанию не передано, вернуть
	 * NULL.
	 */
	public function unwrap(mixed $default = null): mixed;

	/**
	 * Развернуть результат и получить реальное значение.
	 * При ошибочном результате или при отсутствии значения выполнить замыкание, переданное в
	 * необязательном аргументе $reject.
	 * На замыкание возлагается роль обработчика ошибки.
	 * Если замыкание не выбрасывает исключения и не останавливает выполнение программы, оно
	 * должно вернуть, либо NULL, либо валидное значение ожидаемого типа.
	 * 
	 * Ожидаемая сигнатура замыканий:
	 * fn reject(Ultra\State $reject): mixed;
	 */
	public function expect(Closure|null $reject = null): mixed;

	/**
	 * Обработать результат и вернуть либо интерфейс Ultra\State, либо NULL.
	 * В случае действительного результата, обработать результат, используя замыкание, заданное
	 * необязательным аргументом $resolve.
	 * Если $resolve возвращает инетфейс Ultra\State, то возвращается этот интерфейс, вне
	 * зависимости от типа нового объекта реализующего интерфейс.
	 * Если $resolve возвращает любое другое значение отличное от NULL, то на основании этого
	 * значения должен быть создан и возвращён интерфейс Ultra\State.
	 * Если $resolve возвращает NULL, то интерфейс должен вернуть сам себя.
	 * Если $resolve не будет задан, то интерфейс должен вернуть сам себя.
	 * В случае ошибочного или отсутствующего результата, обработать ошибочное состояние,
	 * исполнить замыкание, заданное необязательным аргументом $reject.
	 * Если $reject не прерывает исполнение программы и не выбрасывает исключение, а возвращает
	 * инетфейс Ultra\State, то возвращается этот интерфейс, вне зависимости от типа нового
	 * объекта, реализующего интерфейс.
	 * Если $reject возвращает любое другое значение отличное от NULL, то на основании этого
	 * значения должен быть создан и возвращён интерфейс Ultra\State.
	 * Если $reject возвращает NULL, то интерфейс должен вернуть NULL.
	 * Если $reject не будет задан, то возвращается NULL.
	 * 
	 * Ожидаемая сигнатура замыканий:
	 * fn resolve(Ultra\State $value): mixed;
	 * fn reject(Ultra\State $reject): mixed;
	 */
	public function fetch(Closure|null $resolve = null, Closure|null $reject = null): self|null;

	/**
	 * Обработать результат и вернуть интерфейс Ultra\State.
	 * Логика метода аналогична Ultra\State::fetch(), но в отличии от Ultra\State::fetch()
	 * Ultra\State::follow() всегда возвращает интерфейс Ultra\State, то есть,
	 * если $reject возвращает NULL или $reject не будет задан, то интерфейс должен вернуть сам себя,
	 * а не NULL.
	 */
	public function follow(Closure|null $resolve = null, Closure|null $reject = null): self;

	/**
	 * Совершить действия над успешным результатом передав замыкание $resolve, вернуть интерфейс
	 * Ultra\State.
	 * По умолчанию аналогично вызову Ultra\State::follow($resolve);
	 */
	public function commit(Closure $resolve): self;
	
	/**
	 * Попытаться восстановить результат из ошибочного состояния и вернуть интерфейс Ultra\State
	 * с действующим значением.
	 * Аналогично вызову Ultra\State::follow(null, $reject);
	 */
	public function recover(Closure $reject): self;
}
