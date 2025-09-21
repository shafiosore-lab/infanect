<?php

namespace App\View\Components;

use App\Traits\StringableData;
use Illuminate\View\Component;
use BackedEnum;
use Illuminate\Support\Facades\Log;

class BaseComponent extends Component
{
    use StringableData;

    /**
     * Magic getter that safely converts values to strings
     */
    public function __get($key)
    {
        $value = $this->$key ?? null;
        return $this->stringifyValue($value);
    }

    /**
     * Convert various values to safe strings for Blade with enhanced error handling.
     */
    protected function stringifyValue(mixed $value): string
    {
        try {
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
                // Flatten nested arrays and stringify
                $flat = [];
                array_walk_recursive($value, function($v) use (&$flat) {
                    $flat[] = is_scalar($v) ? (string)$v : json_encode($v);
                });
                return implode(' ', $flat);
            }

            if (is_object($value)) {
                if (method_exists($value, '__toString')) {
                    return (string) $value;
                }

                if (method_exists($value, 'getKey') && $value->getKey()) {
                    return (string) $value->getKey();
                }

                // Try common properties in order of preference
                $properties = ['name', 'title', 'label', 'slug', 'id', 'key'];
                foreach ($properties as $prop) {
                    if (property_exists($value, $prop) && !is_null($value->$prop)) {
                        return (string) $value->$prop;
                    }
                }

                // For collections, try to get count
                if (method_exists($value, 'count')) {
                    return (string) $value->count();
                }

                // Last resort: safe JSON encode
                return $this->safeJsonEncode($value);
            }

            return (string) $value;

        } catch (\Throwable $e) {
            // Log the error but don't break the view
            Log::warning('BaseComponent stringification error', [
                'value_type' => gettype($value),
                'error' => $e->getMessage(),
                'component' => static::class
            ]);

            return '';
        }
    }

    /**
     * Safely encode objects to JSON
     */
    private function safeJsonEncode($value): string
    {
        try {
            $encoded = json_encode($value, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);
            // Truncate very long JSON strings to prevent UI issues
            return strlen($encoded) > 100 ? substr($encoded, 0, 97) . '...' : $encoded;
        } catch (\Throwable $e) {
            return '[Object]';
        }
    }

    /**
     * Return sanitized array of public properties to pass into component views.
     */
    public function toArray(): array
    {
        $props = [];
        $reflection = new \ReflectionClass($this);

        foreach ($reflection->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
            $name = $property->getName();
            if ($name !== 'componentName' && $name !== 'except') {
                $props[$name] = $this->stringifyValue($this->$name ?? null);
            }
        }

        return $props;
    }

    /**
     * Alias used by the framework to get component data.
     */
    public function data(): array
    {
        return array_merge($this->toArray(), [
            // Add component metadata
            '_component' => static::class,
            '_safe_mode' => true
        ]);
    }

    /**
     * Enhanced render method with error boundaries
     */
    public function render()
    {
        return function (array $data) {
            try {
                // Ensure any data passed into the component view is sanitized
                foreach ($data as $k => $v) {
                    if (is_array($v) || is_object($v)) {
                        $data[$k] = $this->stringifyValue($v);
                    }
                }

                return $data;

            } catch (\Throwable $e) {
                Log::error('BaseComponent render error', [
                    'component' => static::class,
                    'error' => $e->getMessage(),
                    'data_keys' => array_keys($data)
                ]);

                // Return safe fallback data
                return [
                    '_error' => 'Component render failed',
                    '_component' => static::class
                ];
            }
        };
    }

    /**
     * Check if a route exists before generating URL
     */
    protected function safeRoute(string $name, array $parameters = []): string
    {
        try {
            if (\Illuminate\Support\Facades\Route::has($name)) {
                return route($name, $parameters);
            }
            return '#';
        } catch (\Throwable $e) {
            return '#';
        }
    }

    /**
     * Safe asset URL generation
     */
    protected function safeAsset(string $path): string
    {
        try {
            return asset($path);
        } catch (\Throwable $e) {
            return '';
        }
    }
}
}
