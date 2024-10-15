<?php

namespace Ivus\Filter\Enums\Rules;

use Ivus\Filter\Interfaces\Enums\{RuleInterface, ExistingBuilderRuleInterface};

enum StringableRule: string implements RuleInterface, ExistingBuilderRuleInterface
{
    case WHERE = 'where';
    case OR_WHERE = 'orWhere';
    case WHERE_NOT = 'whereNot';
    case OR_WHERE_NOT = 'orWhereNot';
}
