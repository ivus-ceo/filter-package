<?php

namespace Ivus\Filter\Enums;

enum Rules: string
{
    /*
    | Strings or Integers
    */
    case WHERE = 'where';
    case OR_WHERE = 'orWhere';
    case WHERE_NOT = 'whereNot';
    case OR_WHERE_NOT = 'orWhereNot';
    //
    case WHERE_LIKE = 'whereLike';
    case OR_WHERE_LIKE = 'orWhereLike';
    case WHERE_NOT_LIKE = 'whereNotLike';
    case OR_WHERE_NOT_LIKE = 'orWhereNotLike';
    //
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

    /*
    | Arrays
    */
    case WHERE_IN = 'whereIn';
    case OR_WHERE_IN = 'orWhereIn';
    case WHERE_NOT_IN = 'whereNotIn';
    case OR_WHERE_NOT_IN = 'orWhereNotIn';
    //
    case WHERE_BETWEEN = 'whereBetween';
    case OR_WHERE_BETWEEN = 'orWhereBetween';
    case WHERE_NOT_BETWEEN = 'whereNotBetween';
    case OR_WHERE_NOT_BETWEEN = 'orWhereNotBetween';
    //
    case WHERE_BETWEEN_COLUMNS = 'whereBetweenColumns';
    case OR_WHERE_BETWEEN_COLUMNS = 'orWhereBetweenColumns';
    case WHERE_NOT_BETWEEN_COLUMNS = 'whereNotBetweenColumns';
    case OR_WHERE_NOT_BETWEEN_COLUMNS = 'orWhereNotBetweenColumns';

    /*
    | Nullables
    */
    case WHERE_NULL = 'whereNull';
    case OR_WHERE_NULL = 'orWhereNull';
    case WHERE_NOT_NULL = 'whereNotNull';
    case OR_WHERE_NOT_NULL = 'orWhereNotNull';

    /*
    | Customs
    */
    case CUSTOM = 'custom';
}
