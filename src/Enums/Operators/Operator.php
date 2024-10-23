<?php

namespace Ivus\Filter\Enums\Operators;

use Ivus\Filter\Interfaces\Enums\Operators\OperatorInterface;

enum Operator: string implements OperatorInterface
{
    case EQUAL = '=';
    case NOT_EQUAL = '!=';
    case GREATER_THAN = '>';
    case GREATER_THAN_OR_EQUAL = '>=';
    case LESS_THAN = '<';
    case LESS_THAN_OR_EQUAL = '<=';
    case LIKE = 'like';
    case NOT_LIKE = 'not like';
}
