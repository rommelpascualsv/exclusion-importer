<?php namespace App\Mappers;

abstract class Mapper 
{
	abstract public function map($data);

	protected function addProperties(&$target, $headers, $source)
	{
		foreach ($headers as $key => $header) {
			$this->addProperty($target, $source, $key, $header);
		}
	}

	protected function addProperty(&$target, $source, $targetProperty, $sourceProperty)
	{
		if (! array_key_exists($sourceProperty, $source)) {
			return;
		}
		$target[$targetProperty] = mb_convert_encoding($source[$sourceProperty], 'UTF-8');
	}

	protected function removeEmptyEntries($entries) {
		return array_filter($entries, function ($entry) {
			return ! empty(array_filter($entry));
		});
	}
}
