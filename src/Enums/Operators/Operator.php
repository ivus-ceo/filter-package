<?php

namespace Ivus\Filter\Enums\Operators;

use Ivus\Filter\Interfaces\Enums\OperatorInterface;

enum Operator: string implements OperatorInterface
{
    case EQUALS = '=';
    case NOT_EQUALS = '!=';
    case GREATER_THAN = '>';
    case GREATER_THAN_OR_EQUALS = '>=';
    case LESS_THAN = '<';
    case LESS_THAN_OR_EQUALS = '<=';
    case LIKE = 'like';
    case NOT_LIKE = 'not like';
}
