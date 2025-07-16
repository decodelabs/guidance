<?php

/**
 * @package Guidance
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Guidance;

use Brick\Math\BigInteger;
use DateTimeInterface;
use DecodeLabs\Exceptional;
use DecodeLabs\Guidance;
use DecodeLabs\Guidance\NanoId\Engine as NanoIdEngine;
use DecodeLabs\Guidance\Ulid\Engine as UlidEngine;
use DecodeLabs\Guidance\Uuid\Engine as UuidEngine;
use DecodeLabs\Guidance\Uuid\Engine\Ramsey as RamseyEngine;
use DecodeLabs\Guidance\Uuid\Format as UuidFormat;
use DecodeLabs\Veneer;
use InvalidArgumentException;
use Stringable;

class Context
{
    protected UuidEngine $uuidEngine {
        get => $this->uuidEngine ??= new RamseyEngine();
    }

    protected UuidFormat $defaultShortUuidFormat = UuidFormat::Base62;

    public function setUuidEngine(
        UuidEngine $engine
    ): void {
        $this->uuidEngine = $engine;
    }



    protected UlidEngine $ulidEngine {
        get => $this->ulidEngine ??= new UlidEngine();
    }

    protected NanoIdEngine $nanoIdEngine {
        get => $this->nanoIdEngine ??= new NanoIdEngine();
    }

    public function getNanoIdEngine(): NanoIdEngine
    {
        return $this->nanoIdEngine;
    }


    /**
     * @return static
     */
    public function setDefaultShortUuidFormat(
        UuidFormat $format
    ): static {
        $this->defaultShortUuidFormat = $format;
        return $this;
    }

    public function getDefaultShortUuidFormat(): UuidFormat
    {
        return $this->defaultShortUuidFormat;
    }


    public function createVoidUuid(): Uuid
    {
        return $this->uuidEngine->createVoid();
    }

    public function createVoidUuidString(): string
    {
        return (string)$this->uuidEngine->createVoid();
    }

    public function createVoidUlid(): Ulid
    {
        return $this->ulidEngine->createVoid();
    }

    public function createVoidUlidString(): string
    {
        return (string)$this->ulidEngine->createVoid();
    }

    public function createVoidNanoId(
        int $size = 21,
        Dictionary $dictionary = Dictionary::NanoId
    ): NanoId {
        return $this->nanoIdEngine->createVoid($size, $dictionary);
    }

    public function createVoidNanoIdString(
        int $size = 21,
        Dictionary $dictionary = Dictionary::NanoId
    ): string {
        return (string)$this->nanoIdEngine->createVoid($size, $dictionary);
    }



    public function createV1Uuid(
        int|string|null $node = null,
        ?int $clockSeq = null
    ): Uuid {
        return $this->uuidEngine->createV1($node, $clockSeq);
    }

    public function createV1UuidString(
        int|string|null $node = null,
        ?int $clockSeq = null
    ): string {
        return (string)$this->uuidEngine->createV1($node, $clockSeq);
    }

    public function createV3Uuid(
        string $name,
        ?string $namespace = null
    ): Uuid {
        return $this->uuidEngine->createV3($name, $namespace);
    }

    public function createV3UuidString(
        string $name,
        ?string $namespace = null
    ): string {
        return (string)$this->uuidEngine->createV3($name, $namespace);
    }

    public function createV4Uuid(): Uuid
    {
        return $this->uuidEngine->createV4();
    }

    public function createV4UuidString(): string
    {
        return (string)$this->uuidEngine->createV4();
    }

    public function createV4CombUuid(): Uuid
    {
        return $this->uuidEngine->createV4Comb();
    }

    public function createV4CombUuidString(): string
    {
        return (string)$this->uuidEngine->createV4Comb();
    }

    public function createV5Uuid(
        string $name,
        ?string $namespace = null
    ): Uuid {
        return $this->uuidEngine->createV5($name, $namespace);
    }

    public function createV5UuidString(
        string $name,
        ?string $namespace = null
    ): string {
        return (string)$this->uuidEngine->createV5($name, $namespace);
    }

    public function createV6Uuid(
        int|string|null $node = null,
        ?int $clockSeq = null
    ): Uuid {
        return $this->uuidEngine->createV6($node, $clockSeq);
    }

    public function createV6UuidString(
        int|string|null $node = null,
        ?int $clockSeq = null
    ): string {
        return (string)$this->uuidEngine->createV6($node, $clockSeq);
    }

    public function createV7Uuid(
        ?DateTimeInterface $date = null
    ): Uuid {
        return $this->uuidEngine->createV7($date);
    }

    public function createV7UuidString(
        ?DateTimeInterface $date = null
    ): string {
        return (string)$this->uuidEngine->createV7($date);
    }


    public function createUlid(): Ulid
    {
        return $this->ulidEngine->create();
    }

    public function createUlidString(): string
    {
        return (string)$this->ulidEngine->create();
    }



    public function createNanoId(
        int $size = 21,
        Dictionary $dictionary = Dictionary::NanoId
    ): NanoId {
        return $this->nanoIdEngine->create($size, $dictionary);
    }

    public function createNanoIdString(
        int $size = 21,
        Dictionary $dictionary = Dictionary::NanoId
    ): string {
        return (string)$this->nanoIdEngine->create($size, $dictionary);
    }





    public function isValidUuid(
        string|Stringable|BigInteger|Uuid|null $uuid,
        bool $includeShort = false
    ): bool {
        if ($uuid === null) {
            return false;
        }

        if (
            $uuid instanceof Uuid ||
            $uuid instanceof BigInteger
        ) {
            return true;
        }

        if ($uuid instanceof Stringable) {
            $uuid = (string)$uuid;
        }

        // Full string
        if (preg_match('/^([0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12})$/i', $uuid)) {
            return true;
        }

        if (!$includeShort) {
            return false;
        }

        // No dashes
        if (preg_match('/^[a-f0-9]{32}$/i', $uuid)) {
            return true;
        }

        // Short format
        if ($this->defaultShortUuidFormat->isValid($uuid)) {
            return true;
        }

        return false;
    }

    public function isValidUlid(
        string|Stringable|BigInteger|Ulid|null $ulid
    ): bool {
        if ($ulid === null) {
            return false;
        }

        if (
            $ulid instanceof Ulid ||
            $ulid instanceof BigInteger
        ) {
            return true;
        }

        if ($ulid instanceof Stringable) {
            $ulid = (string)$ulid;
        }

        // Full string
        if (preg_match('/^([0-9a-z]{26})$/i', $ulid)) {
            return true;
        }

        return false;
    }

    public function isValidNanoId(
        string|Stringable|BigInteger|NanoId|null $nanoId,
        Dictionary $dictionary = Dictionary::NanoId
    ): bool {
        if ($nanoId === null) {
            return false;
        }

        if (
            $nanoId instanceof NanoId ||
            $nanoId instanceof BigInteger
        ) {
            return true;
        }

        if ($nanoId instanceof Stringable) {
            $nanoId = (string)$nanoId;
        }

        if (preg_match('/^[' . preg_quote($dictionary->value, '/') . ']+$/i', $nanoId)) {
            return true;
        }

        return false;
    }



    public function uuidFrom(
        string|Stringable|BigInteger|Uuid $uuid
    ): Uuid {
        if (!$uuid = $this->tryUuidFrom($uuid)) {
            throw Exceptional::InvalidArgument(
                message: 'Invalid UUID',
                data: $uuid
            );
        }

        return $uuid;
    }

    public function ulidFrom(
        string|Stringable|BigInteger|Ulid $ulid
    ): Ulid {
        if (!$ulid = $this->tryUlidFrom($ulid)) {
            throw Exceptional::InvalidArgument(
                message: 'Invalid ULID',
                data: $ulid
            );
        }

        return $ulid;
    }

    public function nanoIdFrom(
        string|Stringable|BigInteger|NanoId $nanoId,
        Dictionary $dictionary = Dictionary::NanoId
    ): NanoId {
        if (!$nanoId = $this->tryNanoIdFrom($nanoId, $dictionary)) {
            throw Exceptional::InvalidArgument(
                message: 'Invalid NanoId',
                data: $nanoId
            );
        }

        return $nanoId;
    }



    public function tryUuidFrom(
        string|Stringable|BigInteger|Uuid|null $uuid
    ): ?Uuid {
        if (
            $uuid === null ||
            $uuid instanceof Uuid
        ) {
            return $uuid;
        }

        if ($uuid instanceof BigInteger) {
            return new Uuid($uuid->toBytes());
        }

        try {
            return $this->uuidFromString($uuid);
        } catch (InvalidArgumentException $e) {
        }

        try {
            return $this->uuidFromShortString($uuid);
        } catch (InvalidArgumentException $e) {
        }

        return null;
    }

    public function tryUlidFrom(
        string|Stringable|BigInteger|Ulid|null $ulid
    ): ?Ulid {
        if (
            $ulid === null ||
            $ulid instanceof Ulid
        ) {
            return $ulid;
        }

        if ($ulid instanceof BigInteger) {
            return new Ulid($ulid->toBytes());
        }

        try {
            return $this->ulidFromString($ulid);
        } catch (InvalidArgumentException $e) {
        }

        return null;
    }

    public function tryNanoIdFrom(
        string|Stringable|BigInteger|NanoId|null $nanoId,
        Dictionary $dictionary = Dictionary::NanoId
    ): ?NanoId {
        if (
            $nanoId === null ||
            $nanoId instanceof NanoId
        ) {
            return $nanoId;
        }

        if ($nanoId instanceof BigInteger) {
            return new NanoId($nanoId->toBytes(), $dictionary);
        }

        try {
            return $this->nanoIdFromString($nanoId, $dictionary);
        } catch (InvalidArgumentException $e) {
        }

        return null;
    }



    public function uuidFromBytes(
        string $bytes
    ): Uuid {
        return new Uuid($bytes);
    }

    public function ulidFromBytes(
        string $bytes
    ): Ulid {
        return new Ulid($bytes);
    }

    public function nanoIdFromBytes(
        string $bytes,
        Dictionary $dictionary = Dictionary::NanoId
    ): NanoId {
        return new NanoId($bytes, $dictionary);
    }



    public function uuidFromString(
        string|Stringable $uuid
    ): Uuid {
        return $this->uuidFromBytes($this->uuidStringToBytes($uuid));
    }

    public function ulidFromString(
        string|Stringable $ulid
    ): Ulid {
        return $this->ulidEngine->fromString((string)$ulid);
    }

    public function nanoIdFromString(
        string|Stringable $nanoId,
        Dictionary $dictionary = Dictionary::NanoId
    ): NanoId {
        return $this->nanoIdEngine->fromString((string)$nanoId, $dictionary);
    }



    public function uuidFromShortString(
        string|Stringable $uuid,
        ?UuidFormat $format = null
    ): Uuid {
        $uuid = (string)$uuid;

        if (preg_match('/^[a-f0-9]{32}|[a-f0-9\-]{36}$/i', $uuid)) {
            return $this->uuidFromString($uuid);
        }

        if (strlen($uuid) >= 32) {
            throw Exceptional::InvalidArgument(
                message: 'Invalid UUID: ' . $uuid
            );
        }

        $format ??= $this->defaultShortUuidFormat;
        return $this->uuidFromBytes($format->decode($uuid));
    }




    public function uuidFromBigInteger(
        BigInteger $uuid
    ): Uuid {
        return $this->uuidFromBytes($uuid->toBytes());
    }

    public function ulidFromBigInteger(
        BigInteger $ulid
    ): Ulid {
        return $this->ulidFromBytes($ulid->toBytes());
    }

    public function nanoIdFromBigInteger(
        BigInteger $nanoId,
        Dictionary $dictionary = Dictionary::NanoId
    ): NanoId {
        return $this->nanoIdFromBytes($nanoId->toBytes(), $dictionary);
    }



    protected function uuidStringToBytes(
        string|Stringable $uuid
    ): string {
        $uuid = (string)$uuid;

        if (strlen($uuid) === 16) {
            return $uuid;
        }

        $uuid = (string)preg_replace('/^urn:uuid:/is', '', $uuid);

        if (preg_match('/[^a-f0-9\-]/is', $uuid)) {
            throw Exceptional::InvalidArgument(
                message: 'Invalid UUID string: ' . $uuid
            );
        }

        $uuid = str_replace('-', '', $uuid);

        if (strlen($uuid) != 32) {
            throw Exceptional::InvalidArgument(
                message: 'Invalid UUID string: ' . $uuid
            );
        }

        return pack('H*', $uuid);
    }



    public function shortenUuid(
        string|Stringable|BigInteger|Uuid $uuid,
        ?UuidFormat $format = null
    ): string {
        $uuid = $this->uuidFrom($uuid);
        $format ??= $this->defaultShortUuidFormat;

        return $format->encode($uuid->bytes);
    }

    public function getUuidDateTime(
        string|Stringable|BigInteger|Uuid $uuid
    ): ?DateTimeInterface {
        if (!$uuid = $this->tryUuidFrom($uuid)) {
            return null;
        }

        return $this->uuidEngine->getDateTimeFromBytes($uuid->bytes);
    }

    public function getUlidDateTime(
        string|Stringable|BigInteger|Ulid $ulid
    ): ?DateTimeInterface {
        if (!$ulid = $this->tryUlidFrom($ulid)) {
            return null;
        }

        return $ulid->dateTime;
    }
}

// Veneer
Veneer\Manager::getGlobalManager()->register(
    Context::class,
    Guidance::class
);
