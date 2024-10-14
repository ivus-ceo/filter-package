<?php

namespace Ivus\Filter\Enums\Rules;

use Ivus\Filter\Interfaces\Enums\{RuleInterface, ExistingBuilderRuleInterface};

enum ArrayableRule: string implements RuleInterface, ExistingBuilderRuleInterface
{
    case WHERE_IN = 'whereIn';
    case OR_WHERE_IN = 'orWhereIn';
    case WHERE_NOT_IN = 'whereNotIn';
    case OR_WHERE_NOT_IN = 'orWhereNotIn';

    case WHERE_BETWEEN = 'whereBetween';
    case OR_WHERE_BETWEEN = 'orWhereBetween';
    case WHERE_NOT_BETWEEN = 'whereNotBetween';
    case OR_WHERE_NOT_BETWEEN = 'orWhereNotBetween';

    case WHERE_BETWEEN_COLUMNS = 'whereBetweenColumns';
    case OR_WHERE_BETWEEN_COLUMNS = 'orWhereBetweenColumns';
    case WHERE_NOT_BETWEEN_COLUMNS = 'whereNotBetweenColumns';
    case OR_WHERE_NOT_BETWEEN_COLUMNS = 'orWhereNotBetweenColumns';
}
