<?php

namespace PitTech\vEnum\Tests;

use PHPUnit\Framework\TestCase;
use PitTech\vEnum\Factory;
use PitTech\vEnum\OutOfBoundsException;
use PitTech\vEnum\vEnum;

class EnumsTest extends TestCase
{

    public function testPayloads(): void
    {
        self::assertEquals([
            'leo' => 123,
            'jaguar' => 124,
            'panther' => 125,
            'guepard' => 126,
            'giraffe' => 127,
        ], Animals::payloads());

        self::assertEquals([
            'jaguar' => 124,
            'guepard' => 126,
        ], Animals::filter([Animals::CATS, Animals::SPOTTED => true])->payloads());

        self::assertEquals([
            'giraffe' => 127,
        ], Animals::only(Animals::GIRAFFIDAE)->payloads());
    }

    public function testMap(): void
    {
        self::assertEquals(
            [
                'leo' => [
                    'label' => 'Leo as default',
                    'value' => 'leo',
                ],
                'jaguar' => [
                    'label' => 'Jaguar as default',
                    'value' => 'jaguar',
                ],
                'panther' => [
                    'label' => 'Panther as default',
                    'value' => 'panther',
                ],
                'guepard' => [
                    'label' => 'Guepard', // declared
                    'value' => 'guepard',
                ],
                'giraffe' => [
                    'label' => 'Giraffe as default',
                    'value' => 'giraffe',
                ],
            ],
            Animals::map(fn(vEnum $vEnum) => [
                'label' => $vEnum->label ?? ucfirst($vEnum->value). ' as default',
                'value' => $vEnum->value,
            ])
        );
    }

    public function testValueFrom(): void
    {
        self::assertEquals([
            'leo' => 'leo',
            'jaguar' => 'jaguar',
            'panther' => 'panther',
            'guepard' => 'guepard',
            'giraffe' => 'giraffe',
        ], Animals::values());
    }

    public function testTryValueFromFlying(): void
    {
        self::assertEquals(2, Factory::build(Flying::class)->tryValueFrom(34));
        self::assertEquals('not found', Factory::build(Flying::class)->tryValueFrom(777, 'not found'));
    }

    public function testTryValueFromArray(): void
    {
        self::assertEquals(
            ['leo', 'jaguar'],
            Animals::class::tryValueFromArray([123, 124, 777])
        );
        self::assertEquals(
            [1, 2],
            Factory::build(Flying::class)->tryValueFromArray([33, 34, 777])
        );
        self::assertEquals(
            [1, 2, 3],
            Factory::build(Flying::class)->tryValueFromArray([33, 34, 45, 777])
        );
        self::assertEquals(
            [1, 2],
            Factory::build(Flying::class)->active()->tryValueFromArray([33, 34, 45, 777])
        );
    }

    public function testValueFromArray(): void
    {
        try {
            self::assertEquals(
                ['leo', 'jaguar', 'panther'],
                Animals::valueFromArray([123, 124, 125, 777])
            );
            self::fail();
        } catch (OutOfBoundsException $exception) {
            self::assertEquals(777, $exception->data);
        }

        try {
            self::assertEquals(
                [1, 2],
                Factory::build(Flying::class)->active()->valueFromArray([33, 34, 45])
            );
            self::fail();
        } catch (OutOfBoundsException $exception) {
            self::assertEquals(45, $exception->data);
        }
    }

    public function testTryResolveFrom(): void
    {
        self::assertEquals(
            123,
            Animals::tryResolveFrom('leo')
        );
        self::assertEquals(
            'not found',
            Factory::build(Flying::class)->active()->tryResolveFrom(3, 'not found')
        );
    }

    public function testResolveFrom(): void
    {
        try {
            Animals::resolveFrom(777);
            self::fail();
        } catch (OutOfBoundsException $exception) {
            self::assertEquals(777, $exception->data);
        }
    }

    public function testResolveFromArray(): void
    {
        try {
            Factory::build(Flying::class)->resolveFromArray([2, 777]);
            self::fail();
        } catch (OutOfBoundsException $exception) {
            self::assertEquals(777, $exception->data);
        }
    }

    public function testTryResolveFromArray(): void
    {
        self::assertEquals(
            [34],
            Factory::build(Flying::class)->tryResolveFromArray([2, 777])
        );

        self::assertEquals(
            [123, 124, 125, 126],
            Animals::active()->only(Animals::CATS)->tryResolveFromArray([
                'leo',
                'jaguar',
                'panther',
                'guepard',
                'giraffe',
            ])
        );
    }

}