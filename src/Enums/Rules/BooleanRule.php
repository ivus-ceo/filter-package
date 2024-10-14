<?php

namespace Ivus\Filter\Enums\Rules;

use Ivus\Filter\Interfaces\Enums\{RuleInterface, ImaginableBuilderRuleInterface};

enum BooleanRule: string implements RuleInterface, ImaginableBuilderRuleInterface
{
    case WHERE_TRUE = 'whereTrue';
    case OR_WHERE_TRUE = 'orWhereTrue';
    case WHERE_FALSE = 'whereFalse';
    case OR_WHERE_FALSE = 'orWhereFalse';

    case WHERE_NOT_TRUE = 'whereNotTrue';
    case OR_WHERE_NOT_TRUE = 'orWhereNotTrue';
    case WHERE_NOT_FALSE = 'whereNotFalse';
    case OR_WHERE_NOT_FALSE = 'orWhereNotFalse';
}
