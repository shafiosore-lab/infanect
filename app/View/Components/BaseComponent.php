<?php

namespace App\View\Components;

use App\Traits\StringableData;
use Illuminate\View\Component;
use BackedEnum;

class BaseComponent extends Component
{
    use StringableData;

    public function __get($key)
    {
        $value = $this->$key ?? null;
        return $this->stringifyValue($value);
    }

    /**
     * Convert various values to safe strings for Blade.
     */
    protected function stringifyValue(mixed $value): string
    {
        if (is_null($value)) {
            return '';
        }

        if ($value instanceof BackedEnum) {
            return (string) $value->value;
        }

        if (is_string($value) || is_numeric($value)) {
            return (string) $value;
        }

        if (is_bool($value)) {
            return $value ? '1' : '0';
        }

        if (is_array($value)) {
            // Recursively stringify array values
            return implode(' ', array_map(fn($v) => $this->stringifyValue($v), $value));
        }

        if (is_object($value)) {
            // If object has __toString, use it
            if (method_exists($value, '__toString')) {
                return (string) $value;
            }

            // For common models, try to return id or name
            if (property_exists($value, 'name')) {
                return (string) $value->name;
            }
            if (property_exists($value, 'title')) {
                return (string) $value->title;
            }
            if (property_exists($value, 'id')) {
                return (string) $value->id;
            }

            // Fallback to json encode
            try {
                return json_encode($value);
            } catch (\Throwable $e) {
                return '';
            }
        }

        return (string) $value;
    }

    /**
     * Return sanitized array of public properties to pass into component views.
     */
    public function toArray(): array
    {
        $props = [];
        foreach (get_object_vars($this) as $key => $value) {
            $props[$key] = $this->stringifyValue($value);
        }

        return $props;
    }

    /**
     * Alias used by the framework to get component data.
     */
    public function data(): array
    {
        return $this->toArray();
    }

    public function render()
    {
        return function (array $data) {
            // Ensure any data passed into the component view is sanitized
            foreach ($data as $k => $v) {
                if (is_array($v)) {
                    $data[$k] = $this->stringifyValue($v);
                }
            }

            return $data['slot'] ?? '';
        };
    }
}
