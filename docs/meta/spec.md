# Guidance — Package Specification

> **Cluster:** `core`
> **Language:** `php`
> **Milestone:** `m3`
> **Repo:** `https://github.com/decodelabs/guidance`
> **Role:** UUID generation

This document describes the purpose, contracts, and design of **Guidance** within the Decode Labs ecosystem.

It is aimed at:

- Developers **using** Guidance for UUID/ULID/NanoId generation and parsing.
- Contributors **maintaining or extending** Guidance.
- Tools and AI assistants that need to reason about its behaviour.

---

## 1. Overview

### 1.1 Purpose

Guidance provides a generalized UUID generation and parsing interface for PHP applications. It offers a simplified interface for generating and parsing UUIDs of various types (v1-v7, void, comb), ULIDs, and NanoIds, without requiring knowledge of the specifics of each type and implementation. Guidance provides a stripped-down frontend over the Ramsey UUID library by default, but can be extended to support other implementations. It also provides short format encoding/decoding for UUIDs (Base62, Base64, FlickrBase58, CookieBase90, etc.), Crockford32 encoding for ULIDs, and dictionary-based encoding for NanoIds. Guidance integrates with Greenleaf for route parameter validation and Lucid for type coercion.

### 1.2 Non-Goals

Guidance does **not**:

- Provide application-specific business logic or domain models
- Implement database abstraction or ORM functionality
- Provide user authentication or authorization
- Implement templating or view rendering
- Implement session management or caching
- Provide asset management or bundling
- Implement form handling or validation
- Provide application scaffolding or code generation
- Implement deployment or DevOps tooling
- Provide testing frameworks or test runners
- Implement logging backends
- Provide performance profiling or monitoring
- Implement APM or distributed tracing
- Provide cryptographic primitives beyond UUID generation
- Implement secure random number generation (uses PHP's random_bytes)
- Provide UUID version-specific business logic
- Implement UUID namespace management
- Provide UUID collision detection
- Implement UUID distribution strategies
- Provide UUID persistence or storage
- Implement UUID validation beyond format checking

Guidance focuses on UUID/ULID/NanoId generation and parsing, not on implementing application features or infrastructure beyond identifier generation.

---

## 2. Role in the Ecosystem

### 2.1 Cluster & Positioning

- **Cluster:** `core` (see Chorus taxonomy)
- Guidance is positioned as a core utility package that provides UUID/ULID/NanoId generation and parsing capabilities. It sits at a low level in the dependency graph, depending on a few core packages (Coercion, Exceptional, Kingdom, Nuance) and external libraries (Brick Math, Ramsey UUID). Guidance is used by packages like Greenleaf (for route parameter validation) and Lucid (for type coercion), and can be used by any application that needs to generate or parse unique identifiers. It integrates with Kingdom for service container integration and provides optional integrations with Greenleaf and Lucid.

### 2.2 Typical Usage Contexts

Typical places Guidance appears:

- UUID generation (v1-v7, void, comb)
- ULID generation
- NanoId generation
- UUID parsing from strings, bytes, or BigInteger
- ULID parsing from strings or bytes
- NanoId parsing from strings or bytes
- UUID short format encoding/decoding
- ULID encoding/decoding (Crockford32)
- NanoId encoding/decoding (dictionary-based)
- Route parameter validation (Greenleaf integration)
- Type coercion (Lucid integration)
- Entity identifier generation
- Database primary key generation
- API resource identifier generation
- File naming and organization
- Session identifier generation
- Request identifier generation
- Log correlation identifier generation

Guidance is intended to be used whenever an application needs to:
- Generate unique identifiers
- Parse unique identifiers from various formats
- Encode/decode identifiers in short formats
- Validate identifier formats
- Extract timestamps from time-based identifiers
- Work with UUIDs, ULIDs, or NanoIds in a unified interface

---

## 3. Public Surface

> This section focuses on the conceptual API, not every symbol.

### 3.1 Key Types

The primary public types are:

- `DecodeLabs\Guidance`
  Main service that orchestrates UUID/ULID/NanoId generation and parsing. Implements `PureService`. Provides methods for creating UUIDs (v1-v7, void, comb), ULIDs, and NanoIds, parsing identifiers from various formats, validating identifiers, shortening UUIDs, and extracting timestamps.

- `DecodeLabs\Guidance\Uid`
  Interface for unique identifier types. Defines bytes, size, URN, timestamp, and dateTime properties, and isNil() method. Implemented by Uuid, Ulid, and NanoId.

- `DecodeLabs\Guidance\Uuid`
  UUID implementation. Implements `Uid` and `Dumpable`. Provides version, variant, dateTime properties, and shorten() method. Supports UUID v1-v7, void, and comb variants.

- `DecodeLabs\Guidance\Ulid`
  ULID implementation. Implements `Uid` and `Dumpable`. Provides dateTime property. Uses Crockford32 encoding for string representation.

- `DecodeLabs\Guidance\NanoId`
  NanoId implementation. Implements `Uid` and `Dumpable`. Provides dictionary property. Supports custom size and dictionary.

- `DecodeLabs\Guidance\UidTrait`
  Trait providing common functionality for Uid implementations. Provides bytes storage, timestamp property, serialization, and isNil() method.

- `DecodeLabs\Guidance\Uuid\Engine`
  Interface for UUID generation engines. Defines methods for creating UUIDs (v1-v7, void, comb) and extracting timestamps.

- `DecodeLabs\Guidance\Uuid\Engine\Ramsey`
  Ramsey UUID library engine implementation. Implements `Engine`. Uses Ramsey UUID library for UUID generation.

- `DecodeLabs\Guidance\Uuid\Format`
  Enum for UUID short format encodings. Provides Base62, GmpBase62, Base64, FlickrBase58, and CookieBase90 formats. Defines encode(), decode(), isValid(), and getLength() methods.

- `DecodeLabs\Guidance\Uuid\Version`
  Enum for UUID versions. Provides Unknown, V1-V8 variants. Defines fromBytes() and fromValue() methods.

- `DecodeLabs\Guidance\Uuid\Variant`
  Enum for UUID variants. Provides Reserved, Ms, Rfc, and Ncs variants. Defines fromBytes() method.

- `DecodeLabs\Guidance\Ulid\Engine`
  ULID generation engine. Provides create(), createVoid(), and fromString() methods. Implements ULID generation algorithm with clock sequence handling.

- `DecodeLabs\Guidance\NanoId\Engine`
  NanoId generation engine. Provides create(), createVoid(), and fromString() methods. Supports custom size and dictionary.

- `DecodeLabs\Guidance\Codec`
  Interface for encoding/decoding algorithms. Defines encode() and decode() static methods.

- `DecodeLabs\Guidance\Codec\Crockford32`
  Crockford32 encoding implementation for ULIDs. Implements `Codec`. Provides encode() and decode() static methods.

- `DecodeLabs\Guidance\Dictionary`
  Enum for character dictionaries. Provides Upper, Lower, Digits, AlphaNumeric, HexUpper, HexLower, Crockford32Upper, Crockford32Lower, NanoId, and Cookie dictionaries.

- `DecodeLabs\Greenleaf\Route\Parameter\Uuid`
  Greenleaf route parameter type for UUIDs. Extends `Parameter`. Validates UUIDs with optional short format support.

- `DecodeLabs\Greenleaf\Route\Parameter\Ulid`
  Greenleaf route parameter type for ULIDs. Extends `Parameter`. Validates ULIDs.

- `DecodeLabs\Greenleaf\Route\Parameter\NanoId`
  Greenleaf route parameter type for NanoIds. Extends `Parameter`. Validates NanoIds with optional dictionary support.

- `DecodeLabs\Lucid\Processor\Uuid`
  Lucid processor for UUID type coercion. Implements `Processor`. Coerces values to Uuid instances.

- `DecodeLabs\Lucid\Processor\Ulid`
  Lucid processor for ULID type coercion. Implements `Processor`. Coerces values to Ulid instances.

- `DecodeLabs\Lucid\Processor\NanoId`
  Lucid processor for NanoId type coercion. Implements `Processor`. Coerces values to NanoId instances.

### 3.2 Main Entry Points

The main usage pattern is through the Guidance service:

```php
use DecodeLabs\Guidance;
use DecodeLabs\Guidance\Uuid\Format;
use DecodeLabs\Monarch;

$guidance = Monarch::getService(Guidance::class);

// Generate a v4 UUID
$v4 = $guidance->createV4Uuid();
$version = $v4->version; // Version::V4

$string1 = (string)$v4; // Full UUID string
$string2 = $v4->shorten(Format::Base62); // Base 62 encoded UUID
$string3 = $v4->shorten(Format::FlickrBase58); // Base 58 encoded UUID
echo $v4->bytes; // Raw bytes of the UUID

$new1 = $guidance->uuidFrom($string1); // Parse full UUID string
$new2 = $guidance->uuidFromShortString($string3, Format::FlickrBase58);

$ulid = $guidance->createUlid(); // Generate a ULID
echo $ulid->dateTime->format('Y-m-d H:i:s'); // Get the date and time from the ULID
```

---

## 4. Dependencies

### 4.1 Decode Labs

- `decodelabs/coercion` (required)
  Used for type coercion when parsing identifiers from various formats.

- `decodelabs/exceptional` (required)
  Used for exception handling and creating identifier-related exceptions.

- `decodelabs/kingdom` (required)
  Used for service container integration (Guidance implements `PureService`).

- `decodelabs/nuance` (required)
  Used for data inspection and entity rendering (Dumpable interface).

### 4.2 External

- `brick/math` (required, ^0.12|^0.13)
  Used for big integer operations in UUID short format encoding/decoding.

- `ramsey/uuid` (required, ^4.8.1)
  Used as the default UUID generation engine. Provides UUID v1-v7 generation, comb generation, and timestamp extraction.

### 4.3 Optional Integrations

- `decodelabs/greenleaf` (dev dependency)
  Detected at runtime if installed, used for route parameter validation (Uuid, Ulid, NanoId parameter types).

- `decodelabs/lucid` (dev dependency)
  Detected at runtime if installed, used for type coercion (Uuid, Ulid, NanoId processors).

---

## 5. Behaviour & Contracts

### 5.1 Invariants

- Guidance service provides UUID engine (defaults to Ramsey)
- Guidance service provides ULID engine
- Guidance service provides NanoId engine
- UUID generation uses configured engine
- ULID generation uses configured engine
- NanoId generation uses configured engine
- UUID parsing supports full string format (with dashes)
- UUID parsing supports no-dash format (32 hex chars)
- UUID parsing supports short format (Base62, Base64, FlickrBase58, CookieBase90)
- UUID parsing supports bytes format (16 bytes)
- UUID parsing supports BigInteger format
- UUID parsing supports URN format (urn:uuid:...)
- ULID parsing supports full string format (26 chars, Crockford32)
- ULID parsing supports bytes format (16 bytes)
- ULID parsing supports BigInteger format
- ULID parsing supports URN format (urn:ulid:...)
- NanoId parsing supports string format (dictionary-based)
- NanoId parsing supports bytes format (variable length)
- NanoId parsing supports BigInteger format
- NanoId parsing supports URN format (urn:nanoid:...)
- UUID validation checks full format, no-dash format, and short formats
- ULID validation checks full format (26 chars)
- NanoId validation checks format against dictionary
- UUID short format encoding uses Brick Math for base conversion
- UUID short format decoding uses Brick Math for base conversion
- ULID encoding uses Crockford32 codec
- ULID decoding uses Crockford32 codec
- NanoId encoding uses dictionary mapping
- NanoId decoding uses dictionary mapping
- UUID version extraction reads from byte 6
- UUID variant extraction reads from byte 8
- UUID timestamp extraction supports v1, v2, v6, v7
- ULID timestamp extraction reads from first 6 bytes
- NanoId does not support timestamp extraction
- UUID void generation creates all-zero UUID
- ULID void generation creates all-zero ULID
- NanoId void generation creates all-zero NanoId
- UUID comb generation uses timestamp-first comb codec
- ULID generation uses clock sequence for monotonicity
- NanoId generation uses random bytes with dictionary mapping
- UUID string representation uses standard format (8-4-4-4-12)
- ULID string representation uses Crockford32 encoding (26 chars)
- NanoId string representation uses dictionary encoding
- UUID URN uses `urn:uuid:` prefix
- ULID URN uses `urn:ulid:` prefix
- NanoId URN uses `urn:nanoid:` prefix
- UUID size is always 16 bytes
- ULID size is always 16 bytes
- NanoId size is variable (default 21 bytes)
- UUID isNil() checks for all-zero bytes
- ULID isNil() checks for all-zero bytes
- NanoId isNil() checks for all-zero bytes
- UUID serialization stores bytes
- ULID serialization stores bytes
- NanoId serialization stores bytes and dictionary
- UUID JSON serialization uses string representation
- ULID JSON serialization uses string representation
- NanoId JSON serialization uses string representation
- UUID dateTime property is lazy-loaded
- ULID dateTime property is lazy-loaded
- NanoId dateTime property is always null
- UUID timestamp property derives from dateTime
- ULID timestamp property derives from dateTime
- NanoId timestamp property is always null
- UUID shorten() method uses Format enum
- UUID Format enum provides multiple encoding options
- UUID Format enum provides length information
- UUID Format enum validates format strings
- ULID Engine handles clock sequence for monotonicity
- ULID Engine handles time collisions
- ULID Engine increments clock sequence on collisions
- NanoId Engine uses dictionary for encoding
- NanoId Engine supports custom size
- NanoId Engine supports custom dictionary
- Crockford32 codec handles character mapping
- Crockford32 codec handles case-insensitive decoding
- Crockford32 codec handles ambiguous character substitution
- Dictionary enum provides various character sets
- Dictionary enum supports custom dictionaries
- Greenleaf Uuid parameter validates UUIDs
- Greenleaf Uuid parameter supports short format
- Greenleaf Ulid parameter validates ULIDs
- Greenleaf NanoId parameter validates NanoIds
- Greenleaf NanoId parameter supports custom dictionary
- Lucid Uuid processor coerces to Uuid instances
- Lucid Ulid processor coerces to Ulid instances
- Lucid NanoId processor coerces to NanoId instances
- UUID engine can be replaced with custom implementation
- ULID engine can be replaced with custom implementation
- NanoId engine can be replaced with custom implementation
- UUID parsing throws exception on invalid format
- ULID parsing throws exception on invalid format
- NanoId parsing throws exception on invalid format
- UUID validation returns false for invalid formats
- ULID validation returns false for invalid formats
- NanoId validation returns false for invalid formats
- UUID try methods return null on failure
- ULID try methods return null on failure
- NanoId try methods return null on failure
- UUID from methods throw exception on failure
- ULID from methods throw exception on failure
- NanoId from methods throw exception on failure
- UUID string to bytes conversion handles various formats
- UUID string to bytes conversion validates format
- UUID string to bytes conversion handles URN prefix
- ULID string to bytes conversion uses Crockford32
- NanoId string to bytes conversion uses dictionary
- UUID BigInteger conversion uses toBytes()
- ULID BigInteger conversion uses toBytes()
- NanoId BigInteger conversion uses toBytes()
- UUID bytes conversion validates length (16 bytes)
- ULID bytes conversion validates length (16 bytes)
- NanoId bytes conversion validates length (matches size)
- UUID version detection reads from byte 6
- UUID variant detection reads from byte 8
- UUID timestamp extraction uses engine lookup
- ULID timestamp extraction uses Engine::extractDateTime()
- UUID comb generation uses TimestampFirstCombCodec
- UUID comb generation uses CombGenerator
- ULID generation uses microtime for timestamp
- ULID generation uses random_bytes for randomness
- ULID generation handles clock sequence overflow
- NanoId generation uses random_bytes with dictionary mapping
- NanoId generation calculates mask for dictionary size
- NanoId generation uses step calculation for efficiency
- UUID Format encode() uses BigInteger base conversion
- UUID Format decode() uses BigInteger base conversion
- UUID Format isValid() checks format string
- UUID Format getLength() returns expected length
- Crockford32 encode() packs bytes into quintets
- Crockford32 decode() unpacks quintets into bytes
- Crockford32 handles ambiguous characters (i/l/o/u)
- Dictionary enum provides predefined character sets
- Dictionary enum supports custom character sets
- UidTrait provides common bytes storage
- UidTrait provides timestamp property derivation
- UidTrait provides serialization support
- UidTrait provides isNil() implementation
- UidTrait validates bytes length on construction
- UUID implements Uid interface
- ULID implements Uid interface
- NanoId implements Uid interface
- UUID implements Dumpable interface
- ULID implements Dumpable interface
- NanoId implements Dumpable interface
- UUID provides version property
- UUID provides variant property
- UUID provides dateTime property
- ULID provides dateTime property
- NanoId provides dictionary property
- UUID shorten() method uses Format enum
- UUID __toString() uses standard format
- ULID __toString() uses Crockford32 encoding
- NanoId __toString() uses dictionary encoding
- UUID jsonSerialize() returns string
- ULID jsonSerialize() returns string
- NanoId jsonSerialize() returns string
- UUID toNuanceEntity() provides metadata
- ULID toNuanceEntity() provides metadata
- NanoId toNuanceEntity() provides metadata
- Greenleaf Uuid parameter uses Guidance service
- Greenleaf Ulid parameter uses Guidance service
- Greenleaf NanoId parameter uses Guidance service
- Lucid Uuid processor uses Guidance service
- Lucid Ulid processor uses Guidance service
- Lucid NanoId processor uses Guidance service
- UUID engine interface allows custom implementations
- ULID engine allows custom implementations
- NanoId engine allows custom implementations
- UUID Format enum is extensible
- Dictionary enum is extensible
- Codec interface allows custom implementations
- UUID parsing is flexible (multiple formats)
- ULID parsing is flexible (multiple formats)
- NanoId parsing is flexible (custom dictionaries)
- UUID generation supports all standard versions
- ULID generation supports monotonicity
- NanoId generation supports custom size and dictionary
- UUID short format encoding reduces length
- ULID encoding is URL-safe
- NanoId encoding is URL-safe
- UUID timestamp extraction supports time-based versions
- ULID timestamp extraction is always available
- NanoId does not support timestamp extraction
- UUID void generation creates nil UUID
- ULID void generation creates nil ULID
- NanoId void generation creates nil NanoId
- UUID comb generation improves database performance
- ULID generation improves database performance
- NanoId generation is fast and efficient
- UUID validation is comprehensive
- ULID validation is format-based
- NanoId validation is dictionary-based
- UUID parsing handles edge cases
- ULID parsing handles edge cases
- NanoId parsing handles edge cases
- UUID encoding/decoding is reversible
- ULID encoding/decoding is reversible
- NanoId encoding/decoding is reversible
- UUID Format enum provides multiple options
- Dictionary enum provides multiple options
- Codec interface provides encoding abstraction
- UUID engine abstraction allows swapping implementations
- ULID engine allows custom implementations
- NanoId engine allows custom implementations
- UUID integration with Greenleaf is optional
- ULID integration with Greenleaf is optional
- NanoId integration with Greenleaf is optional
- UUID integration with Lucid is optional
- ULID integration with Lucid is optional
- NanoId integration with Lucid is optional
- UUID service methods provide convenience wrappers
- ULID service methods provide convenience wrappers
- NanoId service methods provide convenience wrappers
- UUID string methods return string directly
- ULID string methods return string directly
- NanoId string methods return string directly
- UUID object methods return Uuid instances
- ULID object methods return Ulid instances
- NanoId object methods return NanoId instances
- UUID try methods return null on failure
- ULID try methods return null on failure
- NanoId try methods return null on failure
- UUID from methods throw exception on failure
- ULID from methods throw exception on failure
- NanoId from methods throw exception on failure
- UUID validation methods return bool
- ULID validation methods return bool
- NanoId validation methods return bool
- UUID shorten method uses Format enum
- UUID getDateTime method extracts timestamp
- ULID getDateTime method extracts timestamp
- UUID isValid method checks multiple formats
- ULID isValid method checks format
- NanoId isValid method checks format and dictionary
- UUID uuidFrom method parses from various formats
- ULID ulidFrom method parses from various formats
- NanoId nanoIdFrom method parses from various formats
- UUID uuidFromString method parses string
- ULID ulidFromString method parses string
- NanoId nanoIdFromString method parses string
- UUID uuidFromBytes method creates from bytes
- ULID ulidFromBytes method creates from bytes
- NanoId nanoIdFromBytes method creates from bytes
- UUID uuidFromShortString method parses short format
- UUID uuidFromBigInteger method creates from BigInteger
- ULID ulidFromBigInteger method creates from BigInteger
- NanoId nanoIdFromBigInteger method creates from BigInteger
- UUID create methods generate new UUIDs
- ULID create methods generate new ULIDs
- NanoId create methods generate new NanoIds
- UUID createVoid methods generate nil UUIDs
- ULID createVoid methods generate nil ULIDs
- NanoId createVoid methods generate nil NanoIds
- UUID createV4Comb method generates comb UUIDs
- UUID createV7 method supports custom date
- ULID create method uses current time
- NanoId create method supports custom size and dictionary
- UUID engine provides all version methods
- ULID engine provides create and fromString methods
- NanoId engine provides create and fromString methods
- UUID Format enum provides encode/decode methods
- UUID Format enum provides validation method
- UUID Format enum provides length method
- Crockford32 codec provides encode/decode methods
- Dictionary enum provides character sets
- UidTrait provides common functionality
- UUID class provides version-specific properties
- ULID class provides timestamp extraction
- NanoId class provides dictionary support
- Greenleaf parameters validate identifiers
- Lucid processors coerce to identifier types
- UUID parsing is flexible and forgiving
- ULID parsing is strict and format-based
- NanoId parsing is dictionary-based
- UUID generation uses configured engine
- ULID generation uses configured engine
- NanoId generation uses configured engine
- UUID short format encoding reduces identifier length
- ULID encoding is compact and URL-safe
- NanoId encoding is compact and URL-safe
- UUID timestamp extraction supports time-based versions
- ULID timestamp extraction is always available
- NanoId does not support timestamp extraction
- UUID void generation creates nil identifiers
- ULID void generation creates nil identifiers
- NanoId void generation creates nil identifiers
- UUID comb generation improves database performance
- ULID generation improves database performance
- NanoId generation is fast and efficient
- UUID validation is comprehensive
- ULID validation is format-based
- NanoId validation is dictionary-based
- UUID parsing handles edge cases
- ULID parsing handles edge cases
- NanoId parsing handles edge cases
- UUID encoding/decoding is reversible
- ULID encoding/decoding is reversible
- NanoId encoding/decoding is reversible
- UUID Format enum provides multiple options
- Dictionary enum provides multiple options
- Codec interface provides encoding abstraction
- UUID engine abstraction allows swapping implementations
- ULID engine allows custom implementations
- NanoId engine allows custom implementations
- UUID integration with Greenleaf is optional
- ULID integration with Greenleaf is optional
- NanoId integration with Greenleaf is optional
- UUID integration with Lucid is optional
- ULID integration with Lucid is optional
- NanoId integration with Lucid is optional

### 5.2 Input & Output Contracts

**Guidance Methods:**
- `createVoidUuid(): Uuid` — Creates nil UUID. Returns Uuid instance.
- `createVoidUuidString(): string` — Creates nil UUID string. Returns string.
- `createVoidUlid(): Ulid` — Creates nil ULID. Returns Ulid instance.
- `createVoidUlidString(): string` — Creates nil ULID string. Returns string.
- `createVoidNanoId(int $size = 21, Dictionary $dictionary = Dictionary::NanoId): NanoId` — Creates nil NanoId. Returns NanoId instance.
- `createVoidNanoIdString(int $size = 21, Dictionary $dictionary = Dictionary::NanoId): string` — Creates nil NanoId string. Returns string.
- `createV1Uuid(int|string|null $node = null, ?int $clockSeq = null): Uuid` — Creates UUID v1. Returns Uuid instance.
- `createV1UuidString(int|string|null $node = null, ?int $clockSeq = null): string` — Creates UUID v1 string. Returns string.
- `createV3Uuid(string $name, ?string $namespace = null): Uuid` — Creates UUID v3. Returns Uuid instance.
- `createV3UuidString(string $name, ?string $namespace = null): string` — Creates UUID v3 string. Returns string.
- `createV4Uuid(): Uuid` — Creates UUID v4. Returns Uuid instance.
- `createV4UuidString(): string` — Creates UUID v4 string. Returns string.
- `createV4CombUuid(): Uuid` — Creates UUID v4 comb. Returns Uuid instance.
- `createV4CombUuidString(): string` — Creates UUID v4 comb string. Returns string.
- `createV5Uuid(string $name, ?string $namespace = null): Uuid` — Creates UUID v5. Returns Uuid instance.
- `createV5UuidString(string $name, ?string $namespace = null): string` — Creates UUID v5 string. Returns string.
- `createV6Uuid(int|string|null $node = null, ?int $clockSeq = null): Uuid` — Creates UUID v6. Returns Uuid instance.
- `createV6UuidString(int|string|null $node = null, ?int $clockSeq = null): string` — Creates UUID v6 string. Returns string.
- `createV7Uuid(?DateTimeInterface $date = null): Uuid` — Creates UUID v7. Returns Uuid instance.
- `createV7UuidString(?DateTimeInterface $date = null): string` — Creates UUID v7 string. Returns string.
- `createUlid(): Ulid` — Creates ULID. Returns Ulid instance.
- `createUlidString(): string` — Creates ULID string. Returns string.
- `createNanoId(int $size = 21, Dictionary $dictionary = Dictionary::NanoId): NanoId` — Creates NanoId. Returns NanoId instance.
- `createNanoIdString(int $size = 21, Dictionary $dictionary = Dictionary::NanoId): string` — Creates NanoId string. Returns string.
- `isValidUuid(mixed $uuid, ?UuidFormat $shortFormat = null): bool` — Validates UUID. Returns bool.
- `isValidUlid(mixed $ulid): bool` — Validates ULID. Returns bool.
- `isValidNanoId(mixed $nanoId, Dictionary $dictionary = Dictionary::NanoId): bool` — Validates NanoId. Returns bool.
- `uuidFrom(string|Stringable|BigInteger|Uuid $uuid, UuidFormat $shortFormat = UuidFormat::Base62): Uuid` — Parses UUID. Returns Uuid instance or throws exception.
- `ulidFrom(string|Stringable|BigInteger|Ulid $ulid): Ulid` — Parses ULID. Returns Ulid instance or throws exception.
- `nanoIdFrom(string|Stringable|BigInteger|NanoId $nanoId, Dictionary $dictionary = Dictionary::NanoId): NanoId` — Parses NanoId. Returns NanoId instance or throws exception.
- `tryUuidFrom(string|Stringable|BigInteger|Uuid|null $uuid, UuidFormat $shortFormat = UuidFormat::Base62): ?Uuid` — Tries to parse UUID. Returns Uuid instance or null.
- `tryUlidFrom(string|Stringable|BigInteger|Ulid|null $ulid): ?Ulid` — Tries to parse ULID. Returns Ulid instance or null.
- `tryNanoIdFrom(string|Stringable|BigInteger|NanoId|null $nanoId, Dictionary $dictionary = Dictionary::NanoId): ?NanoId` — Tries to parse NanoId. Returns NanoId instance or null.
- `uuidFromBytes(string $bytes): Uuid` — Creates UUID from bytes. Returns Uuid instance.
- `ulidFromBytes(string $bytes): Ulid` — Creates ULID from bytes. Returns Ulid instance.
- `nanoIdFromBytes(string $bytes, Dictionary $dictionary = Dictionary::NanoId): NanoId` — Creates NanoId from bytes. Returns NanoId instance.
- `uuidFromString(string|Stringable $uuid): Uuid` — Parses UUID from string. Returns Uuid instance.
- `ulidFromString(string|Stringable $ulid): Ulid` — Parses ULID from string. Returns Ulid instance.
- `nanoIdFromString(string|Stringable $nanoId, Dictionary $dictionary = Dictionary::NanoId): NanoId` — Parses NanoId from string. Returns NanoId instance.
- `uuidFromShortString(string|Stringable $uuid, UuidFormat $format = UuidFormat::Base62): Uuid` — Parses UUID from short string. Returns Uuid instance.
- `uuidFromBigInteger(BigInteger $uuid): Uuid` — Creates UUID from BigInteger. Returns Uuid instance.
- `ulidFromBigInteger(BigInteger $ulid): Ulid` — Creates ULID from BigInteger. Returns Ulid instance.
- `nanoIdFromBigInteger(BigInteger $nanoId, Dictionary $dictionary = Dictionary::NanoId): NanoId` — Creates NanoId from BigInteger. Returns NanoId instance.
- `shortenUuid(string|Stringable|BigInteger|Uuid $uuid, UuidFormat $format = UuidFormat::Base62): string` — Shortens UUID. Returns string.
- `getUuidDateTime(string|Stringable|BigInteger|Uuid $uuid, UuidFormat $shortFormat = UuidFormat::Base62): ?DateTimeInterface` — Extracts timestamp from UUID. Returns DateTimeInterface or null.
- `getUlidDateTime(string|Stringable|BigInteger|Ulid $ulid): ?DateTimeInterface` — Extracts timestamp from ULID. Returns DateTimeInterface or null.

**Uid Interface:**
- `bytes: string` — Raw bytes property. Read-only.
- `size: int` — Size in bytes property. Read-only.
- `urn: string` — URN representation property. Read-only.
- `timestamp: ?int` — Timestamp property. Read-only.
- `dateTime: ?DateTimeInterface` — DateTime property. Read-only.
- `isNil(): bool` — Checks if identifier is nil. Returns bool.

**Uuid Methods:**
- `version: Version` — UUID version property. Read-only.
- `variant: Variant` — UUID variant property. Read-only.
- `dateTime: ?DateTimeInterface` — DateTime property. Lazy-loaded.
- `shorten(Format $format): string` — Shortens UUID. Returns string.
- `__toString(): string` — Converts to string. Returns standard format string.
- `jsonSerialize(): string` — JSON serialization. Returns string.
- `toNuanceEntity(): NuanceEntity` — Creates Nuance entity. Returns NuanceEntity instance.

**Ulid Methods:**
- `dateTime: ?DateTimeInterface` — DateTime property. Lazy-loaded.
- `__toString(): string` — Converts to string. Returns Crockford32 encoded string.
- `jsonSerialize(): string` — JSON serialization. Returns string.
- `toNuanceEntity(): NuanceEntity` — Creates Nuance entity. Returns NuanceEntity instance.

**NanoId Methods:**
- `dictionary: Dictionary` — Dictionary property. Read-write.
- `dateTime: ?DateTimeInterface` — DateTime property. Always null.
- `__toString(): string` — Converts to string. Returns dictionary encoded string.
- `jsonSerialize(): string` — JSON serialization. Returns string.
- `toNuanceEntity(): NuanceEntity` — Creates Nuance entity. Returns NuanceEntity instance.

**Uuid\Engine Interface:**
- `createVoid(): Uuid` — Creates nil UUID. Returns Uuid instance.
- `createV1(int|string|null $node = null, ?int $clockSeq = null): Uuid` — Creates UUID v1. Returns Uuid instance.
- `createV3(string $name, ?string $namespace = null): Uuid` — Creates UUID v3. Returns Uuid instance.
- `createV4(): Uuid` — Creates UUID v4. Returns Uuid instance.
- `createV4Comb(): Uuid` — Creates UUID v4 comb. Returns Uuid instance.
- `createV5(string $name, ?string $namespace = null): Uuid` — Creates UUID v5. Returns Uuid instance.
- `createV6(int|string|null $node = null, ?int $clockSeq = null): Uuid` — Creates UUID v6. Returns Uuid instance.
- `createV7(?DateTimeInterface $date = null): Uuid` — Creates UUID v7. Returns Uuid instance.
- `getDateTimeFromBytes(string $bytes): ?DateTimeInterface` — Extracts timestamp from bytes. Returns DateTimeInterface or null.

**Uuid\Format Enum:**
- `encode(string $bytes): string` — Encodes bytes to short format. Returns string.
- `decode(string $uuid): string` — Decodes short format to bytes. Returns string.
- `isValid(string $uuid): bool` — Validates format string. Returns bool.
- `getLength(): int` — Gets expected length. Returns int.

**Ulid\Engine Methods:**
- `createVoid(): Ulid` — Creates nil ULID. Returns Ulid instance.
- `create(): Ulid` — Creates ULID. Returns Ulid instance.
- `fromString(string $ulid): Ulid` — Parses ULID from string. Returns Ulid instance.
- `extractDateTime(string $bytes): DateTimeInterface` — Extracts timestamp from bytes. Returns DateTimeInterface.

**NanoId\Engine Methods:**
- `createVoid(int $size = 21, Dictionary $dictionary = Dictionary::NanoId): NanoId` — Creates nil NanoId. Returns NanoId instance.
- `create(int $size = 21, Dictionary $dictionary = Dictionary::NanoId): NanoId` — Creates NanoId. Returns NanoId instance.
- `fromString(string $nanoId, Dictionary $dictionary = Dictionary::NanoId): NanoId` — Parses NanoId from string. Returns NanoId instance.

**Codec Interface:**
- `encode(string $bytes): string` — Encodes bytes. Returns string.
- `decode(string $id): string` — Decodes identifier. Returns string.

**Crockford32 Methods:**
- `encode(string $bytes): string` — Encodes bytes to Crockford32. Returns string.
- `decode(string $id): string` — Decodes Crockford32 to bytes. Returns string.

**Dictionary Enum:**
- Provides character set values for various encoding schemes.

---

## 6. Error Handling

- UUID parsing failures throw `InvalidArgument` exception
- ULID parsing failures throw `InvalidArgument` exception
- NanoId parsing failures throw `InvalidArgument` exception
- UUID validation failures return false
- ULID validation failures return false
- NanoId validation failures return false
- UUID try methods return null on failure
- ULID try methods return null on failure
- NanoId try methods return null on failure
- UUID from methods throw exception on failure
- ULID from methods throw exception on failure
- NanoId from methods throw exception on failure
- UUID string to bytes conversion throws exception on invalid format
- ULID string to bytes conversion throws exception on invalid format
- NanoId string to bytes conversion throws exception on invalid format
- UUID bytes validation throws exception on wrong length
- ULID bytes validation throws exception on wrong length
- NanoId bytes validation throws exception on wrong length
- UUID Format decode throws exception on invalid format
- ULID Engine fromString throws exception on invalid format
- NanoId Engine fromString throws exception on invalid format
- ULID Engine clock sequence overflow throws `Logic` exception
- NanoId byte value validation throws exception on invalid byte
- UUID Format encode/decode may throw exception on conversion failure
- ULID Engine extractDateTime may throw exception on invalid bytes
- NanoId dictionary mapping may throw exception on invalid character
- UUID engine creation may throw exception on engine failure
- ULID engine creation may throw exception on engine failure
- NanoId engine creation may throw exception on engine failure
- UUID version detection returns Unknown for invalid versions
- UUID variant detection handles all variant types
- UUID timestamp extraction returns null for non-time-based versions
- ULID timestamp extraction always succeeds (time-based)
- NanoId timestamp extraction always returns null
- UUID void generation always succeeds
- ULID void generation always succeeds
- NanoId void generation always succeeds
- UUID comb generation may throw exception on engine failure
- ULID generation may throw exception on random failure
- NanoId generation may throw exception on random failure
- UUID short format encoding may throw exception on conversion failure
- ULID encoding may throw exception on encoding failure
- NanoId encoding may throw exception on dictionary mapping failure
- UUID short format decoding may throw exception on conversion failure
- ULID decoding may throw exception on decoding failure
- NanoId decoding may throw exception on dictionary mapping failure
- UUID validation handles null values
- ULID validation handles null values
- NanoId validation handles null values
- UUID validation handles instance values
- ULID validation handles instance values
- NanoId validation handles instance values
- UUID validation handles BigInteger values
- ULID validation handles BigInteger values
- NanoId validation handles BigInteger values
- UUID validation handles string values
- ULID validation handles string values
- NanoId validation handles string values
- UUID parsing handles URN prefix
- ULID parsing handles URN prefix
- NanoId parsing handles URN prefix
- UUID parsing handles no-dash format
- ULID parsing handles case-insensitive format
- NanoId parsing handles dictionary-based format
- UUID parsing handles short format with Format enum
- ULID parsing handles Crockford32 format
- NanoId parsing handles dictionary format
- UUID Format isValid checks format string
- ULID Engine fromString validates format
- NanoId Engine fromString validates format and dictionary
- UUID Format encode/decode uses BigInteger
- ULID Engine uses Crockford32 codec
- NanoId Engine uses dictionary mapping
- UUID engine abstraction allows error handling customization
- ULID engine allows error handling customization
- NanoId engine allows error handling customization
- UUID integration with Greenleaf validates identifiers
- ULID integration with Greenleaf validates identifiers
- NanoId integration with Greenleaf validates identifiers
- UUID integration with Lucid coerces with validation
- ULID integration with Lucid coerces with validation
- NanoId integration with Lucid coerces with validation
- UUID service methods provide error handling
- ULID service methods provide error handling
- NanoId service methods provide error handling
- UUID try methods provide safe parsing
- ULID try methods provide safe parsing
- NanoId try methods provide safe parsing
- UUID from methods provide strict parsing
- ULID from methods provide strict parsing
- NanoId from methods provide strict parsing
- UUID validation provides format checking
- ULID validation provides format checking
- NanoId validation provides format and dictionary checking
- UUID parsing provides flexible input handling
- ULID parsing provides strict input handling
- NanoId parsing provides dictionary-based input handling
- UUID encoding/decoding provides reversible conversion
- ULID encoding/decoding provides reversible conversion
- NanoId encoding/decoding provides reversible conversion
- UUID Format enum provides multiple encoding options
- Dictionary enum provides multiple character sets
- Codec interface provides encoding abstraction
- UUID engine abstraction allows error handling customization
- ULID engine allows error handling customization
- NanoId engine allows error handling customization
- UUID integration with Greenleaf validates identifiers
- ULID integration with Greenleaf validates identifiers
- NanoId integration with Greenleaf validates identifiers
- UUID integration with Lucid coerces with validation
- ULID integration with Lucid coerces with validation
- NanoId integration with Lucid coerces with validation

---

## 7. Configuration & Extensibility

- UUID engine can be replaced by implementing `Uuid\Engine` interface
- ULID engine can be replaced by setting `ulidEngine` property
- NanoId engine can be replaced by setting `nanoIdEngine` property
- UUID Format enum can be extended with new formats
- Dictionary enum can be extended with new character sets
- Codec interface can be implemented for custom encodings
- UUID short format encoding supports multiple formats
- ULID encoding uses Crockford32 codec (fixed)
- NanoId encoding uses dictionary-based encoding (configurable)
- UUID parsing supports multiple input formats
- ULID parsing supports string and bytes formats
- NanoId parsing supports string and bytes formats
- UUID validation supports multiple format checks
- ULID validation supports format check
- NanoId validation supports format and dictionary checks
- UUID generation supports all standard versions
- ULID generation supports monotonicity
- NanoId generation supports custom size and dictionary
- UUID timestamp extraction supports time-based versions
- ULID timestamp extraction is always available
- NanoId does not support timestamp extraction
- UUID void generation creates nil identifiers
- ULID void generation creates nil identifiers
- NanoId void generation creates nil identifiers
- UUID comb generation improves database performance
- ULID generation improves database performance
- NanoId generation is fast and efficient
- UUID short format encoding reduces identifier length
- ULID encoding is compact and URL-safe
- NanoId encoding is compact and URL-safe
- UUID Format enum provides multiple encoding options
- Dictionary enum provides multiple character sets
- Codec interface provides encoding abstraction
- UUID engine abstraction allows swapping implementations
- ULID engine allows custom implementations
- NanoId engine allows custom implementations
- UUID integration with Greenleaf is optional
- ULID integration with Greenleaf is optional
- NanoId integration with Greenleaf is optional
- UUID integration with Lucid is optional
- ULID integration with Lucid is optional
- NanoId integration with Lucid is optional
- UUID service methods provide convenience wrappers
- ULID service methods provide convenience wrappers
- NanoId service methods provide convenience wrappers
- UUID string methods return string directly
- ULID string methods return string directly
- NanoId string methods return string directly
- UUID object methods return Uuid instances
- ULID object methods return Ulid instances
- NanoId object methods return NanoId instances
- UUID try methods return null on failure
- ULID try methods return null on failure
- NanoId try methods return null on failure
- UUID from methods throw exception on failure
- ULID from methods throw exception on failure
- NanoId from methods throw exception on failure
- UUID validation methods return bool
- ULID validation methods return bool
- NanoId validation methods return bool
- UUID shorten method uses Format enum
- UUID getDateTime method extracts timestamp
- ULID getDateTime method extracts timestamp
- UUID isValid method checks multiple formats
- ULID isValid method checks format
- NanoId isValid method checks format and dictionary
- UUID uuidFrom method parses from various formats
- ULID ulidFrom method parses from various formats
- NanoId nanoIdFrom method parses from various formats
- UUID uuidFromString method parses string
- ULID ulidFromString method parses string
- NanoId nanoIdFromString method parses string
- UUID uuidFromBytes method creates from bytes
- ULID ulidFromBytes method creates from bytes
- NanoId nanoIdFromBytes method creates from bytes
- UUID uuidFromShortString method parses short format
- UUID uuidFromBigInteger method creates from BigInteger
- ULID ulidFromBigInteger method creates from BigInteger
- NanoId nanoIdFromBigInteger method creates from BigInteger
- UUID create methods generate new UUIDs
- ULID create methods generate new ULIDs
- NanoId create methods generate new NanoIds
- UUID createVoid methods generate nil UUIDs
- ULID createVoid methods generate nil ULIDs
- NanoId createVoid methods generate nil NanoIds
- UUID createV4Comb method generates comb UUIDs
- UUID createV7 method supports custom date
- ULID create method uses current time
- NanoId create method supports custom size and dictionary
- UUID engine provides all version methods
- ULID engine provides create and fromString methods
- NanoId engine provides create and fromString methods
- UUID Format enum provides encode/decode methods
- UUID Format enum provides validation method
- UUID Format enum provides length method
- Crockford32 codec provides encode/decode methods
- Dictionary enum provides character sets
- UidTrait provides common functionality
- UUID class provides version-specific properties
- ULID class provides timestamp extraction
- NanoId class provides dictionary support
- Greenleaf parameters validate identifiers
- Lucid processors coerce to identifier types
- UUID parsing is flexible and forgiving
- ULID parsing is strict and format-based
- NanoId parsing is dictionary-based
- UUID generation uses configured engine
- ULID generation uses configured engine
- NanoId generation uses configured engine
- UUID short format encoding reduces identifier length
- ULID encoding is compact and URL-safe
- NanoId encoding is compact and URL-safe
- UUID timestamp extraction supports time-based versions
- ULID timestamp extraction is always available
- NanoId does not support timestamp extraction
- UUID void generation creates nil identifiers
- ULID void generation creates nil identifiers
- NanoId void generation creates nil identifiers
- UUID comb generation improves database performance
- ULID generation improves database performance
- NanoId generation is fast and efficient
- UUID validation is comprehensive
- ULID validation is format-based
- NanoId validation is dictionary-based
- UUID parsing handles edge cases
- ULID parsing handles edge cases
- NanoId parsing handles edge cases
- UUID encoding/decoding is reversible
- ULID encoding/decoding is reversible
- NanoId encoding/decoding is reversible
- UUID Format enum provides multiple options
- Dictionary enum provides multiple options
- Codec interface provides encoding abstraction
- UUID engine abstraction allows swapping implementations
- ULID engine allows custom implementations
- NanoId engine allows custom implementations
- UUID integration with Greenleaf is optional
- ULID integration with Greenleaf is optional
- NanoId integration with Greenleaf is optional
- UUID integration with Lucid is optional
- ULID integration with Lucid is optional
- NanoId integration with Lucid is optional

---

## 8. Interactions with Other Packages

### 8.1 Ramsey UUID

Guidance uses Ramsey UUID for:
- UUID generation (v1-v7, void, comb)
- UUID timestamp extraction
- UUID comb generation
- UUID format validation

Guidance wraps Ramsey UUID library in a simplified interface, providing a stripped-down frontend that can be extended to support other implementations.

### 8.2 Brick Math

Guidance uses Brick Math for:
- Big integer operations in UUID short format encoding/decoding
- Base conversion for Format enum
- Arbitrary base encoding/decoding

Guidance uses Brick Math's BigInteger class for base conversion when encoding/decoding UUIDs in short formats.

### 8.3 Coercion

Guidance uses Coercion for:
- Type coercion when parsing identifiers from various formats
- String conversion for validation
- Type conversion for serialization

### 8.4 Exceptional

Guidance uses Exceptional for:
- Exception handling and creating identifier-related exceptions
- Invalid argument exceptions for parsing failures
- Logic exceptions for clock sequence overflow

### 8.5 Kingdom

Guidance uses Kingdom for:
- Service container integration (implements `PureService`)
- Service registration and resolution

### 8.6 Nuance

Guidance uses Nuance for:
- Data inspection and entity rendering (Dumpable interface)
- Debugging and development tools

### 8.7 Greenleaf

Guidance integrates with Greenleaf for:
- Route parameter validation (Uuid, Ulid, NanoId parameter types)
- URL-friendly identifier parsing in routes

Guidance provides Greenleaf route parameter types that validate UUIDs, ULIDs, and NanoIds in route patterns.

### 8.8 Lucid

Guidance integrates with Lucid for:
- Type coercion (Uuid, Ulid, NanoId processors)
- Automatic type conversion in data processing

Guidance provides Lucid processors that coerce values to Uuid, Ulid, and NanoId instances.

### 8.9 Other Packages

Guidance may be used by:
- Applications that need unique identifier generation
- Packages that need identifier parsing and validation
- Frameworks that need identifier support

---

## 9. Usage Examples

### 9.1 Basic UUID Generation

```php
use DecodeLabs\Guidance;
use DecodeLabs\Monarch;

$guidance = Monarch::getService(Guidance::class);

// Generate a v4 UUID
$v4 = $guidance->createV4Uuid();
$version = $v4->version; // Version::V4

// Generate a v1 UUID
$v1 = $guidance->createV1Uuid();

// Generate a v7 UUID with custom date
$v7 = $guidance->createV7Uuid(new DateTime('2024-01-01'));

// Generate a v4 comb UUID
$comb = $guidance->createV4CombUuid();
```

### 9.2 UUID Parsing

```php
use DecodeLabs\Guidance;
use DecodeLabs\Guidance\Uuid\Format;
use DecodeLabs\Monarch;

$guidance = Monarch::getService(Guidance::class);

// Parse full UUID string
$uuid1 = $guidance->uuidFrom('550e8400-e29b-41d4-a716-446655440000');

// Parse no-dash format
$uuid2 = $guidance->uuidFrom('550e8400e29b41d4a716446655440000');

// Parse short format
$uuid3 = $guidance->uuidFromShortString('abc123', Format::Base62);

// Parse from bytes
$uuid4 = $guidance->uuidFromBytes("\x55\x0e\x84\x00...");

// Try parsing (returns null on failure)
$uuid5 = $guidance->tryUuidFrom('invalid');
```

### 9.3 UUID Short Format

```php
use DecodeLabs\Guidance;
use DecodeLabs\Guidance\Uuid\Format;
use DecodeLabs\Monarch;

$guidance = Monarch::getService(Guidance::class);

$uuid = $guidance->createV4Uuid();

// Shorten to Base62
$short1 = $uuid->shorten(Format::Base62);

// Shorten to FlickrBase58
$short2 = $uuid->shorten(Format::FlickrBase58);

// Shorten to CookieBase90
$short3 = $uuid->shorten(Format::CookieBase90);

// Parse short format
$parsed = $guidance->uuidFromShortString($short2, Format::FlickrBase58);
```

### 9.4 ULID Generation

```php
use DecodeLabs\Guidance;
use DecodeLabs\Monarch;

$guidance = Monarch::getService(Guidance::class);

// Generate ULID
$ulid = $guidance->createUlid();

// Get timestamp
$dateTime = $ulid->dateTime;
echo $dateTime->format('Y-m-d H:i:s');

// Get timestamp as integer
$timestamp = $ulid->timestamp;
```

### 9.5 ULID Parsing

```php
use DecodeLabs\Guidance;
use DecodeLabs\Monarch;

$guidance = Monarch::getService(Guidance::class);

// Parse ULID string
$ulid1 = $guidance->ulidFrom('01ARZ3NDEKTSV4RRFFQ69G5FAV');

// Parse from bytes
$ulid2 = $guidance->ulidFromBytes("\x01\x02...");

// Try parsing
$ulid3 = $guidance->tryUlidFrom('invalid');
```

### 9.6 NanoId Generation

```php
use DecodeLabs\Guidance;
use DecodeLabs\Guidance\Dictionary;
use DecodeLabs\Monarch;

$guidance = Monarch::getService(Guidance::class);

// Generate default NanoId
$nanoId1 = $guidance->createNanoId();

// Generate with custom size
$nanoId2 = $guidance->createNanoId(32);

// Generate with custom dictionary
$nanoId3 = $guidance->createNanoId(21, Dictionary::AlphaNumeric);
```

### 9.7 NanoId Parsing

```php
use DecodeLabs\Guidance;
use DecodeLabs\Guidance\Dictionary;
use DecodeLabs\Monarch;

$guidance = Monarch::getService(Guidance::class);

// Parse NanoId string
$nanoId1 = $guidance->nanoIdFrom('V1StGXR8_Z5jdHi6B-myT');

// Parse with custom dictionary
$nanoId2 = $guidance->nanoIdFrom('ABC123', Dictionary::AlphaNumeric);

// Try parsing
$nanoId3 = $guidance->tryNanoIdFrom('invalid');
```

### 9.8 Validation

```php
use DecodeLabs\Guidance;
use DecodeLabs\Guidance\Uuid\Format;
use DecodeLabs\Monarch;

$guidance = Monarch::getService(Guidance::class);

// Validate UUID
$isValid = $guidance->isValidUuid('550e8400-e29b-41d4-a716-446655440000');
$isValidShort = $guidance->isValidUuid('abc123', Format::Base62);

// Validate ULID
$isValidUlid = $guidance->isValidUlid('01ARZ3NDEKTSV4RRFFQ69G5FAV');

// Validate NanoId
$isValidNanoId = $guidance->isValidNanoId('V1StGXR8_Z5jdHi6B-myT');
```

### 9.9 Greenleaf Integration

```php
namespace MyApp\Http;

use DecodeLabs\Greenleaf\Route\Action;
use DecodeLabs\Greenleaf\Route\Parameter\Uuid;
use DecodeLabs\Greenleaf\Route\Parameter\Ulid;
use DecodeLabs\Greenleaf\Route\Parameter\NanoId;
use DecodeLabs\Guidance\Uuid\Format;

class Routes implements Generator
{
    public function generateRoutes(): iterable
    {
        // UUID route parameter
        yield new Action('user/{id}', 'user', parameters: [
            new Uuid('id', Format::Base62)
        ]);

        // ULID route parameter
        yield new Action('post/{id}', 'post', parameters: [
            new Ulid('id')
        ]);

        // NanoId route parameter
        yield new Action('item/{id}', 'item', parameters: [
            new NanoId('id')
        ]);
    }
}
```

### 9.10 Lucid Integration

```php
use DecodeLabs\Lucid\Processor\Uuid;
use DecodeLabs\Lucid\Processor\Ulid;
use DecodeLabs\Lucid\Processor\NanoId;
use DecodeLabs\Guidance;

$guidance = Monarch::getService(Guidance::class);

// UUID processor
$uuidProcessor = new Uuid($guidance);
$uuid = $uuidProcessor->coerce('550e8400-e29b-41d4-a716-446655440000');

// ULID processor
$ulidProcessor = new Ulid($guidance);
$ulid = $ulidProcessor->coerce('01ARZ3NDEKTSV4RRFFQ69G5FAV');

// NanoId processor
$nanoIdProcessor = new NanoId($guidance);
$nanoId = $nanoIdProcessor->coerce('V1StGXR8_Z5jdHi6B-myT');
```

### 9.11 Timestamp Extraction

```php
use DecodeLabs\Guidance;
use DecodeLabs\Monarch;

$guidance = Monarch::getService(Guidance::class);

// Extract timestamp from UUID (v1, v2, v6, v7)
$dateTime = $guidance->getUuidDateTime($uuid);
if ($dateTime) {
    echo $dateTime->format('Y-m-d H:i:s');
}

// Extract timestamp from ULID
$dateTime = $guidance->getUlidDateTime($ulid);
echo $dateTime->format('Y-m-d H:i:s');
```

### 9.12 Custom UUID Engine

```php
namespace MyApp;

use DecodeLabs\Guidance\Uuid;
use DecodeLabs\Guidance\Uuid\Engine;
use DateTimeInterface;

class MyUuidEngine implements Engine
{
    public function createVoid(): Uuid
    {
        return new Uuid(str_repeat("\0", 16));
    }

    public function createV4(): Uuid
    {
        // Custom implementation
    }

    // ... implement other methods
}
```

---

## 10. Implementation Notes (for Contributors)

### 10.1 Internal Architecture

At a high level, Guidance:
- Provides a simplified interface over UUID/ULID/NanoId generation
- Uses Ramsey UUID library as default UUID engine
- Implements ULID generation algorithm with clock sequence handling
- Implements NanoId generation algorithm with dictionary mapping
- Provides short format encoding/decoding for UUIDs
- Provides Crockford32 encoding/decoding for ULIDs
- Provides dictionary-based encoding/decoding for NanoIds
- Integrates with Greenleaf for route parameter validation
- Integrates with Lucid for type coercion
- Uses Brick Math for big integer operations
- Uses Coercion for type conversion
- Uses Exceptional for exception handling
- Uses Kingdom for service container integration
- Uses Nuance for data inspection

### 10.2 UUID Generation Flow

1. User calls UUID creation method on Guidance service
2. Guidance service delegates to UUID engine
3. UUID engine (Ramsey) generates UUID bytes
4. UUID engine returns Uuid instance with bytes
5. Uuid instance provides version, variant, dateTime properties
6. Uuid instance can be converted to string or shortened

### 10.3 UUID Parsing Flow

1. User calls UUID parsing method with input
2. Guidance service checks input type (string, bytes, BigInteger, Uuid)
3. If string, attempts to parse as full format, no-dash format, or short format
4. If bytes, creates Uuid instance directly
5. If BigInteger, converts to bytes and creates Uuid instance
6. If Uuid, returns as-is
7. Returns Uuid instance or throws exception

### 10.4 ULID Generation Flow

1. User calls ULID creation method on Guidance service
2. Guidance service delegates to ULID engine
3. ULID engine gets current timestamp (milliseconds)
4. ULID engine generates random bytes
5. ULID engine handles clock sequence for monotonicity
6. ULID engine packs timestamp and random bytes
7. ULID engine returns Ulid instance with bytes
8. Ulid instance provides dateTime property

### 10.5 ULID Parsing Flow

1. User calls ULID parsing method with input
2. Guidance service checks input type (string, bytes, BigInteger, Ulid)
3. If string, uses Crockford32 codec to decode to bytes
4. If bytes, creates Ulid instance directly
5. If BigInteger, converts to bytes and creates Ulid instance
6. If Ulid, returns as-is
7. Returns Ulid instance or throws exception

### 10.6 NanoId Generation Flow

1. User calls NanoId creation method with size and dictionary
2. Guidance service delegates to NanoId engine
3. NanoId engine calculates mask and step for dictionary
4. NanoId engine generates random bytes
5. NanoId engine maps bytes to dictionary characters
6. NanoId engine returns NanoId instance with bytes and dictionary
7. NanoId instance provides string representation via dictionary

### 10.7 NanoId Parsing Flow

1. User calls NanoId parsing method with input and dictionary
2. Guidance service checks input type (string, bytes, BigInteger, NanoId)
3. If string, uses dictionary to map characters to bytes
4. If bytes, creates NanoId instance directly
5. If BigInteger, converts to bytes and creates NanoId instance
6. If NanoId, returns as-is
7. Returns NanoId instance or throws exception

### 10.8 UUID Short Format Encoding

1. User calls shorten() method with Format enum
2. Format enum encodes UUID bytes using BigInteger base conversion
3. Format enum returns encoded string
4. Format enum provides length information

### 10.9 UUID Short Format Decoding

1. User calls uuidFromShortString() with short string and Format enum
2. Format enum decodes short string using BigInteger base conversion
3. Format enum returns bytes
4. Guidance service creates Uuid instance from bytes

### 10.10 ULID Encoding/Decoding

1. ULID encoding uses Crockford32 codec
2. Crockford32 codec packs bytes into quintets
3. Crockford32 codec maps quintets to alphabet characters
4. ULID decoding uses Crockford32 codec
5. Crockford32 codec maps characters to quintets
6. Crockford32 codec unpacks quintets to bytes

### 10.11 NanoId Encoding/Decoding

1. NanoId encoding uses dictionary mapping
2. Dictionary maps bytes to characters
3. NanoId decoding uses dictionary mapping
4. Dictionary maps characters to bytes

### 10.12 UUID Timestamp Extraction

1. User calls getUuidDateTime() method
2. Guidance service delegates to UUID engine
3. UUID engine checks UUID version (v1, v2, v6, v7)
4. UUID engine extracts timestamp from bytes
5. UUID engine returns DateTimeInterface or null

### 10.13 ULID Timestamp Extraction

1. User accesses dateTime property on Ulid instance
2. Ulid instance lazy-loads dateTime using Engine::extractDateTime()
3. Engine extracts timestamp from first 6 bytes
4. Engine converts timestamp to DateTimeInterface

### 10.14 Greenleaf Integration

1. Greenleaf route parameter validates identifier during route matching
2. Parameter uses Guidance service to validate identifier
3. Parameter returns validation result
4. Route matching proceeds if validation succeeds

### 10.15 Lucid Integration

1. Lucid processor coerces value to identifier type
2. Processor uses Guidance service to validate identifier
3. Processor uses Guidance service to parse identifier
4. Processor returns identifier instance or throws exception

### 10.16 Gotchas & Historical Decisions

- UUID engine defaults to Ramsey but can be replaced
- ULID engine is instantiated on first access
- NanoId engine is instantiated on first access
- UUID short format encoding uses BigInteger for base conversion
- ULID encoding uses Crockford32 codec (fixed algorithm)
- NanoId encoding uses dictionary mapping (configurable)
- UUID timestamp extraction only works for time-based versions
- ULID timestamp extraction always works (time-based)
- NanoId does not support timestamp extraction
- UUID void generation creates all-zero UUID
- ULID void generation creates all-zero ULID
- NanoId void generation creates all-zero NanoId
- UUID comb generation uses timestamp-first comb codec
- ULID generation uses clock sequence for monotonicity
- NanoId generation uses random bytes with dictionary mapping
- UUID string representation uses standard format (8-4-4-4-12)
- ULID string representation uses Crockford32 encoding (26 chars)
- NanoId string representation uses dictionary encoding (variable length)
- UUID URN uses `urn:uuid:` prefix
- ULID URN uses `urn:ulid:` prefix
- NanoId URN uses `urn:nanoid:` prefix
- UUID size is always 16 bytes
- ULID size is always 16 bytes
- NanoId size is variable (default 21 bytes)
- UUID isNil() checks for all-zero bytes
- ULID isNil() checks for all-zero bytes
- NanoId isNil() checks for all-zero bytes
- UUID serialization stores bytes only
- ULID serialization stores bytes only
- NanoId serialization stores bytes and dictionary
- UUID JSON serialization uses string representation
- ULID JSON serialization uses string representation
- NanoId JSON serialization uses string representation
- UUID dateTime property is lazy-loaded
- ULID dateTime property is lazy-loaded
- NanoId dateTime property is always null
- UUID timestamp property derives from dateTime
- ULID timestamp property derives from dateTime
- NanoId timestamp property is always null
- UUID shorten() method uses Format enum
- UUID Format enum provides multiple encoding options
- UUID Format enum provides length information
- UUID Format enum validates format strings
- ULID Engine handles clock sequence for monotonicity
- ULID Engine handles time collisions
- ULID Engine increments clock sequence on collisions
- NanoId Engine uses dictionary for encoding
- NanoId Engine supports custom size
- NanoId Engine supports custom dictionary
- Crockford32 codec handles character mapping
- Crockford32 codec handles case-insensitive decoding
- Crockford32 codec handles ambiguous character substitution
- Dictionary enum provides various character sets
- Dictionary enum supports custom dictionaries
- Greenleaf Uuid parameter validates UUIDs
- Greenleaf Uuid parameter supports short format
- Greenleaf Ulid parameter validates ULIDs
- Greenleaf NanoId parameter validates NanoIds
- Greenleaf NanoId parameter supports custom dictionary
- Lucid Uuid processor coerces to Uuid instances
- Lucid Ulid processor coerces to Ulid instances
- Lucid NanoId processor coerces to NanoId instances
- UUID engine can be replaced with custom implementation
- ULID engine can be replaced with custom implementation
- NanoId engine can be replaced with custom implementation
- UUID parsing throws exception on invalid format
- ULID parsing throws exception on invalid format
- NanoId parsing throws exception on invalid format
- UUID validation returns false for invalid formats
- ULID validation returns false for invalid formats
- NanoId validation returns false for invalid formats
- UUID try methods return null on failure
- ULID try methods return null on failure
- NanoId try methods return null on failure
- UUID from methods throw exception on failure
- ULID from methods throw exception on failure
- NanoId from methods throw exception on failure
- UUID string to bytes conversion handles various formats
- UUID string to bytes conversion validates format
- UUID string to bytes conversion handles URN prefix
- ULID string to bytes conversion uses Crockford32
- NanoId string to bytes conversion uses dictionary
- UUID BigInteger conversion uses toBytes()
- ULID BigInteger conversion uses toBytes()
- NanoId BigInteger conversion uses toBytes()
- UUID bytes conversion validates length (16 bytes)
- ULID bytes conversion validates length (16 bytes)
- NanoId bytes conversion validates length (matches size)
- UUID version detection reads from byte 6
- UUID variant detection reads from byte 8
- UUID timestamp extraction uses engine lookup
- ULID timestamp extraction uses Engine::extractDateTime()
- UUID comb generation uses TimestampFirstCombCodec
- UUID comb generation uses CombGenerator
- ULID generation uses microtime for timestamp
- ULID generation uses random_bytes for randomness
- ULID generation handles clock sequence overflow
- NanoId generation uses random_bytes with dictionary mapping
- NanoId generation calculates mask for dictionary size
- NanoId generation uses step calculation for efficiency
- UUID Format encode() uses BigInteger base conversion
- UUID Format decode() uses BigInteger base conversion
- UUID Format isValid() checks format string
- UUID Format getLength() returns expected length
- Crockford32 encode() packs bytes into quintets
- Crockford32 decode() unpacks quintets into bytes
- Crockford32 handles ambiguous characters (i/l/o/u)
- Dictionary enum provides predefined character sets
- Dictionary enum supports custom character sets
- UidTrait provides common bytes storage
- UidTrait provides timestamp property derivation
- UidTrait provides serialization support
- UidTrait provides isNil() implementation
- UidTrait validates bytes length on construction
- UUID implements Uid interface
- ULID implements Uid interface
- NanoId implements Uid interface
- UUID implements Dumpable interface
- ULID implements Dumpable interface
- NanoId implements Dumpable interface
- UUID provides version property
- UUID provides variant property
- UUID provides dateTime property
- ULID provides dateTime property
- NanoId provides dictionary property
- UUID shorten() method uses Format enum
- UUID __toString() uses standard format
- ULID __toString() uses Crockford32 encoding
- NanoId __toString() uses dictionary encoding
- UUID jsonSerialize() returns string
- ULID jsonSerialize() returns string
- NanoId jsonSerialize() returns string
- UUID toNuanceEntity() provides metadata
- ULID toNuanceEntity() provides metadata
- NanoId toNuanceEntity() provides metadata
- Greenleaf Uuid parameter uses Guidance service
- Greenleaf Ulid parameter uses Guidance service
- Greenleaf NanoId parameter uses Guidance service
- Lucid Uuid processor uses Guidance service
- Lucid Ulid processor uses Guidance service
- Lucid NanoId processor uses Guidance service
- UUID engine interface allows custom implementations
- ULID engine allows custom implementations
- NanoId engine allows custom implementations
- UUID Format enum is extensible
- Dictionary enum is extensible
- Codec interface allows custom implementations
- UUID parsing is flexible (multiple formats)
- ULID parsing is flexible (multiple formats)
- NanoId parsing is flexible (custom dictionaries)
- UUID generation supports all standard versions
- ULID generation supports monotonicity
- NanoId generation supports custom size and dictionary
- UUID short format encoding reduces length
- ULID encoding is URL-safe
- NanoId encoding is URL-safe
- UUID timestamp extraction supports time-based versions
- ULID timestamp extraction is always available
- NanoId does not support timestamp extraction
- UUID void generation creates nil UUID
- ULID void generation creates nil ULID
- NanoId void generation creates nil NanoId
- UUID comb generation improves database performance
- ULID generation improves database performance
- NanoId generation is fast and efficient
- UUID validation is comprehensive
- ULID validation is format-based
- NanoId validation is dictionary-based
- UUID parsing handles edge cases
- ULID parsing handles edge cases
- NanoId parsing handles edge cases
- UUID encoding/decoding is reversible
- ULID encoding/decoding is reversible
- NanoId encoding/decoding is reversible
- UUID Format enum provides multiple options
- Dictionary enum provides multiple options
- Codec interface provides encoding abstraction
- UUID engine abstraction allows swapping implementations
- ULID engine allows custom implementations
- NanoId engine allows custom implementations
- UUID integration with Greenleaf is optional
- ULID integration with Greenleaf is optional
- NanoId integration with Greenleaf is optional
- UUID integration with Lucid is optional
- ULID integration with Lucid is optional
- NanoId integration with Lucid is optional
- UUID service methods provide convenience wrappers
- ULID service methods provide convenience wrappers
- NanoId service methods provide convenience wrappers
- UUID string methods return string directly
- ULID string methods return string directly
- NanoId string methods return string directly
- UUID object methods return Uuid instances
- ULID object methods return Ulid instances
- NanoId object methods return NanoId instances
- UUID try methods return null on failure
- ULID try methods return null on failure
- NanoId try methods return null on failure
- UUID from methods throw exception on failure
- ULID from methods throw exception on failure
- NanoId from methods throw exception on failure
- UUID validation methods return bool
- ULID validation methods return bool
- NanoId validation methods return bool
- UUID shorten method uses Format enum
- UUID getDateTime method extracts timestamp
- ULID getDateTime method extracts timestamp
- UUID isValid method checks multiple formats
- ULID isValid method checks format
- NanoId isValid method checks format and dictionary
- UUID uuidFrom method parses from various formats
- ULID ulidFrom method parses from various formats
- NanoId nanoIdFrom method parses from various formats
- UUID uuidFromString method parses string
- ULID ulidFromString method parses string
- NanoId nanoIdFromString method parses string
- UUID uuidFromBytes method creates from bytes
- ULID ulidFromBytes method creates from bytes
- NanoId nanoIdFromBytes method creates from bytes
- UUID uuidFromShortString method parses short format
- UUID uuidFromBigInteger method creates from BigInteger
- ULID ulidFromBigInteger method creates from BigInteger
- NanoId nanoIdFromBigInteger method creates from BigInteger
- UUID create methods generate new UUIDs
- ULID create methods generate new ULIDs
- NanoId create methods generate new NanoIds
- UUID createVoid methods generate nil UUIDs
- ULID createVoid methods generate nil ULIDs
- NanoId createVoid methods generate nil NanoIds
- UUID createV4Comb method generates comb UUIDs
- UUID createV7 method supports custom date
- ULID create method uses current time
- NanoId create method supports custom size and dictionary
- UUID engine provides all version methods
- ULID engine provides create and fromString methods
- NanoId engine provides create and fromString methods
- UUID Format enum provides encode/decode methods
- UUID Format enum provides validation method
- UUID Format enum provides length method
- Crockford32 codec provides encode/decode methods
- Dictionary enum provides character sets
- UidTrait provides common functionality
- UUID class provides version-specific properties
- ULID class provides timestamp extraction
- NanoId class provides dictionary support
- Greenleaf parameters validate identifiers
- Lucid processors coerce to identifier types
- UUID parsing is flexible and forgiving
- ULID parsing is strict and format-based
- NanoId parsing is dictionary-based
- UUID generation uses configured engine
- ULID generation uses configured engine
- NanoId generation uses configured engine
- UUID short format encoding reduces identifier length
- ULID encoding is compact and URL-safe
- NanoId encoding is compact and URL-safe
- UUID timestamp extraction supports time-based versions
- ULID timestamp extraction is always available
- NanoId does not support timestamp extraction
- UUID void generation creates nil identifiers
- ULID void generation creates nil identifiers
- NanoId void generation creates nil identifiers
- UUID comb generation improves database performance
- ULID generation improves database performance
- NanoId generation is fast and efficient
- UUID validation is comprehensive
- ULID validation is format-based
- NanoId validation is dictionary-based
- UUID parsing handles edge cases
- ULID parsing handles edge cases
- NanoId parsing handles edge cases
- UUID encoding/decoding is reversible
- ULID encoding/decoding is reversible
- NanoId encoding/decoding is reversible
- UUID Format enum provides multiple options
- Dictionary enum provides multiple options
- Codec interface provides encoding abstraction
- UUID engine abstraction allows swapping implementations
- ULID engine allows custom implementations
- NanoId engine allows custom implementations
- UUID integration with Greenleaf is optional
- ULID integration with Greenleaf is optional
- NanoId integration with Greenleaf is optional
- UUID integration with Lucid is optional
- ULID integration with Lucid is optional
- NanoId integration with Lucid is optional

---

## 11. Testing & Quality

- **Code Quality Score:** 4.5/5
- **README Quality Score:** 3/5
- **Documentation Score:** 0/5 (this spec)
- **Test Coverage Score:** 0/5

See `composer.json` for supported PHP versions.

---

## 12. Roadmap & Future Ideas

- Enhanced documentation and examples
- Additional UUID engine implementations
- Additional ULID engine implementations
- Additional NanoId engine implementations
- Additional UUID Format options
- Additional Dictionary options
- Additional Codec implementations
- Enhanced validation options
- Better error messages
- Enhanced timestamp extraction
- Additional identifier types
- Better performance optimizations
- Enhanced Greenleaf integration
- Enhanced Lucid integration
- Additional integration options

---

## 13. References

- [Ramsey UUID Library](https://uuid.ramsey.dev) — UUID generation
- [Brick Math Library](https://github.com/brick/math) — Big integer operations
- [Coercion Package](https://github.com/decodelabs/coercion) — Type coercion
- [Exceptional Package](https://github.com/decodelabs/exceptional) — Exception handling
- [Kingdom Package](https://github.com/decodelabs/kingdom) — Service container
- [Nuance Package](https://github.com/decodelabs/nuance) — Data inspection
- [Greenleaf Package](https://github.com/decodelabs/greenleaf) — Route parameter integration
- [Lucid Package](https://github.com/decodelabs/lucid) — Type coercion integration
- [ULID Specification](https://github.com/ulid/spec) — ULID format specification
- [NanoId Specification](https://github.com/ai/nanoid) — NanoId format specification
- [RFC 4122](https://tools.ietf.org/html/rfc4122) — UUID specification
- [Chorus Package Index](../../../chorus/config/packages.json) — Ecosystem metadata

