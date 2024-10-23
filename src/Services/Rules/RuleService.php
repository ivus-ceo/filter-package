<?php

namespace Ivus\Filter\Services\Rules;

use Illuminate\Support\Facades\File;
use Ivus\Filter\Interfaces\Enums\Rules\RuleInterface;
use Ivus\Filter\Interfaces\Services\RuleServiceInterface;

class RuleService implements RuleServiceInterface
{
    const EXISTABLES_RULES_NAMESPACE = 'Ivus\\Filter\\Enums\\Rules\\Existables';
    const IMAGINABLES_RULES_NAMESPACE = 'Ivus\\Filter\\Enums\\Rules\\Imaginables';

    /**
     * Get resolved rule by string
     *
     * @param string $string
     * @return RuleInterface|null
     */
    public static function getResolvedRule(string $string): ?RuleInterface
    {
        foreach (static::getRules() as $namespace => $files) {
            foreach ($files as $file) {
                $rule = $namespace . '\\' . str_replace('.php', '', $file->getFilename());
                $enumable = $rule::tryFrom($string);
                if ($enumable instanceof $rule)
                    return $enumable;
            }
        }

        return null;
    }

    /**
     * Get all rules
     *
     * @return array
     */
    public static function getRules(): array
    {
        $directory = __DIR__ . '/../../Enums/Rules';

        return [
            static::EXISTABLES_RULES_NAMESPACE => File::files($directory . '/Existables'),
            static::IMAGINABLES_RULES_NAMESPACE => File::files($directory . '/Imaginables'),
        ];
    }
}
