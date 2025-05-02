<?php
/**
 * This is a stub file for IDE compatibility only.
 * It should not be included in your projects.
 */
namespace DecodeLabs;

use DecodeLabs\Veneer\Proxy as Proxy;
use DecodeLabs\Veneer\ProxyTrait as ProxyTrait;
use DecodeLabs\Guidance\Context as Inst;
use DecodeLabs\Guidance\Uuid\Engine as Ref0;
use DecodeLabs\Guidance\Uuid\Format as Ref1;
use DecodeLabs\Guidance\Uuid as Ref2;
use DecodeLabs\Guidance\Ulid as Ref3;
use DateTimeInterface as Ref4;
use Stringable as Ref5;
use Brick\Math\BigInteger as Ref6;

class Guidance implements Proxy
{
    use ProxyTrait;

    public const Veneer = 'DecodeLabs\\Guidance';
    public const VeneerTarget = Inst::class;

    protected static Inst $_veneerInstance;

    public static function setUuidEngine(Ref0 $engine): void {}
    public static function setDefaultShortUuidFormat(Ref1 $format): Inst {
        return static::$_veneerInstance->setDefaultShortUuidFormat(...func_get_args());
    }
    public static function getDefaultShortUuidFormat(): Ref1 {
        return static::$_veneerInstance->getDefaultShortUuidFormat();
    }
    public static function createVoidUuid(): Ref2 {
        return static::$_veneerInstance->createVoidUuid();
    }
    public static function createVoidUuidString(): string {
        return static::$_veneerInstance->createVoidUuidString();
    }
    public static function createVoidUlid(): Ref3 {
        return static::$_veneerInstance->createVoidUlid();
    }
    public static function createVoidUlidString(): string {
        return static::$_veneerInstance->createVoidUlidString();
    }
    public static function createV1Uuid(string|int|null $node = NULL, ?int $clockSeq = NULL): Ref2 {
        return static::$_veneerInstance->createV1Uuid(...func_get_args());
    }
    public static function createV1UuidString(string|int|null $node = NULL, ?int $clockSeq = NULL): string {
        return static::$_veneerInstance->createV1UuidString(...func_get_args());
    }
    public static function createV3Uuid(string $name, ?string $namespace = NULL): Ref2 {
        return static::$_veneerInstance->createV3Uuid(...func_get_args());
    }
    public static function createV3UuidString(string $name, ?string $namespace = NULL): string {
        return static::$_veneerInstance->createV3UuidString(...func_get_args());
    }
    public static function createV4Uuid(): Ref2 {
        return static::$_veneerInstance->createV4Uuid();
    }
    public static function createV4UuidString(): string {
        return static::$_veneerInstance->createV4UuidString();
    }
    public static function createV4CombUuid(): Ref2 {
        return static::$_veneerInstance->createV4CombUuid();
    }
    public static function createV4CombUuidString(): string {
        return static::$_veneerInstance->createV4CombUuidString();
    }
    public static function createV5Uuid(string $name, ?string $namespace = NULL): Ref2 {
        return static::$_veneerInstance->createV5Uuid(...func_get_args());
    }
    public static function createV5UuidString(string $name, ?string $namespace = NULL): string {
        return static::$_veneerInstance->createV5UuidString(...func_get_args());
    }
    public static function createV6Uuid(string|int|null $node = NULL, ?int $clockSeq = NULL): Ref2 {
        return static::$_veneerInstance->createV6Uuid(...func_get_args());
    }
    public static function createV6UuidString(string|int|null $node = NULL, ?int $clockSeq = NULL): string {
        return static::$_veneerInstance->createV6UuidString(...func_get_args());
    }
    public static function createV7Uuid(?Ref4 $date = NULL): Ref2 {
        return static::$_veneerInstance->createV7Uuid(...func_get_args());
    }
    public static function createV7UuidString(?Ref4 $date = NULL): string {
        return static::$_veneerInstance->createV7UuidString(...func_get_args());
    }
    public static function createUlid(): Ref3 {
        return static::$_veneerInstance->createUlid();
    }
    public static function createUlidString(): string {
        return static::$_veneerInstance->createUlidString();
    }
    public static function isValidUuid(Ref5|Ref6|Ref2|string|null $uuid, bool $includeShort = false): bool {
        return static::$_veneerInstance->isValidUuid(...func_get_args());
    }
    public static function isValidUlid(Ref5|Ref6|Ref3|string|null $ulid): bool {
        return static::$_veneerInstance->isValidUlid(...func_get_args());
    }
    public static function uuidFrom(Ref5|Ref6|Ref2|string $uuid): Ref2 {
        return static::$_veneerInstance->uuidFrom(...func_get_args());
    }
    public static function ulidFrom(Ref5|Ref6|Ref3|string $ulid): Ref3 {
        return static::$_veneerInstance->ulidFrom(...func_get_args());
    }
    public static function tryUuidFrom(Ref5|Ref6|Ref2|string|null $uuid): ?Ref2 {
        return static::$_veneerInstance->tryUuidFrom(...func_get_args());
    }
    public static function tryUlidFrom(Ref5|Ref6|Ref3|string|null $ulid): ?Ref3 {
        return static::$_veneerInstance->tryUlidFrom(...func_get_args());
    }
    public static function uuidFromBytes(string $bytes): Ref2 {
        return static::$_veneerInstance->uuidFromBytes(...func_get_args());
    }
    public static function ulidFromBytes(string $bytes): Ref3 {
        return static::$_veneerInstance->ulidFromBytes(...func_get_args());
    }
    public static function uuidFromString(Ref5|string $uuid): Ref2 {
        return static::$_veneerInstance->uuidFromString(...func_get_args());
    }
    public static function ulidFromString(Ref5|string $ulid): Ref3 {
        return static::$_veneerInstance->ulidFromString(...func_get_args());
    }
    public static function uuidFromShortString(Ref5|string $uuid, ?Ref1 $format = NULL): Ref2 {
        return static::$_veneerInstance->uuidFromShortString(...func_get_args());
    }
    public static function uuidFromBigInteger(Ref6 $uuid): Ref2 {
        return static::$_veneerInstance->uuidFromBigInteger(...func_get_args());
    }
    public static function ulidFromBigInteger(Ref6 $ulid): Ref3 {
        return static::$_veneerInstance->ulidFromBigInteger(...func_get_args());
    }
    public static function shortenUuid(Ref5|Ref6|Ref2|string $uuid, ?Ref1 $format = NULL): string {
        return static::$_veneerInstance->shortenUuid(...func_get_args());
    }
    public static function getUuidDateTime(Ref5|Ref6|Ref2|string $uuid): ?Ref4 {
        return static::$_veneerInstance->getUuidDateTime(...func_get_args());
    }
    public static function getUlidDateTime(Ref5|Ref6|Ref3|string $ulid): ?Ref4 {
        return static::$_veneerInstance->getUlidDateTime(...func_get_args());
    }
};
