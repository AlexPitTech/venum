<?php

namespace PitTech\vEnum;

readonly class Collection implements \IteratorAggregate
{
    /** @param vEnum[] $collection */
    public function __construct(
        public array  $collection,
        public string $enumClass
    ){}

    public function values(): array
    {
        return array_combine(array_keys($this->collection), array_keys($this->collection));
    }

    public function payloads(): array
    {
        return array_map(fn(vEnum $eo) => $eo->payload, $this->collection);
    }

    public function enums(): array
    {
        return $this->collection;
    }

    public function active(bool $active = true): static
    {
        return new static(
            \array_filter($this->collection, fn(vEnum $venom) => $active === $venom->active),
            $this->enumClass
        );
    }

    public function only(... $tags): static
    {
        return empty($tags) ? $this : new static(
            \array_filter($this->collection, function(vEnum $venom) use ($tags){
                foreach ($tags as $tag){
                    if (!\in_array($tag, $venom->tags)){
                        return false;
                    }
                }
                return true;
            }),
            $this->enumClass
        );
    }

    public function filter(array $filters): static
    {
        $filters = is_array($filters[0] ?? null) ? $filters[0] : $filters;
        $tags = [];
        foreach ($filters as $k => $filter){
            if (\is_int($k)){
                $tags[] = $filter;
            } elseif (is_bool($filter)){
                if ($filter){
                    $tags[] = $k;
                }
            } else {
                throw new \InvalidArgumentException("Invalid filter");
            }
        }

        return $this->only(... $tags);
    }

    // converters

    public function tryValueFrom(mixed $payload, mixed $default = null): ?string
    {
        foreach ($this->payloads() as $v => $p){
            if ($p === $payload){
                return $v;
            }
        }

        return $default instanceof \Closure
            ? $default($this)
            : $default;
    }

    public function valueFrom(mixed $payload): ?string
    {
        return $this->tryValueFrom($payload)
            ?? throw new OutOfBoundsException("Unknown payload value for $this->enumClass`", $payload);
    }

    public function tryValueFromArray(?array $payloads): ?array
    {
        $declared = $this->payloads();
        return array_filter(array_map(fn ($payload) => array_search($payload, $declared, true), $payloads));
    }

    public function valueFromArray(?array $payloads): ?array
    {
        $declared = $this->payloads();
        return array_map(
            function ($payload) use ($declared) {
                $value = array_search($payload, $declared, true);
                return $value === false
                    ? throw new OutOfBoundsException("Unknown payload value for $this->enumClass", $payload)
                    : $value;
            },
            $payloads
        );
    }

    // resolvers

    public function tryResolveFrom(string|int $value, mixed $default = null): mixed
    {
        return isset($this->collection[$value])
            ? $this->collection[$value]->payload
            : $default;
    }

    public function resolveFrom(string|int $value): mixed
    {
        return match(true){
            isset($this->collection[$value]) => $this->collection[$value]->payload,
            default => throw new OutOfBoundsException("Unknown value for $this->enumClass", $value)
        };
    }

    public function resolveFromArray(array $values): array
    {
        return array_map(fn($value) => $this->resolveFrom($value), $values);
    }

    public function tryResolveFromArray(?array $values): ?array
    {
        return $values
            ? array_filter(array_map(fn($value) => $this->tryResolveFrom($value), $values))
            : $values;
    }

    public function map(callable $callback): array
    {
        return array_map($callback, $this->collection);
    }

    // interfaces

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->collection);
    }
}
