<?php

/**
 * @package Guidance
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Guidance\Ulid;

use DateTimeInterface;
use DateTimeImmutable;
use DecodeLabs\Exceptional;
use DecodeLabs\Guidance\Codec\Crockford32;
use DecodeLabs\Guidance\Ulid;

class Engine
{
    const Radix = 2 ** 32;
    const UInt8Max = 0b11111111;
    const TimeOffset = 0;
    const ClockSeqOffset = 6;
    const RandomOffset = 8;

    /**
     * @var array<int,int>
     */
    private array $previousBytes {
        // @phpstan-ignore-next-line
        get => $this->previousBytes ??= (
            array_values((array)unpack('C*', str_repeat("\0", 16)))
        );
    }

    private int $previousTime {
        get => $this->previousTime ??= -1;
    }

    public function createVoid(): Ulid
    {
        return new Ulid(
            str_repeat("\0", 16)
        );
    }

    public function create(): Ulid
    {
        $time = (int)(microtime(true) * 1000);
        $bytes = random_bytes(16);
        $bytes = array_values((array)unpack('C*', $bytes));
        /** @var array<int,int> $bytes */

        if($time <= $this->previousTime) {
            $this->restoreClockSequence($bytes);
            $this->incrementClockSequence($bytes);
        } else {
            $timeLow = $time % self::Radix;
            $timeHigh = ($time - $timeLow) / self::Radix;

            $idx = -1;
            $bytes[++$idx] = self::rightShift($timeHigh, 8) & self::UInt8Max;
            $bytes[++$idx] = self::rightShift($timeHigh, 0) & self::UInt8Max;
            $bytes[++$idx] = self::rightShift($timeLow, 24) & self::UInt8Max;
            $bytes[++$idx] = self::rightShift($timeLow, 16) & self::UInt8Max;
            $bytes[++$idx] = self::rightShift($timeLow, 8) & self::UInt8Max;
            $bytes[++$idx] = self::rightShift($timeLow, 0) & self::UInt8Max;
            $this->previousTime = $time;
        }

        $this->reserveClockSequence($bytes);
        $this->previousBytes = $bytes;

        return new Ulid(
            pack('C*', ...$bytes)
        );
    }

    /**
     * @param array<int,int> &$bytes
     */
    private function restoreClockSequence(
        array &$bytes
    ): void {
        for($idx = self::TimeOffset; $idx < self::RandomOffset; ++$idx) {
            $bytes[$idx] = $this->previousBytes[$idx];
        }
    }

    /**
     * @param array<int,int> &$bytes
     */
    private function incrementClockSequence(
        array &$bytes
    ): void {
        for(
            $idx = self::RandomOffset - 1,
            $end = self::ClockSeqOffset - 1;
            $idx > $end;
            --$idx
        ) {
            if($bytes[$idx] === 0xFF) {
                $bytes[$idx] = 0;
            } else {
                ++$bytes[$idx];
                return;
            }
        }

        throw Exceptional::Logic(
            message: 'Clock sequence overflow'
        );
    }

    /**
     * @param array<int,int> &$bytes
     */
    private function reserveClockSequence(
        array &$bytes
    ): void {
        $bytes[self::ClockSeqOffset] &= 0b01111111;
    }

    public static function extractDateTime(
        string $bytes
    ): DateTimeInterface {
        $idx = -1;
        $bytes = array_values((array)unpack('C*', $bytes));
        /** @var array<int,int> $bytes */

        $timeHigh = 0
            | ($bytes[++$idx] << 8)
            | ($bytes[++$idx] << 0);

        $timeLow = 0
            | ($bytes[++$idx] << 24)
            | ($bytes[++$idx] << 16)
            | ($bytes[++$idx] << 8)
            | ($bytes[++$idx] << 0);

        $time = ($timeHigh * Engine::Radix) + Engine::rightShift($timeLow, 0);
        return DateTimeImmutable::createFromTimestamp($time / 1000);
    }

    public static function rightShift(
        int $a,
        int $b
    ): int {
        if ($b >= 32 || $b < -32) {
            $m = (int)($b / 32);
            $b = $b - ($m * 32);
        }

        if ($b < 0) {
            $b = 32 + $b;
        }

        if ($b == 0) {
            return (($a >> 1) & 0x7fffffff) * 2 + (($a >> $b) & 1);
        }

        if ($a < 0)
        {
            $a = ($a >> 1);
            $a &= 0x7fffffff;
            $a |= 0x40000000;
            $a = ($a >> ($b - 1));
        } else {
            $a = ($a >> $b);
        }

        return $a;
    }


    public function fromString(
        string $ulid
    ): Ulid {
        if(strlen($ulid) === 16) {
            return new Ulid($ulid);
        }

        $ulid = (string)$ulid;
        $ulid = (string)preg_replace('/^urn:u(u|l)id:/is', '', $ulid);
        $ulid = (string)preg_replace('/^([0-9a-z])$/i', '$1', $ulid);

        if(!preg_match('/^([0-9a-z]{26})$/i', $ulid)) {
            throw Exceptional::InvalidArgument(
                message: 'Invalid ULID string',
                data: $ulid
            );
        }

        return new Ulid(
            Crockford32::decode($ulid)
        );
    }
}
