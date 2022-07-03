<?php
declare(strict_types=1);

namespace DP\Entity\Condition;

use DP\Struct\Status;
use DP\Entity\Statement\DataTryEntity;

use Kentron\Support\Assert;
use Kentron\Template\Entity\ACoreEntity;

final class DataConditionEntity extends ACoreEntity
{
    protected array $propertyMap = [
        "try" => [
            "get_class" => "getTryEntity",
            "get" => "getTry"
        ],
        "expect" => "expect",
        "persist" => "persist"
    ];

    public bool $expect;
    public bool $persist;
    public bool|null $result = null;

    public DataTryEntity $tryEntity;

    public function __construct()
    {
        $this->tryEntity = new DataTryEntity();
    }

    /**
     * Class Getters
     */

    public function getTryEntity(): DataTryEntity
    {
        return $this->tryEntity;
    }

    /**
     * Property Getters
     */

    public function getTry(): array
    {
        return $this->tryEntity->normalise();
    }

    /**
     * Helpers
     */

    /**
     * Run the condition and compare it exactly to the expected result
     *
     * @return bool|null
     */
    public function run(): ?bool
    {
        $tryResult = $this->tryEntity->run();

        if (is_null($tryResult)) {
            return null;
        }

        $this->result = Assert::same($tryResult, $this->expect);
        return $this->result;
    }

    public function getReadable(): string
    {
        return $this->tryEntity->getReadable() . " then " . Status::determineStatusText($this->expect);
    }
}
