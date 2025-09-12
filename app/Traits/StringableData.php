<?php

namespace App\Traits;

trait StringableData
{
    protected function stringifyValue($value)
    {
        if (is_array($value)) {
            return implode(' ', array_map(fn($v) => (string)$v, $value));
        }
        return $value ?? '';
    }
}
