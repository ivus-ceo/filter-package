<?php

namespace Ivus\Filter\Enums\Rules\Imaginables;

use Ivus\Filter\Interfaces\Enums\Rules\{RuleInterface, Imaginables\ImaginableRuleInterface};

enum BooleanImaginableRule: string implements RuleInterface, ImaginableRuleInterface
{
    case WHERE_EQUAL_TRUE = 'whereEqualTrue';
    case OR_WHERE_EQUAL_TRUE = 'orWhereEqualTrue';
    case WHERE_EQUAL_FALSE = 'whereEqualFalse';
    case OR_WHERE_EQUAL_FALSE = 'orWhereEqualFalse';

    case WHERE_NOT_EQUAL_TRUE = 'whereNotEqualTrue';
    case OR_WHERE_NOT_EQUAL_TRUE = 'orWhereNotEqualTrue';
    case WHERE_NOT_EQUAL_FALSE = 'whereNotEqualFalse';
    case OR_WHERE_NOT_EQUAL_FALSE = 'orWhereNotEqualFalse';
}
