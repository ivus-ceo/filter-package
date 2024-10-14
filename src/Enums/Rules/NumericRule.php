<?php

namespace Ivus\Filter\Enums\Rules;

use Ivus\Filter\Interfaces\Enums\{RuleInterface, ImaginableBuilderRuleInterface};

enum NumericRule: string implements RuleInterface, ImaginableBuilderRuleInterface
{
    case WHERE_EQUAL = 'whereEqual';
    case OR_WHERE_EQUAL = 'orWhereEqual';
    case WHERE_NOT_EQUAL = 'whereNotEqual';
    case OR_WHERE_NOT_EQUAL = 'orWhereNotEqual';

    case WHERE_GREATER_THAN = 'whereGreaterThan';
    case OR_WHERE_GREATER_THAN = 'orWhereGreaterThan';

    case WHERE_LESS_THAN = 'whereLessThan';
    case OR_WHERE_LESS_THAN = 'orWhereLessThan';

    case WHERE_GREATER_THAN_OR_EQUAL = 'whereGreaterThanOrEqual';
    case OR_WHERE_GREATER_THAN_OR_EQUAL = 'orWhereGreaterThanOrEqual';

    case WHERE_LESS_THAN_OR_EQUAL = 'whereLessThanOrEqual';
    case OR_WHERE_LESS_THAN_OR_EQUAL = 'orWhereLessThanOrEqual';
}
