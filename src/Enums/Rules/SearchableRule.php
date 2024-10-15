<?php

namespace Ivus\Filter\Enums\Rules;

use Ivus\Filter\Interfaces\Enums\{RuleInterface, ExistingBuilderRuleInterface};

enum SearchableRule: string implements RuleInterface, ExistingBuilderRuleInterface
{
    case WHERE_LIKE = 'whereLike';
    case OR_WHERE_LIKE = 'orWhereLike';
    case WHERE_NOT_LIKE = 'whereNotLike';
    case OR_WHERE_NOT_LIKE = 'orWhereNotLike';
}
