<?php

declare(strict_types=1);

namespace Madebybob\Number\Formatter;

use Locale;
use NumberFormatter;

class Formatter
{
    /**
     * Shorthand for formatting decimals.
     */
    public static function format(string $value, ?int $minFractionDigits = null, ?int $maxFractionDigits = null, ?string $locale = null): string
    {
        return self::get(NumberFormatter::DECIMAL, $minFractionDigits, $maxFractionDigits, $locale)->format($value);
    }

    /**
     * Shorthand for formatting currency amounts.
     */
    public static function formatCurrency(string $value, string $isoCode, ?string $locale = null): string
    {
        return self::get(NumberFormatter::CURRENCY, $locale)->formatCurrency((float) $value, $isoCode);
    }

    /**
     * Provides an NumberFormatter instance of the PHP intl extension.
     */
    public static function get(int $type, ?int $minFractionDigits = null, ?int $maxFractionDigits = null, ?string $locale = null): NumberFormatter
    {
        if ($locale === null) {
            $locale = Locale::getDefault();
        }

        $formatter = new NumberFormatter($locale, $type);
        if ($minFractionDigits !== null) {
            $formatter->setAttribute(NumberFormatter::MIN_FRACTION_DIGITS, $minFractionDigits);
        }
        if ($maxFractionDigits !== null) {
            $formatter->setAttribute(NumberFormatter::MAX_FRACTION_DIGITS, $maxFractionDigits);
        }

        return $formatter;
    }
}
