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
use DecodeLabs\Guidance\NanoId\Engine as Ref1;
use DecodeLabs\Guidance\Uuid\Format as Ref2;
use DecodeLabs\Guidance\Uuid as Ref3;
use DecodeLabs\Guidance\Ulid as Ref4;
use DecodeLabs\Guidance\Dictionary as Ref5;
use DecodeLabs\Guidance\NanoId as Ref6;
use DateTimeInterface as Ref7;
use Stringable as Ref8;
use Brick\Math\BigInteger as Ref9;

class Guidance implements Proxy
{
    use ProxyTrait;

    public const Veneer = 'DecodeLabs\\Guidance';
    public const VeneerTarget = Inst::class;

    protected static Inst $_veneerInstance;

    public static function setUuidEngine(Ref0 $engine): void {}
    public static function getNanoIdEngine(): Ref1 {
        return static::$_veneerInstance->getNanoIdEngine();
    }
    public static function setDefaultShortUuidFormat(Ref2 $format): Inst {
        return static::$_veneerInstance->setDefaultShortUuidFormat(...func_get_args());
    }
    public static function getDefaultShortUuidFormat(): Ref2 {
        return static::$_veneerInstance->getDefaultShortUuidFormat();
    }
    public static function createVoidUuid(): Ref3 {
        return static::$_veneerInstance->createVoidUuid();
    }
    public static function createVoidUuidString(): string {
        return static::$_veneerInstance->createVoidUuidString();
    }
    public static function createVoidUlid(): Ref4 {
        return static::$_veneerInstance->createVoidUlid();
    }
    public static function createVoidUlidString(): string {
        return static::$_veneerInstance->createVoidUlidString();
    }
    public static function createVoidNanoId(int $size = 21, Ref5 $dictionary = \DecodeLabs\Guidance\Dictionary::NanoId): Ref6 {
        return static::$_veneerInstance->createVoidNanoId(...func_get_args());
    }
    public static function createVoidNanoIdString(int $size = 21, Ref5 $dictionary = \DecodeLabs\Guidance\Dictionary::NanoId): string {
        return static::$_veneerInstance->createVoidNanoIdString(...func_get_args());
    }
    public static function createV1Uuid(string|int|null $node = NULL, ?int $clockSeq = NULL): Ref3 {
        return static::$_veneerInstance->createV1Uuid(...func_get_args());
    }
    public static function createV1UuidString(string|int|null $node = NULL, ?int $clockSeq = NULL): string {
        return static::$_veneerInstance->createV1UuidString(...func_get_args());
    }
    public static function createV3Uuid(string $name, ?string $namespace = NULL): Ref3 {
        return static::$_veneerInstance->createV3Uuid(...func_get_args());
    }
    public static function createV3UuidString(string $name, ?string $namespace = NULL): string {
        return static::$_veneerInstance->createV3UuidString(...func_get_args());
    }
    public static function createV4Uuid(): Ref3 {
        return static::$_veneerInstance->createV4Uuid();
    }
    public static function createV4UuidString(): string {
        return static::$_veneerInstance->createV4UuidString();
    }
    public static function createV4CombUuid(): Ref3 {
        return static::$_veneerInstance->createV4CombUuid();
    }
    public static function createV4CombUuidString(): string {
        return static::$_veneerInstance->createV4CombUuidString();
    }
    public static function createV5Uuid(string $name, ?string $namespace = NULL): Ref3 {
        return static::$_veneerInstance->createV5Uuid(...func_get_args());
    }
    public static function createV5UuidString(string $name, ?string $namespace = NULL): string {
        return static::$_veneerInstance->createV5UuidString(...func_get_args());
    }
    public static function createV6Uuid(string|int|null $node = NULL, ?int $clockSeq = NULL): Ref3 {
        return static::$_veneerInstance->createV6Uuid(...func_get_args());
    }
    public static function createV6UuidString(string|int|null $node = NULL, ?int $clockSeq = NULL): string {
        return static::$_veneerInstance->createV6UuidString(...func_get_args());
    }
    public static function createV7Uuid(?Ref7 $date = NULL): Ref3 {
        return static::$_veneerInstance->createV7Uuid(...func_get_args());
    }
    public static function createV7UuidString(?Ref7 $date = NULL): string {
        return static::$_veneerInstance->createV7UuidString(...func_get_args());
    }
    public static function createUlid(): Ref4 {
        return static::$_veneerInstance->createUlid();
    }
    public static function createUlidString(): string {
        return static::$_veneerInstance->createUlidString();
    }
    public static function createNanoId(int $size = 21, Ref5 $dictionary = \DecodeLabs\Guidance\Dictionary::NanoId): Ref6 {
        return static::$_veneerInstance->createNanoId(...func_get_args());
    }
    public static function createNanoIdString(int $size = 21, Ref5 $dictionary = \DecodeLabs\Guidance\Dictionary::NanoId): string {
        return static::$_veneerInstance->createNanoIdString(...func_get_args());
    }
    public static function isValidUuid(Ref8|Ref9|Ref3|string|null $uuid, bool $includeShort = false): bool {
        return static::$_veneerInstance->isValidUuid(...func_get_args());
    }
    public static function isValidUlid(Ref8|Ref9|Ref4|string|null $ulid): bool {
        return static::$_veneerInstance->isValidUlid(...func_get_args());
    }
    public static function isValidNanoId(Ref8|Ref9|Ref6|string|null $nanoId, Ref5 $dictionary = \DecodeLabs\Guidance\Dictionary::NanoId): bool {
        return static::$_veneerInstance->isValidNanoId(...func_get_args());
    }
    public static function uuidFrom(Ref8|Ref9|Ref3|string $uuid): Ref3 {
        return static::$_veneerInstance->uuidFrom(...func_get_args());
    }
    public static function ulidFrom(Ref8|Ref9|Ref4|string $ulid): Ref4 {
        return static::$_veneerInstance->ulidFrom(...func_get_args());
    }
    public static function nanoIdFrom(Ref8|Ref9|Ref6|string $nanoId, Ref5 $dictionary = \DecodeLabs\Guidance\Dictionary::NanoId): Ref6 {
        return static::$_veneerInstance->nanoIdFrom(...func_get_args());
    }
    public static function tryUuidFrom(Ref8|Ref9|Ref3|string|null $uuid): ?Ref3 {
        return static::$_veneerInstance->tryUuidFrom(...func_get_args());
    }
    public static function tryUlidFrom(Ref8|Ref9|Ref4|string|null $ulid): ?Ref4 {
        return static::$_veneerInstance->tryUlidFrom(...func_get_args());
    }
    public static function tryNanoIdFrom(Ref8|Ref9|Ref6|string|null $nanoId, Ref5 $dictionary = \DecodeLabs\Guidance\Dictionary::NanoId): ?Ref6 {
        return static::$_veneerInstance->tryNanoIdFrom(...func_get_args());
    }
    public static function uuidFromBytes(string $bytes): Ref3 {
        return static::$_veneerInstance->uuidFromBytes(...func_get_args());
    }
    public static function ulidFromBytes(string $bytes): Ref4 {
        return static::$_veneerInstance->ulidFromBytes(...func_get_args());
    }
    public static function nanoIdFromBytes(string $bytes, Ref5 $dictionary = \DecodeLabs\Guidance\Dictionary::NanoId): Ref6 {
        return static::$_veneerInstance->nanoIdFromBytes(...func_get_args());
    }
    public static function uuidFromString(Ref8|string $uuid): Ref3 {
        return static::$_veneerInstance->uuidFromString(...func_get_args());
    }
    public static function ulidFromString(Ref8|string $ulid): Ref4 {
        return static::$_veneerInstance->ulidFromString(...func_get_args());
    }
    public static function nanoIdFromString(Ref8|string $nanoId, Ref5 $dictionary = \DecodeLabs\Guidance\Dictionary::NanoId): Ref6 {
        return static::$_veneerInstance->nanoIdFromString(...func_get_args());
    }
    public static function uuidFromShortString(Ref8|string $uuid, ?Ref2 $format = NULL): Ref3 {
        return static::$_veneerInstance->uuidFromShortString(...func_get_args());
    }
    public static function uuidFromBigInteger(Ref9 $uuid): Ref3 {
        return static::$_veneerInstance->uuidFromBigInteger(...func_get_args());
    }
    public static function ulidFromBigInteger(Ref9 $ulid): Ref4 {
        return static::$_veneerInstance->ulidFromBigInteger(...func_get_args());
    }
    public static function nanoIdFromBigInteger(Ref9 $nanoId, Ref5 $dictionary = \DecodeLabs\Guidance\Dictionary::NanoId): Ref6 {
        return static::$_veneerInstance->nanoIdFromBigInteger(...func_get_args());
    }
    public static function shortenUuid(Ref8|Ref9|Ref3|string $uuid, ?Ref2 $format = NULL): string {
        return static::$_veneerInstance->shortenUuid(...func_get_args());
    }
    public static function getUuidDateTime(Ref8|Ref9|Ref3|string $uuid): ?Ref7 {
        return static::$_veneerInstance->getUuidDateTime(...func_get_args());
    }
    public static function getUlidDateTime(Ref8|Ref9|Ref4|string $ulid): ?Ref7 {
        return static::$_veneerInstance->getUlidDateTime(...func_get_args());
    }
};
