<?php

namespace Ivus\Filter\Enums\Rules\Imaginables;

use Ivus\Filter\Interfaces\Enums\Rules\{RuleInterface, Imaginables\ImaginableRuleInterface};

enum RelationImaginableRule: string implements RuleInterface, ImaginableRuleInterface
{
    case WHERE_HAS_EQUAL = 'whereHasEqual';
    case OR_WHERE_HAS_EQUAL = 'orWhereHasEqual';
    case WHERE_HAS_NOT_EQUAL = 'whereHasNotEqual';
    case OR_WHERE_HAS_NOT_EQUAL = 'orWhereHasNotEqual';

    case WHERE_HAS_GREATER_THAN = 'whereHasGreaterThan';
    case OR_WHERE_HAS_GREATER_THAN = 'orWhereHasGreaterThan';

    case WHERE_HAS_LESS_THAN = 'whereHasLessThan';
    case OR_WHERE_HAS_LESS_THAN = 'orWhereHasLessThan';

    case WHERE_HAS_GREATER_THAN_OR_EQUAL = 'whereHasGreaterThanOrEqual';
    case OR_WHERE_HAS_GREATER_THAN_OR_EQUAL = 'orWhereHasGreaterThanOrEqual';

    case WHERE_HAS_LESS_THAN_OR_EQUAL = 'whereHasLessThanOrEqual';
    case OR_WHERE_HAS_LESS_THAN_OR_EQUAL = 'orWhereHasLessThanOrEqual';
}
