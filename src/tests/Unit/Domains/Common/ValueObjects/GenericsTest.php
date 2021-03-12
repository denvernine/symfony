<?php

declare(strict_types=1);

namespace Tests\Unit\Domains\Common\ValueObjects;

use App\Domains\Common\ValueObjects\Generics;
use Closure;
use Error;
use Generator;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * @author denvernine
 * @group unit
 * @group domains
 * @group common
 *
 * @coversNothing
 */
class GenericsTest extends TestCase
{
    /**
     * @testdox testIterator iteratorインターフェースのcurrent,key,next,rewind,validメソッドの実装が期待通りかテストする
     */
    public function testIterator(): void
    {
        $closure = fn () => null;

        ${1} = $this->createInstance('stdClass', 'Closure', [
            new stdClass(), (object) [],
        ], [
            $closure, function () {},
        ]);

        foreach (${1} as $key => $value) {
            $this->assertInstanceOf(stdClass::class, $key);
            $this->assertInstanceOf(Closure::class, $value);
        }
    }

    public function testCount(): void
    {
        $values = range(1, mt_rand(2, 5));

        ${1} = $this->createInstance('int', 'int', [], $values);

        $this->assertSame(count($values), ${1}->count());
    }

    /**
     * @dataProvider provideTypes
     */
    public function testKeyType($type): void
    {
        ${1} = $this->createInstance($type, 'int', [], []);

        $this->assertSame($type, ${1}->keyType());
    }

    /**
     * @dataProvider provideTypes
     */
    public function testValueType($type): void
    {
        ${1} = $this->createInstance('int', $type, [], []);

        $this->assertSame($type, ${1}->valueType());
    }

    public function testKeys(): void
    {
        $keys = [];

        foreach (range(1, 3) as $i) {
            $keys[] = new stdClass();
        }

        ${1} = $this->createInstance('stdClass', 'int', $keys, []);

        $this->assertSame($keys, ${1}->keys());
    }

    public function testValues(): void
    {
        $values = [];

        foreach (range(1, 3) as $i) {
            $values[] = new stdClass();
        }

        ${1} = $this->createInstance('int', 'stdClass', [], $values);

        $this->assertSame($values, ${1}->values());
    }

    public function testExists(): void
    {
        $key = new stdClass();

        ${1} = $this->createInstance('stdClass', 'int', [$key], [$key]);

        $this->assertTrue(${1}->exists($key));
    }

    public function testGet(): void
    {
        $value = new stdClass();

        ${1} = $this->createInstance('int', 'stdClass', [], [$value]);

        $actual = ${1}->get(0);

        $this->assertInstanceOf(stdClass::class, $actual);
        $this->assertSame($value, $actual);
    }

    public function testSet(): void
    {
        $rand = mt_rand(2, 5);
        ${1} = $this->createInstance('int', 'int', [], []);

        $this->assertFalse(${1}->exists($rand));

        ${1}->set($rand, $rand);

        $this->assertTrue(${1}->exists($rand));
    }

    public function testUnset(): void
    {
        $rand = mt_rand(2, 5);
        ${1} = $this->createInstance('int', 'int', [$rand], [$rand]);

        ${1}->unset($rand, $rand);

        $this->assertFalse(${1}->exists($rand));
        $this->assertNull(${1}->get($rand));
    }

    public function testCreate(): void
    {
        ${1} = $this->createInstance();

        $this->assertInstanceOf(Generics::class, ${1});
    }

    public function testConstruct(): void
    {
        $this->expectException(Error::class);

        new Generics('string', 'string');
    }

    public function provideTypes(): Generator
    {
        foreach ([
            'bool',
            'float',
            'int',
            'string',
            'array',
            'callable',
            'iterable',
            'null',
            'stdClass',
        ] as $type) {
            yield $type => [$type];
        }
    }

    protected function createInstance(?string $keyType = null, ?string $valueType = null, ?array $keys = null, ?array $values = null): Generics
    {
        return Generics::create(
            $keyType ?? 'string',
            $valueType ?? 'string',
            $keys ?? ['foo', 'bar'],
            $values ?? ['hoge', 'fuga'],
        );
    }
}
