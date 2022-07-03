<?php
declare(strict_types=1);

namespace DP\Entity;

use DP\Entity\Condition\DataConditionCollectionEntity;

use Kentron\Support\Type\Type;
use Kentron\Template\Entity\ACoreEntity;

final class DataProcessingEntity extends ACoreEntity
{
    public DataConditionCollectionEntity $dataConditionCollectionEntity;

    public function __construct()
    {
        DataStore::reset();
    }

    /**
     * Helpers
     */

    /**
     * Store any dynamic variables needed for this data
     *
     * @param object[]|object $storeItems
     * @param IDataEntity $dataEntity
     *
     * @return DataValueEntity[]
     */
    public function buildStore(array|object $storeItems, IDataEntity $dataEntity): array
    {
        $dataValueEntities = [];
        $storeItems = is_object($storeItems) ? [$storeItems] : $storeItems;

        foreach ($storeItems as $storeItem) {
            $dataValueEntities[] = $this->buildStoreItem(
                Type::getProperty($storeItem, "key"),
                Type::getProperty($storeItem, "value"),
                $dataEntity
            );
        }

        return $dataValueEntities;
    }

    /**
     * Store any dynamic variables needed for this data
     *
     * @param string $key
     * @param array|object $propertyData
     * @param IDataEntity $dataEntity
     *
     * @return DataValueEntity
     */
    public function buildStoreItem(string $key, array|object $propertyData, IDataEntity $dataEntity): DataValueEntity
    {
        $dataValueEntity = new DataValueEntity();

        $dataValueEntity->hydrate($propertyData);
        $dataValueEntity->putData($dataEntity);

        DataStore::set($key, $dataValueEntity);

        return $dataValueEntity;
    }

    /**
     * Build the conditional statements that determine the pass status of the data
     *
     * @param array $conditions
     * @param string $operator
     * @param IDataEntity $dataEntity
     *
     * @return void
     */
    public function buildConditions(array $conditions, string $operator, IDataEntity $dataEntity): void
    {
        $this->dataConditionCollectionEntity = new DataConditionCollectionEntity();

        $this->dataConditionCollectionEntity->hydrateCollection($conditions);
        $this->dataConditionCollectionEntity->setOperator($operator);
        $this->dataConditionCollectionEntity->putData($dataEntity);
    }
}
