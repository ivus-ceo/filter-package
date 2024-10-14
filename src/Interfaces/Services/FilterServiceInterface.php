<?php

namespace Ivus\Filter\Interfaces\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Ivus\Filter\Enums\Operators\Operator;
use Ivus\Filter\Interfaces\Enums\RuleInterface;

interface FilterServiceInterface
{
    public function filter(bool $isPaginated = true): LengthAwarePaginator | Collection;
    public static function getMethodByRule(RuleInterface $rule): string;
    public static function getOperatorByRule(RuleInterface $rule): ?Operator;
    public static function getValueByRule(RuleInterface $rule, ?string $value): array | string | int | bool | null;
}
