<?php

namespace PhpBench\Pipeline\Tests\Unit;

use PHPUnit\Framework\TestCase;
use PhpBench\Pipeline\Step;
use PhpBench\Pipeline\Pipeline;
use PhpBench\Pipeline\Exception\EmptyPipeline;

class PipelineTest extends TestCase
{
    const TEST_STRING = 'Hello World';

    /**
     * @var Step|ObjectProphecy
     */
    private $step;

    public function setUp()
    {
        $this->step = $this->prophesize(Step::class);
    }

    public function testPop()
    {
        $pipeline = new Pipeline([
            $this->step->reveal()
        ]);

        $this->step->generator($pipeline)->willReturn($this->generator());

        $generator = $pipeline->pop();

        $this->assertEquals(self::TEST_STRING, $generator->current());
    }

    public function testPopOnEmpty()
    {
        $this->expectException(EmptyPipeline::class);
        $pipeline = new Pipeline([]);
        $pipeline->pop();
    }

    public function testRun()
    {
        $pipeline = new Pipeline([
            $this->step->reveal()
        ]);
        $this->step->generator($pipeline)->willReturn($this->generator());

        $results = $pipeline->run();
        $this->assertEquals([ self::TEST_STRING ], $results);
    }

    private function generator()
    {
        yield self::TEST_STRING;
    }
}
