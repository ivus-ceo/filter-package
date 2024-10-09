<?php

namespace Ivus\Filter\Enums\Rules;

enum NullableRule: string
{
    case WHERE_NULL = 'whereNull';
    case OR_WHERE_NULL = 'orWhereNull';
    case WHERE_NOT_NULL = 'whereNotNull';
    case OR_WHERE_NOT_NULL = 'orWhereNotNull';
}
