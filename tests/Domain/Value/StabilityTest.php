<?php

declare(strict_types=1);

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Domain\Value;

use App\Domain\Value\Stability;
use Ergebnis\Test\Util\Helper;
use PHPUnit\Framework\TestCase;

final class StabilityTest extends TestCase
{
    use Helper;

    /**
     * @dataProvider \Ergebnis\Test\Util\DataProvider\StringProvider::blank()
     * @dataProvider \Ergebnis\Test\Util\DataProvider\StringProvider::empty()
     */
    public function testThrowsExceptionFor(string $value): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Stability::fromString($value);
    }

    public function testUsesLowercaseForStability(): void
    {
        static::assertSame(
            'minor',
            Stability::fromString('MINOR')->toString()
        );
    }

    public function testToStringReturnsLowercaseString(): void
    {
        static::assertSame(
            'minor',
            Stability::minor()->toString()
        );
    }

    public function testToUppercaseStringReturnsUppercaseString(): void
    {
        static::assertSame(
            'MINOR',
            Stability::minor()->toUppercaseString()
        );
    }

    /**
     * @dataProvider provideValidCases
     */
    public function testValid(string $value): void
    {
        $stability = Stability::fromString($value);

        static::assertSame($value, $stability->toString());
    }

    /**
     * @return iterable<string, array{string}>
     */
    public function provideValidCases(): iterable
    {
        yield 'unknown' => ['unknown'];
        yield 'minor' => ['minor'];
        yield 'patch' => ['patch'];
    }

    public function testPatch(): void
    {
        static::assertSame(
            'patch',
            Stability::patch()->toString()
        );
    }

    public function testMinor(): void
    {
        static::assertSame(
            'minor',
            Stability::minor()->toString()
        );
    }

    public function testPedantic(): void
    {
        static::assertSame(
            'pedantic',
            Stability::pedantic()->toString()
        );
    }

    public function testUnknown(): void
    {
        static::assertSame(
            'unknown',
            Stability::unknown()->toString()
        );
    }

    /**
     * @dataProvider provideEqualsCases
     */
    public function testEquals(bool $expected, Stability $stability, Stability $other): void
    {
        static::assertSame(
            $expected,
            $stability->equals($other)
        );

        static::assertSame(
            !$expected,
            $stability->notEquals($other)
        );
    }

    /**
     * @return iterable<array{bool, Stability, Stability}>
     */
    public function provideEqualsCases(): iterable
    {
        yield [
            true,
            Stability::unknown(),
            Stability::fromString('unknown'),
        ];

        yield 'equal, because of case insensitive' => [
            true,
            Stability::fromString('minor'),
            Stability::fromString('MINOR'),
        ];

        yield [
            false,
            Stability::minor(),
            Stability::patch(),
        ];
    }
}
