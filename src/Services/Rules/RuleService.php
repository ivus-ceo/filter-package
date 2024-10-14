<?php

namespace Ivus\Filter\Services\Rules;

use Illuminate\Support\Facades\File;
use Ivus\Filter\Interfaces\Enums\RuleInterface;
use Ivus\Filter\Interfaces\Services\RuleServiceInterface;

class RuleService implements RuleServiceInterface
{
    const RULES_NAMESPACE = 'Ivus\\Filter\\Enums\\Rules';

    /**
     * Get resolved rule by string
     *
     * @param string $value
     * @return RuleInterface|null
     */
    public static function getResolvedRule(string $value): ?RuleInterface
    {
        foreach (File::files(__DIR__ . '/../../Enums/Rules/') as $rule) {
            $rule = static::RULES_NAMESPACE . '\\' . str_replace('.php', '', $rule->getFilename());
            $enumable =$rule::tryFrom($value);
            if ($enumable instanceof $rule)
                return $enumable;
        }

        return null;
    }
}
