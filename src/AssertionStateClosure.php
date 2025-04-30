<?php declare(strict_types=1);
/**
 * (c) 2005-2025 Dmitry Lebedev <dl@adios.ru>
 * This source code is part of the Ultra library.
 * Please see the LICENSE file for copyright and licensing information.
 */
namespace Ultra;

use Closure;
use ReflectionFunction;
use ReflectionNamedType;
use ReflectionParameter;

/**
 * Класс содержит статические методы проверки допустимости сигнатур ожидаемых замыканий.
 */
class AssertionStateClosure {
	public static function isValidList(array $args): bool {
		if (!array_all($args, fn ($fn) => $fn instanceof Closure)) {
			return false;
		}

		return array_all($args, self::isValid(...));
	}

	public static function isValid(Closure $fn): bool {
		$rf = new ReflectionFunction($fn);
		$params = $rf->getParameters();

		if (!isset($params[0])) {
			return false;
		}

		if (!self::isValidParameterType($params[0])) {
			return false;
		}

		return true;
	}

	public static function isValidOrNull(Closure|null $fn): bool {
		if (is_null($fn)) {
			return true;
		}

		return self::isValid($fn);
	}

	private static function isValidParameterType(ReflectionParameter $p): bool {
		$type = $p->getType();

		if ($type instanceof ReflectionNamedType) {
			$name = $type->getName();

			if ($name == State::class || is_subclass_of($name, State::class)) {
				return true;
			}
		}

		return false;
	}
}
