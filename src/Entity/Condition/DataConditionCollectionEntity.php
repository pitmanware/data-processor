<?php
declare(strict_types=1);

namespace DP\Entity\Condition;

use DP\Entity\IDataEntity;

use Kentron\Support\Evaluate;
use Kentron\Template\Entity\ACoreCollectionEntity;

use \Closure;

final class DataConditionCollectionEntity extends ACoreCollectionEntity
{
    public bool|null $passStatus = null;

    private string $operator;
    private Closure $operation;

    public function __construct()
    {
        parent::__construct(DataConditionEntity::class);
    }

    public function setOperator(string $operator): void
    {
        $this->operator = strtolower($operator);
        $this->operation = Evaluate::parseOperator($operator);
    }

    /**
     * Inject property data
     *
     * @param IDataEntity $dataEntity
     *
     * @return void
     */
    public function putData(IDataEntity $dataEntity): void
    {
        /** @var DataConditionEntity */
        foreach ($this->iterateEntities() as $ruleConditionEntity) {
            $ruleConditionEntity->getTryEntity()->putData($dataEntity);
        }
    }

    /**
     * Run the conditions and return the output of the operation closure
     *
     * @return DataConditionEntity[]
     */
    final public function run(): iterable
    {
        /** @var bool[] */
        $conditions = [];

        /** @var DataConditionEntity $ruleConditionEntity */
        foreach ($this->iterateEntities() as $ruleConditionEntity) {
            $conditionResult = $ruleConditionEntity->run();

            if (is_bool($conditionResult)) {
                // If we want to save the outcome of this condition to the data property
                if ($ruleConditionEntity->persist) {
                    $operandL = $ruleConditionEntity->tryEntity->operandL;

                    // If the property is null, then there is no MatchDataScalar to update
                    if (!is_null($operandL->getValue())) {
                        $operandLProperty = $operandL->getProperty();

                        // If the persisted outcome has not been set, set it
                        if (is_null($operandLProperty->pass)) {
                            $operandLProperty->resolve($conditionResult);
                        }
                        // Otherwise if the expected outcome has already been found
                        //   and the result was not as expected
                        //   and the operator expects all to pass
                        else if ($operandLProperty->pass && !$conditionResult && $this->operator === Evaluate::OP_AND) {
                            // Override the pass status to fail
                            $operandLProperty->resolve(false);
                        }
                    }
                }

                $conditions[] = $conditionResult;
            }

            // Return the condition entity to be audited
            yield $ruleConditionEntity;
        }

        $countConditions = count($conditions);

        $this->passStatus = match (true) {
            $countConditions == 0 => null,
            $countConditions == 1 => $conditions[0],
            $countConditions >= 2 => ($this->operation)(...$conditions),
        };
    }

    public function getReadable(): string
    {
        $readable = "";

        /** @var DataConditionEntity */
        foreach ($this->iterateEntities() as $ruleConditionEntity) {
            $readable .= empty($readable) ? "" : "\n{$this->operator} ";
            $readable .= $ruleConditionEntity->getReadable();
        }

        return $readable;
    }

    /**
     * Helpers
     */

    public function newEntity(): DataConditionEntity
    {
        return parent::newEntity();
    }
}
