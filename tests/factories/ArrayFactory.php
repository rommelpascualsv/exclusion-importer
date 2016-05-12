<?php

class ArrayFactory
{
	private static $factories = [];

	public static function define($name, $attributes)
	{
		self::$factories[$name] = $attributes;
	}

	public static function create($name, $attributes = [])
	{
		return array_replace_recursive(self::$factories[$name], $attributes);
	}
}