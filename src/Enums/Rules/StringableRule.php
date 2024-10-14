<?php

namespace Ivus\Filter\Enums\Rules;

use Ivus\Filter\Interfaces\Enums\{RuleInterface, ExistingBuilderRuleInterface};

enum StringableRule: string implements RuleInterface, ExistingBuilderRuleInterface
{
    case WHERE = 'where';
    case OR_WHERE = 'orWhere';
    case WHERE_NOT = 'whereNot';
    case OR_WHERE_NOT = 'orWhereNot';

    case WHERE_LIKE = 'whereLike';
    case OR_WHERE_LIKE = 'orWhereLike';
    case WHERE_NOT_LIKE = 'whereNotLike';
    case OR_WHERE_NOT_LIKE = 'orWhereNotLike';

    case WHERE_DATE = 'whereDate';
    case OR_WHERE_DATE = 'orWhereDate';

    case WHERE_MONTH = 'whereMonth';
    case OR_WHERE_MONTH = 'orWhereMonth';

    case WHERE_DAY = 'whereDay';
    case OR_WHERE_DAY = 'orWhereDay';

    case WHERE_YEAR = 'whereYear';
    case OR_WHERE_YEAR = 'orWhereYear';

    case WHERE_TIME = 'whereTime';
    case OR_WHERE_TIME = 'orWhereTime';
}
