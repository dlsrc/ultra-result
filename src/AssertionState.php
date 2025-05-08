<?php declare(strict_types=1);
/**
 * (c) 2005-2025 Dmitry Lebedev <dl@adios.ru>
 * This source code is part of the Ultra library.
 * Please see the LICENSE file for copyright and licensing information.
 */
namespace Ultra;

use Closure;
use ReflectionFunction;
use ReflectionIntersectionType;
use ReflectionNamedType;
use ReflectionParameter;
use ReflectionUnionType;

/**
 * Класс содержит статические методы проверки допустимости сигнатур ожидаемых замыканий.
 */
class AssertionState {
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

		return self::_isValidType($params[0]);
	}

	public static function isValidOrNull(Closure|null $fn): bool {
		if (is_null($fn)) {
			return true;
		}

		return self::isValid($fn);
	}

	private static function _isValidType(ReflectionParameter $p): bool {
		$type = $p->getType();

		return match($type::class) {
			ReflectionNamedType::class => self::_isValidNamedParameter($type),
			ReflectionUnionType::class => self::_isValidUnionParameter($type),
			ReflectionIntersectionType::class => self::_isValidIntersectionParameter($type),
		};
	}

	private static function _isStateType(string $name): bool {
		return $name == State::class || is_subclass_of($name, State::class);
	}

	private static function _isValidNamedParameter(ReflectionNamedType $type): bool {
		return self::_isStateType($type->getName());
	}

	private static function _isValidUnionParameter(ReflectionUnionType $type): bool {
		foreach ($type->getTypes() as $t) {
			if ($t instanceof ReflectionIntersectionType) {
				if (!self::_isValidIntersectionParameter($t)) {
					return false;
				}
			}
			elseif (!self::_isValidNamedParameter($t)) {
				return false;
			}
		}

		return true;
	}

	private static function _isValidIntersectionParameter(ReflectionIntersectionType $type): bool {
		foreach ($type->getTypes() as $t) {
			if (self::_isValidNamedParameter($t)) {
				return true;
			}
		}

		return false;
	}
}
