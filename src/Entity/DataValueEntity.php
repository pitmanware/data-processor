<?php
declare(strict_types=1);

namespace DP\Entity;

use DP\Entity\Statement\DataEquationEntity;

use Kentron\Template\Entity\ACoreEntity;

use \Error;

final class DataValueEntity extends ACoreEntity
{
    protected array $propertyMap = [
        "property" => [
            "set" => "setProperty"
        ],
        "variable" => [
            "set" => "setVariable"
        ],
        "equation" => [
            "set" => "setEquation"
        ],
        "raw" => [
            "set" => "setRaw"
        ]
    ];

    private DataScalarEntity $property;
    private self $storeDataValueEntity;
    private DataEquationEntity $equation;
    private string|int|float|bool|null $raw = null;

    private array $propertyChain;

    /**
     * Getters
     */

    public function getValue(): string|int|float|bool|null
    {
        if (isset($this->property)) {
            return $this->property->value;
        }
        if (isset($this->storeDataValueEntity)) {
            return $this->storeDataValueEntity->getValue();
        }
        if (isset($this->equation)) {
            return $this->equation->run();
        }
        return $this->raw;
    }

    public function getProperty(): DataScalarEntity
    {
        if (isset($this->property)) {
            return $this->property;
        }
        if (isset($this->storeDataValueEntity)) {
            return $this->storeDataValueEntity->getProperty();
        }
        if (isset($this->equation)) {
            return $this->equation->operandL->getProperty();
        }
        throw new Error("Could not retrieve rule value property");
    }

    public function getKey(): string
    {
        if (isset($this->property)) {
            return $this->property->key;
        }
        if (isset($this->storeDataValueEntity)) {
            return $this->storeDataValueEntity->getKey();
        }
        if (isset($this->equation)) {
            return "(" . $this->equation->getReadable() . ")";
        }

        if (is_bool($this->raw)) {
            return $this->raw ? "true" : "false";
        }
        else if (is_null($this->raw)) {
            return "NULL";
        }
        return (string)$this->raw;
    }

    /**
     * Setters
     */

    public function setProperty(array $propertyChain): void
    {
        $this->propertyChain = $propertyChain;
    }

    public function setVariable(string $variable): void
    {
        $this->storeDataValueEntity = DataStore::get($variable);
    }

    public function setEquation(array $equation): void
    {
        $this->equation = new DataEquationEntity();
        $this->equation->hydrate($equation);
    }

    public function setRaw(string|int|float|bool $raw): void
    {
        $this->raw = $raw;
    }

    /**
     * Helpers
     */

    /**
     * Inject the data needed for property lookups
     *
     * @param IDataEntity $dataEntity
     *
     * @return void
     */
    public function putData(IDataEntity $dataEntity): void
    {
        // If propertyChain have been set, it means we need to retrieve a property from the data object
        if (isset($this->propertyChain)) {
            $property = $dataEntity->getPropertyByChain($this->propertyChain);
            if (!is_null($property)) {
                $this->property = $property;
                $this->property->used = true;
            }
        }

        // If there is an equation (store variable only), inject the data deeper for any further property lookups
        if (isset($this->equation)) {
            $this->equation->putData($dataEntity);
        }
    }
}
