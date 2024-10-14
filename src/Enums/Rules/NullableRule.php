<?php

namespace Ivus\Filter\Enums\Rules;

use Ivus\Filter\Interfaces\Enums\{RuleInterface, ExistingBuilderRuleInterface};

enum NullableRule: string implements RuleInterface, ExistingBuilderRuleInterface
{
    case WHERE_NULL = 'whereNull';
    case OR_WHERE_NULL = 'orWhereNull';
    case WHERE_NOT_NULL = 'whereNotNull';
    case OR_WHERE_NOT_NULL = 'orWhereNotNull';
}
