<?php

namespace MadeByBob\Number\Tests;

use MadeByBob\Number\Number;
use PHPUnit\Framework\TestCase;

class MemoryTest extends TestCase
{
    public function testParentIsAvailableAsLongAsItIsReferenced(): void
    {
        $five = new Number(5);
        $seven = $five->add(2);
        $fifteen = $seven->add(8);

        $this->assertSame($five, $seven->parent());
        $this->assertSame($seven, $fifteen->parent());
        $this->assertSame($five, $fifteen->parent()->parent());
    }

    public function testParentIsReleasedWhenItIsNoLongerReferenced(): void
    {
        $five = new Number(5);
        $seven = $five->add(2);
        $fifteen = $seven->add(8);

        unset($seven);

        $this->assertNull($fifteen->parent());
        $this->assertSame($five, $five->add(0)->parent());
    }

    public function testUnreferencedIntermediateResultsOfAChainAreReleased(): void
    {
        $result = (new Number(5))->add(2)->add(8);

        $this->assertEquals('15.0000', $result->toString());
        $this->assertNull($result->parent());
    }

    public function testIntermediateResultsOfAChainAreNotRetained(): void
    {
        $before = memory_get_usage();

        $total = new Number('0');
        for ($i = 0; $i < 20000; $i++) {
            $total = $total->add('1.5');
        }

        $retained = memory_get_usage() - $before;

        $this->assertEquals('30000.0000', $total->toString());

        // Every intermediate result used to be kept alive by its descendants,
        // which grew this loop by roughly 3 MB. Only the last result is alive now.
        $this->assertLessThan(1024 * 1024, $retained, sprintf('Chain retained %d bytes', $retained));
    }
}
