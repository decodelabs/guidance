<?php
/**
 * This is a stub file for IDE compatibility only.
 * It should not be included in your projects.
 */
namespace DecodeLabs;

use DecodeLabs\Veneer\Proxy as Proxy;
use DecodeLabs\Veneer\ProxyTrait as ProxyTrait;
use DecodeLabs\Guidance\Context as Inst;
use DecodeLabs\Guidance\Factory as Ref0;
use DecodeLabs\Guidance\Format as Ref1;
use DecodeLabs\Guidance\Uuid as Ref2;
use DateTimeInterface as Ref3;
use Stringable as Ref4;
use Brick\Math\BigInteger as Ref5;

class Guidance implements Proxy
{
    use ProxyTrait;

    const Veneer = 'DecodeLabs\\Guidance';
    const VeneerTarget = Inst::class;

    public static Inst $instance;

    public static function setFactory(Ref0 $factory): Inst {
        return static::$instance->setFactory(...func_get_args());
    }
    public static function getFactory(): Ref0 {
        return static::$instance->getFactory();
    }
    public static function setDefaultShortFormat(Ref1 $format): Inst {
        return static::$instance->setDefaultShortFormat(...func_get_args());
    }
    public static function getDefaultShortFormat(): Ref1 {
        return static::$instance->getDefaultShortFormat();
    }
    public static function createVoid(): Ref2 {
        return static::$instance->createVoid();
    }
    public static function createVoidString(): string {
        return static::$instance->createVoidString();
    }
    public static function createV1(string|int|null $node = NULL, ?int $clockSeq = NULL): Ref2 {
        return static::$instance->createV1(...func_get_args());
    }
    public static function createV1String(string|int|null $node = NULL, ?int $clockSeq = NULL): string {
        return static::$instance->createV1String(...func_get_args());
    }
    public static function createV3(string $name, ?string $namespace = NULL): Ref2 {
        return static::$instance->createV3(...func_get_args());
    }
    public static function createV3String(string $name, ?string $namespace = NULL): string {
        return static::$instance->createV3String(...func_get_args());
    }
    public static function createV4(): Ref2 {
        return static::$instance->createV4();
    }
    public static function createV4String(): string {
        return static::$instance->createV4String();
    }
    public static function createV4Comb(): Ref2 {
        return static::$instance->createV4Comb();
    }
    public static function createV4CombString(): string {
        return static::$instance->createV4CombString();
    }
    public static function createV5(string $name, ?string $namespace = NULL): Ref2 {
        return static::$instance->createV5(...func_get_args());
    }
    public static function createV5String(string $name, ?string $namespace = NULL): string {
        return static::$instance->createV5String(...func_get_args());
    }
    public static function createV6(string|int|null $node = NULL, ?int $clockSeq = NULL): Ref2 {
        return static::$instance->createV6(...func_get_args());
    }
    public static function createV6String(string|int|null $node = NULL, ?int $clockSeq = NULL): string {
        return static::$instance->createV6String(...func_get_args());
    }
    public static function createV7(?Ref3 $date = NULL): Ref2 {
        return static::$instance->createV7(...func_get_args());
    }
    public static function createV7String(?Ref3 $date = NULL): string {
        return static::$instance->createV7String(...func_get_args());
    }
    public static function isValid(Ref4|Ref5|Ref2|string|null $uuid, bool $includeShort = false): bool {
        return static::$instance->isValid(...func_get_args());
    }
    public static function from(Ref4|Ref5|Ref2|string $uuid): Ref2 {
        return static::$instance->from(...func_get_args());
    }
    public static function tryFrom(Ref4|Ref5|Ref2|string|null $uuid): ?Ref2 {
        return static::$instance->tryFrom(...func_get_args());
    }
    public static function fromBytes(string $bytes): Ref2 {
        return static::$instance->fromBytes(...func_get_args());
    }
    public static function fromString(Ref4|string $uuid): Ref2 {
        return static::$instance->fromString(...func_get_args());
    }
    public static function fromShortString(Ref4|string $uuid, ?Ref1 $format = NULL): Ref2 {
        return static::$instance->fromShortString(...func_get_args());
    }
    public static function fromBigInteger(Ref5 $uuid): Ref2 {
        return static::$instance->fromBigInteger(...func_get_args());
    }
    public static function shorten(Ref4|Ref5|Ref2|string $uuid, ?Ref1 $format = NULL): string {
        return static::$instance->shorten(...func_get_args());
    }
    public static function getDateTime(Ref4|Ref5|Ref2|string $uuid): ?Ref3 {
        return static::$instance->getDateTime(...func_get_args());
    }
};
