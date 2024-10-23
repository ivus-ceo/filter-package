<?php

namespace Ivus\Filter\Enums\Rules\Existables;

use Ivus\Filter\Interfaces\Enums\Rules\{RuleInterface, Existables\ExistableRuleInterface};

enum SearchExistableRule: string implements RuleInterface, ExistableRuleInterface
{
    case WHERE_LIKE = 'whereLike';
    case OR_WHERE_LIKE = 'orWhereLike';
    case WHERE_NOT_LIKE = 'whereNotLike';
    case OR_WHERE_NOT_LIKE = 'orWhereNotLike';
}
