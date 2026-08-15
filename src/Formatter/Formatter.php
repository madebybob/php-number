<?php

declare(strict_types=1);

namespace MadeByBob\Number\Formatter;

use Locale;
use NumberFormatter;
use RuntimeException;

abstract class Formatter
{
    /**
     * Maximum amount of formatters kept in memory.
     */
    private const CACHE_SIZE = 32;

    /**
     * Formatters, keyed by type, locale and options.
     *
     * @var array<string, NumberFormatter>
     */
    private static array $formatters = [];

    /**
     * Shorthand for formatting decimals.
     */
    public static function format(string $value, ?int $minFractionDigits = null, ?int $maxFractionDigits = null, ?string $locale = null): string
    {
        $options = [
            NumberFormatter::MIN_FRACTION_DIGITS => $minFractionDigits,
            NumberFormatter::MAX_FRACTION_DIGITS => $maxFractionDigits,
        ];

        return self::cached(NumberFormatter::DECIMAL, $locale, $options)->format((float) $value);
    }

    /**
     * Shorthand for formatting currency amounts.
     */
    public static function formatMoney(string $value, string $isoCode, ?string $locale = null): string
    {
        return self::cached(NumberFormatter::CURRENCY, $locale)->formatCurrency((float) $value, $isoCode);
    }

    /**
     * Drops the formatters kept in memory.
     */
    public static function flush(): void
    {
        self::$formatters = [];
    }

    /**
     * Provides a shared NumberFormatter instance for the given configuration.
     *
     * Constructing a NumberFormatter is roughly twenty times as expensive as
     * formatting a value with it, so instances are reused. They are only handed
     * out internally, which guarantees the attributes of a cached instance
     * always match the key it is cached under.
     *
     * @param array<int, int|null> $options
     */
    private static function cached(int $type, ?string $locale = null, array $options = []): NumberFormatter
    {
        self::assertIntlIsLoaded();

        $locale = $locale ?? Locale::getDefault();

        $key = $type . '|' . $locale;
        foreach ($options as $option => $setting) {
            $key .= '|' . $option . ':' . ($setting ?? '');
        }

        if (isset(self::$formatters[$key])) {
            return self::$formatters[$key];
        }

        if (count(self::$formatters) >= self::CACHE_SIZE) {
            self::$formatters = [];
        }

        return self::$formatters[$key] = self::get($type, $locale, $options);
    }

    /**
     * Provides an NumberFormatter instance of the PHP intl extension (and some syntax sugar).
     */
    public static function get(int $type, ?string $locale = null, array $options = []): NumberFormatter
    {
        self::assertIntlIsLoaded();

        if ($locale === null) {
            $locale = Locale::getDefault();
        }

        $formatter = new NumberFormatter($locale, $type);
        foreach ($options as $key => $value) {
            if ($value === null) {
                continue;
            }

            $formatter->setAttribute($key, $value);
        }

        return $formatter;
    }

    private static function assertIntlIsLoaded(): void
    {
        if (extension_loaded('intl') === false) {
            throw new RuntimeException('PHP\'s intl extension is required to use the Formatter');
        }
    }
}
