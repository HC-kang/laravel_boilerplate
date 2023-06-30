<?php

namespace App\Dtos;
use Illuminate\Support\Facades\Log;

class BaseDto
{
    public function toArray(): array
    {
        $reflectionClass = new \ReflectionClass($this);
        $properties = $reflectionClass->getProperties();

        $array = [];
        foreach ($properties as $property) {
            $property->setAccessible(true);
            $value = $property->getValue($this);
            if ($value !== null) {
                $array[$this->camelToSnake($property->getName())] = $value;
            }
        }
        return array_filter($array, function ($value) {
            return $value !== null;
        });
    }

    private function camelToSnake($input) {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $input));
    }
}