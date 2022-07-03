<?php
declare(strict_types=1);

namespace DP\Entity;

use \Error;

final class DataStore
{
    /**
     * @var DataValueEntity[]
     */
    private static array $store = [];

    /**
     * Clear any saved variables
     *
     * @return void
     */
    public static function reset(): void
    {
        self::$store = [];
    }

    /**
     * Return a saved variable by key
     *
     * @param string $key
     *
     * @return DataValueEntity
     */
    public static function get(string $key): DataValueEntity
    {
        if (!isset(self::$store[$key])) {
            throw new Error("Store item '{$key}' is not set");
        }

        return self::$store[$key];
    }

    /**
     * Save a variable statically
     *
     * @param string $key
     * @param DataValueEntity $value
     *
     * @return void
     */
    public static function set(string $key, DataValueEntity $value): void
    {
        if (isset(self::$store[$key])) {
            throw new Error("Store item '{$key}' has already been set");
        }
        self::$store[$key] = $value;
    }
}
