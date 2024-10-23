<?php

namespace Ivus\Filter\Enums\Rules\Existables;

use Ivus\Filter\Interfaces\Enums\Rules\{RuleInterface, Existables\ExistableRuleInterface};

enum NullExistableRule: string implements RuleInterface, ExistableRuleInterface
{
    case WHERE_NULL = 'whereNull';
    case OR_WHERE_NULL = 'orWhereNull';
    case WHERE_NOT_NULL = 'whereNotNull';
    case OR_WHERE_NOT_NULL = 'orWhereNotNull';
}
