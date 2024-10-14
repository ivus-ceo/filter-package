<?php

namespace Ivus\Filter\Interfaces\Services;

use Ivus\Filter\Interfaces\Enums\RuleInterface;

interface RuleServiceInterface
{
    public static function getResolvedRule(string $value): ?RuleInterface;
}
