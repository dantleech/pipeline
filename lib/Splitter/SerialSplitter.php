<?php

namespace PhpBench\Framework\Splitter;

use PhpBench\Framework\Step;
use Generator;
use PhpBench\Framework\Pipeline;

class SerialSplitter implements Step
{
    /**
     * @var Step[]
     */
    private $outputs = [];

    public function __construct(array $outputs = [])
    {
        foreach ($outputs as $output) {
            $this->add($output);
        }
    }

    public function generator(Pipeline $pipeline): Generator
    {
        $generators = [];

        foreach ($this->outputs as $output) {
            $generators[] = $output->generator(clone $pipeline);
        }

            foreach ($generators as $index => $generator) {
                foreach ($generator as $data) {
                    yield $data;
                }
            }
    }

    private function add(Step $output)
    {
        $this->outputs[] = $output;
    }
}