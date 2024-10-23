<?php

namespace Ivus\Filter\Interfaces\Services;

use Ivus\Filter\Interfaces\Enums\Rules\RuleInterface;

interface RuleServiceInterface
{
    public static function getResolvedRule(string $string): ?RuleInterface;
}
