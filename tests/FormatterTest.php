<?php

namespace MadeByBob\Number\Tests;

use MadeByBob\Number\Formatter\Formatter;
use NumberFormatter;
use PHPUnit\Framework\TestCase;

class FormatterTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if (extension_loaded('intl') === false) {
            $this->markTestSkipped('Intl extension not loaded.');
        }

        Formatter::flush();
    }

    public function testSharedFormattersRespectLocaleAndOptions(): void
    {
        $this->assertEquals('1.234,568', Formatter::format('1234.56789', 0, 3, 'nl_NL'));
        $this->assertEquals('1,234.568', Formatter::format('1234.56789', 0, 3, 'en_US'));

        // Same locale, other options: the cached formatter may not be reused.
        $this->assertEquals('1.234,57', Formatter::format('1234.56789', 0, 2, 'nl_NL'));
        $this->assertEquals('1.234,5679', Formatter::format('1234.56789', 0, 4, 'nl_NL'));
        $this->assertEquals('1.234,568', Formatter::format('1234.56789', 0, 3, 'nl_NL'));

        $this->assertEquals('€ 1.234,57', $this->normalizeSpaces(Formatter::formatMoney('1234.56789', 'EUR', 'nl_NL')));
        $this->assertEquals('US$ 1.234,57', $this->normalizeSpaces(Formatter::formatMoney('1234.56789', 'USD', 'nl_NL')));
    }

    public function testGetProvidesAnInstanceThatIsSafeToModify(): void
    {
        $formatter = Formatter::get(NumberFormatter::DECIMAL, 'nl_NL');
        $formatter->setAttribute(NumberFormatter::MAX_FRACTION_DIGITS, 0);

        $this->assertNotSame($formatter, Formatter::get(NumberFormatter::DECIMAL, 'nl_NL'));
        $this->assertEquals('1.234,568', Formatter::format('1234.56789', 0, 3, 'nl_NL'));
    }

    /**
     * ICU separates the currency symbol with a non breaking space.
     */
    private function normalizeSpaces(string $value): string
    {
        return str_replace("\xC2\xA0", ' ', $value);
    }
}
