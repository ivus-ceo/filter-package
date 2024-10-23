<?php

namespace Ivus\Filter\Enums\Rules\Existables;

use Ivus\Filter\Interfaces\Enums\Rules\{RuleInterface, Existables\ExistableRuleInterface};

enum StringExistableRule: string implements RuleInterface, ExistableRuleInterface
{
    case WHERE = 'where';
    case OR_WHERE = 'orWhere';
    case WHERE_NOT = 'whereNot';
    case OR_WHERE_NOT = 'orWhereNot';
}
