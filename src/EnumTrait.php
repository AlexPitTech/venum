<?php

namespace PitTech\vEnum;

trait EnumTrait
{
    /** @throws */
    public static function collection(): Collection
    {
        return Factory::build(static::class);
    }

    public static function active(bool $active = true): Collection
    {
        return static::collection()->active($active);
    }

    public static function only(... $tags): Collection
    {
        return static::collection()->only(... $tags);
    }

    public static function filter(... $filters): Collection
    {
        return static::collection()->filter(... $filters);
    }

    public static function values(): array
    {
        return static::collection()->values();
    }

    public static function payloads(): array
    {
        return static::collection()->payloads();
    }

    public static function valueFrom(mixed $payload): ?string
    {
        return static::collection()->valueFrom($payload);
    }

    public static function tryValueFrom(mixed $payload, mixed $default = null): ?string
    {
        return static::collection()->tryValueFrom($payload, $default);
    }

    public static function tryValueFromArray(?array $payloads): ?array
    {
        return static::collection()->tryValueFromArray($payloads);
    }

    public static function valueFromArray(?array $payloads): ?array
    {
        return static::collection()->valueFromArray($payloads);
    }

    public static function tryResolveFrom(string|int $value, mixed $default = null): mixed
    {
        return static::collection()->tryResolveFrom($value, $default);
    }

    public static function resolveFrom(string|int $value): mixed
    {
        return static::collection()->resolveFrom($value);
    }

    public static function resolveFromArray(array $values): array
    {
        return static::collection()->resolveFromArray($values);
    }

    public static function tryResolveFromArray(?array $values): ?array
    {
        return static::collection()->tryResolveFromArray($values);
    }

    public static function map(callable $callback): ?array
    {
        return static::collection()->map($callback);
    }

}
