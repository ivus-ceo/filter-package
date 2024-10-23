<?php

namespace Ivus\Filter\Interfaces\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Ivus\Filter\Enums\Operators\Operator;
use Ivus\Filter\Interfaces\Enums\Rules\RuleInterface;

interface FilterServiceInterface
{
    public function filter(string $query = null, bool $isPaginated = true, bool $isBuilder = false): LengthAwarePaginator | Collection | Builder;
    public static function getMethodByRule(RuleInterface $rule, string $customInvokeMethod): string;
    public static function getOperatorByRule(RuleInterface $rule): ?Operator;
    public static function getValueByRule(RuleInterface $rule, ?string $value): array | string | int | bool | null;
}
