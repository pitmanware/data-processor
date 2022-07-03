<?php
declare(strict_types=1);

namespace DP\Entity\Statement;

use DP\Entity\DataValueEntity;
use DP\Entity\IDataEntity;

use Kentron\Template\Entity\ACoreEntity;

use \Closure;

abstract class ADataStatementEntity extends ACoreEntity
{
    protected array $propertyMap = [
        "operand_l" => [
            "prop" => "operandL",
            "set" => "setOperandL"
        ],
        "operator" => [
            "prop" => "operator",
            "set" => "setOperator"
        ],
        "operand_r" => [
            "prop" => "operandR",
            "set" => "setOperandR"
        ]
    ];

    public string $operator;
    public DataValueEntity $operandL;
    public Closure $operation;
    public DataValueEntity $operandR;

    public function __construct()
    {
        $this->operandL = new DataValueEntity();
        $this->operandR = new DataValueEntity();
    }

    /**
     * Setters
     */

    public function setOperandL(array $operandL): void
    {
        $this->operandL->hydrate($operandL);
    }

    public function setOperator(string $operator): void
    {
        $this->operator = $operator;
        $this->operation = $this->parseOperator($operator);
    }

    public function setOperandR(array $operandR): void
    {
        $this->operandR->hydrate($operandR);
    }

    /**
     * Helpers
     */

    public function putData(IDataEntity $dataEntity): void
    {
        $this->operandL->putData($dataEntity);
        $this->operandR->putData($dataEntity);
    }

    public function getReadable(): string
    {
        return
            $this->operandL->getKey() . " " .
            $this->getReadableOperator($this->operator) . " " .
            $this->operandR->getKey();
    }

    protected function run(): mixed
    {
        $operandL = $this->operandL->getValue();
        $operandR = $this->operandR->getValue();

        if (
            ((is_string($operandL)) && empty($operandL)) ||
            ((is_string($operandR)) && empty($operandR)) ||
            is_null($operandL) || is_null($operandR)
        ) {
            return null;
        }

        return ($this->operation)($this->operandL->getValue(), $this->operandR->getValue());
    }

    abstract protected function parseOperator(string $operator): Closure;
    abstract protected function getReadableOperator(string $operator): string;
}
