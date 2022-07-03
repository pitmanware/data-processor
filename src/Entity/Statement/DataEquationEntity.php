<?php
declare(strict_types=1);

namespace DP\Entity\Statement;

use Kentron\Support\Evaluate;

use \Closure;

final class DataEquationEntity extends ADataStatementEntity
{
    /**
     * Get a callable based on the operator
     *
     * @param string $operator
     *
     * @return \Closure
     */
    protected function parseOperator(string $operator): Closure
    {
        return Evaluate::parseOperator($operator);
    }

    /**
     * Get Readable operator
     *
     * @param string $operator
     *
     * @return string
     */
    protected function getReadableOperator(string $operator): string
    {
        return Evaluate::getReadableOperator($operator);
    }

    /**
     * Run overload for expected numeric type return
     *
     * @return int|float|null
     */
    public function run(): int|float|null
    {
        return parent::run();
    }
}
