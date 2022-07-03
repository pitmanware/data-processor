<?php
declare(strict_types=1);

namespace DP\Entity\Statement;

use Kentron\Support\Assert;

use \Closure;

final class DataTryEntity extends ADataStatementEntity
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
        return Assert::parseOperator($operator);
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
        return Assert::getReadableOperator($operator);
    }

    /**
     * Run overload for bool|int type return
     *
     * @return bool|int|null
     */
    public function run(): bool|int|null
    {
        return parent::run();
    }
}
