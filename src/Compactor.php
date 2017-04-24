<?php
namespace Eggbe\Compact;

use \Eggbe\Helper\Arr;
use \Eggbe\Helper\Src;
use \Eggbe\Helper\Str;
use \Eggbe\Reglib\Reglib;

use \Eggbe\Prototype\IArrayable;
use \Eggbe\Prototype\IRestorable;
use \Eggbe\Prototype\IPresentable;
use \Eggbe\Prototype\TUncreatable;

use \Eggbe\Utilities\AliasMaker;

class Compactor {
	use TUncreatable;

	/**
	 * @const string
	 */
	const DT_NULL = 0;

	/**
	 * const int
	 */
	const DT_STRING = 1;

	/**
	 * const int
	 */
	const DT_INTEGER = 2;

	/**
	 * const int
	 */
	const DT_DOUBLE = 3;

	/**
	 * const int
	 */
	const DT_BOOLEAN = 4;

	/**
	 * const int
	 */
	const DT_ARRAY = 5;

	/**
	 * const int
	 */
	const DT_OBJECT = 6;

	/**
	 * const int
	 */
	const CO_STRICT = 0b0000;

	/**
	 * const int
	 */
	const CO_ALLOW_ARRAYABLE = 0b0001;

	/**
	 * @param mixed $data
	 * @param int $flags
	 * @return array
	 * @throws \Exception
	 */
	public final static function compact($data, int $flags = self::CO_STRICT): array {
		if ($flags > self::CO_ALLOW_ARRAYABLE){
			throw new \Exception('Undefined flags sequence!');
		}

		return Arr::inline(self::to($data, $flags));
	}

	/**
	 * @param $data
	 * @param int $flags
	 * @return array
	 * @throws \Exception
	 */
	private static final function to($data, int $flags = self::CO_STRICT){
		if (is_null($data)) {
			return [self::DT_NULL];
		}

		if (is_string($data)) {
			return [self::DT_STRING, $data];
		}

		if (is_integer($data)) {
			return [self::DT_INTEGER, (string)$data];
		}

		if (is_double($data)) {
			return [self::DT_DOUBLE, (string)$data];
		}

		if (is_bool($data)){
			return [self::DT_BOOLEAN, (string)intval($data)];
		}

		if (is_array($data)){
			return [self::DT_ARRAY, count($data), array_map(function($value) use ($flags) {
				return self::to($value, $flags); }, Arr::stretch($data, 0))];
		}

		if (is_object($data)){
			if ($data instanceof IPresentable) {
				return [self::DT_OBJECT, get_class($data), self::to($data->present(), $flags)];
			}

			if ($flags & self::CO_ALLOW_ARRAYABLE && ($data instanceof IArrayable || method_exists($data, 'toArray'))){
				return [self::DT_OBJECT, get_class($data), self::to($data->toArray(), $flags)];
			}

			throw new \Exception('Unpresentable objects of class ' .  get_class($data) . '!');
		}


		throw new \Exception('Inconvertible ' . gettype($data) . (is_object($data)
			? (' of type ' . get_class($data)) : null ) . '!');
	}

	/**
	 * @param array $Composed
	 * @param AliasMaker $Aliases
	 * @return mixed
	 * @throws \Exception
	 */
	public static final function decompact(array $Composed, AliasMaker $Aliases = null) {
		$Output = self::from($Composed, !is_null($Aliases)
			? $Aliases : new AliasMaker());

		if (count($Composed) > 0){
			throw new \Exception('Can\'t decompose the given sequence!');
		}

		return $Output;
	}

	/**
	 * @param array $Composed
	 * @param AliasMaker $Aliases
	 * @return mixed
	 * @throws \Exception
	 */
	private static final function from(array &$Composed, AliasMaker $Aliases) {
		if (($prefix  =  (int)array_shift($Composed)) == self::DT_NULL){
			return null;
		}

		if ($prefix == self::DT_STRING){
			return (string)array_shift($Composed);
		}

		if ($prefix == self::DT_INTEGER){
			return (int)array_shift($Composed);
		}

		if ($prefix == self::DT_DOUBLE){
			return (float)array_shift($Composed);
		}

		if ($prefix == self::DT_BOOLEAN){
			return (bool)array_shift($Composed);
		}

		if ($prefix == self::DT_OBJECT){
			if (!class_exists($class = array_shift($Composed))){

				if (!class_exists($class = $Aliases->alike($class))){
					throw new \Exception('Undefined entity class ' . $class .'!');
				}
			}

			if (!is_subclass_of($class, IRestorable::class)){
				throw new \Exception('Entity class ' . $class .' is not exists or not subclass of ' . IRestorable::class . '!');
			}

			return new $class(self::from($Composed, $Aliases));
		}

		if ($prefix == self::DT_ARRAY){
			$Data = [];

			$size = (int)array_shift($Composed);
			for ($i = 0; $i < $size; $i++) {

				if (!is_string($key = self::from($Composed, $Aliases)) && !is_integer($key)) {
					throw new \Exception('Invalid key!');
				}

				$Data[$key] = self::from($Composed, $Aliases);
			}

			return $Data;
		}

		throw new \Exception('Unsupported data type!');
	}
}
