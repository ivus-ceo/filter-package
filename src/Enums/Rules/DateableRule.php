<?php

namespace Ivus\Filter\Enums\Rules;

use Ivus\Filter\Interfaces\Enums\{RuleInterface, ImaginableBuilderRuleInterface};

enum DateableRule: string implements RuleInterface, ImaginableBuilderRuleInterface
{
    case WHERE_DATE_EQUAL = 'whereDateEqual';
    case OR_WHERE_DATE_EQUAL = 'orWhereDateEqual';
    case WHERE_DATE_NOT_EQUAL = 'whereDateNotEqual';
    case OR_WHERE_DATE_NOT_EQUAL = 'orWhereDateNotEqual';

    case WHERE_DATE_GREATER_THAN = 'whereDateGreaterThan';
    case OR_WHERE_DATE_GREATER_THAN = 'orWhereDateGreaterThan';

    case WHERE_DATE_LESS_THAN = 'whereDateLessThan';
    case OR_WHERE_DATE_LESS_THAN = 'orWhereDateLessThan';

    case WHERE_DATE_GREATER_THAN_OR_EQUAL = 'whereDateGreaterThanOrEqual';
    case OR_WHERE_DATE_GREATER_THAN_OR_EQUAL = 'orWhereDateGreaterThanOrEqual';

    case WHERE_DATE_LESS_THAN_OR_EQUAL = 'whereDateLessThanOrEqual';
    case OR_WHERE_DATE_LESS_THAN_OR_EQUAL = 'orWhereDateLessThanOrEqual';



    case WHERE_DAY_EQUAL = 'whereDayEqual';
    case OR_WHERE_DAY_EQUAL = 'orWhereDayEqual';
    case WHERE_DAY_NOT_EQUAL = 'whereDayNotEqual';
    case OR_WHERE_DAY_NOT_EQUAL = 'orWhereDayNotEqual';

    case WHERE_DAY_GREATER_THAN = 'whereDayGreaterThan';
    case OR_WHERE_DAY_GREATER_THAN = 'orWhereDayGreaterThan';

    case WHERE_DAY_LESS_THAN = 'whereDayLessThan';
    case OR_WHERE_DAY_LESS_THAN = 'orWhereDayLessThan';

    case WHERE_DAY_GREATER_THAN_OR_EQUAL = 'whereDayGreaterThanOrEqual';
    case OR_WHERE_DAY_GREATER_THAN_OR_EQUAL = 'orWhereDayGreaterThanOrEqual';

    case WHERE_DAY_LESS_THAN_OR_EQUAL = 'whereDayLessThanOrEqual';
    case OR_WHERE_DAY_LESS_THAN_OR_EQUAL = 'orWhereDayLessThanOrEqual';



    case WHERE_MONTH_EQUAL = 'whereMonthEqual';
    case OR_WHERE_MONTH_EQUAL = 'orWhereMonthEqual';
    case WHERE_MONTH_NOT_EQUAL = 'whereMonthNotEqual';
    case OR_WHERE_MONTH_NOT_EQUAL = 'orWhereMonthNotEqual';

    case WHERE_MONTH_GREATER_THAN = 'whereMonthGreaterThan';
    case OR_WHERE_MONTH_GREATER_THAN = 'orWhereMonthGreaterThan';

    case WHERE_MONTH_LESS_THAN = 'whereMonthLessThan';
    case OR_WHERE_MONTH_LESS_THAN = 'orWhereMonthLessThan';

    case WHERE_MONTH_GREATER_THAN_OR_EQUAL = 'whereMonthGreaterThanOrEqual';
    case OR_WHERE_MONTH_GREATER_THAN_OR_EQUAL = 'orWhereMonthGreaterThanOrEqual';

    case WHERE_MONTH_LESS_THAN_OR_EQUAL = 'whereMonthLessThanOrEqual';
    case OR_WHERE_MONTH_LESS_THAN_OR_EQUAL = 'orWhereMonthLessThanOrEqual';



    case WHERE_YEAR_EQUAL = 'whereYearEqual';
    case OR_WHERE_YEAR_EQUAL = 'orWhereYearEqual';
    case WHERE_YEAR_NOT_EQUAL = 'whereYearNotEqual';
    case OR_WHERE_YEAR_NOT_EQUAL = 'orWhereYearNotEqual';

    case WHERE_YEAR_GREATER_THAN = 'whereYearGreaterThan';
    case OR_WHERE_YEAR_GREATER_THAN = 'orWhereYearGreaterThan';

    case WHERE_YEAR_LESS_THAN = 'whereYearLessThan';
    case OR_WHERE_YEAR_LESS_THAN = 'orWhereYearLessThan';

    case WHERE_YEAR_GREATER_THAN_OR_EQUAL = 'whereYearGreaterThanOrEqual';
    case OR_WHERE_YEAR_GREATER_THAN_OR_EQUAL = 'orWhereYearGreaterThanOrEqual';

    case WHERE_YEAR_LESS_THAN_OR_EQUAL = 'whereYearLessThanOrEqual';
    case OR_WHERE_YEAR_LESS_THAN_OR_EQUAL = 'orWhereYearLessThanOrEqual';



    case WHERE_TIME_EQUAL = 'whereTimeEqual';
    case OR_WHERE_TIME_EQUAL = 'orWhereTimeEqual';
    case WHERE_TIME_NOT_EQUAL = 'whereTimeNotEqual';
    case OR_WHERE_TIME_NOT_EQUAL = 'orWhereTimeNotEqual';

    case WHERE_TIME_GREATER_THAN = 'whereTimeGreaterThan';
    case OR_WHERE_TIME_GREATER_THAN = 'orWhereTimeGreaterThan';

    case WHERE_TIME_LESS_THAN = 'whereTimeLessThan';
    case OR_WHERE_TIME_LESS_THAN = 'orWhereTimeLessThan';

    case WHERE_TIME_GREATER_THAN_OR_EQUAL = 'whereTimeGreaterThanOrEqual';
    case OR_WHERE_TIME_GREATER_THAN_OR_EQUAL = 'orWhereTimeGreaterThanOrEqual';

    case WHERE_TIME_LESS_THAN_OR_EQUAL = 'whereTimeLessThanOrEqual';
    case OR_WHERE_TIME_LESS_THAN_OR_EQUAL = 'orWhereTimeLessThanOrEqual';
}
