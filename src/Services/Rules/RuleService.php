<?php

namespace Ivus\Filter\Services\Rules;

use Ivus\Filter\Enums\Rules\{ArrayableRule, CustomableRule, NullableRule, StringableRule};

class RuleService
{
    /**
     * Get resolved rule by string
     *
     * @param string $value
     * @return ArrayableRule|CustomableRule|NullableRule|StringableRule|null
     */
    public static function getResolvedRule(string $value): ArrayableRule | CustomableRule | NullableRule | StringableRule | null
    {
        foreach (config('filters.rules', []) as $rule) {
            $enumable = $rule::tryFrom($value);
            $isResolved = $enumable instanceof $rule;
            if ($isResolved) return $enumable;
        }

        return null;
    }
}
