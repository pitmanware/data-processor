<?php
declare(strict_types=1);

namespace DP\Struct;

final class Status
{
    public const PASS = "PASS";
    public const FAIL = "FAIL";
    public const NA = "N/A";

    public static function determineStatusText(?bool $status): string
    {
        return match ($status) {
            true => Status::PASS,
            false => Status::FAIL,
            null => Status::NA
        };
    }
}
