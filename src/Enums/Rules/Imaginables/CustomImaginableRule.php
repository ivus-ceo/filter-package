<?php

namespace Ivus\Filter\Enums\Rules\Imaginables;

use Ivus\Filter\Interfaces\Enums\Rules\{RuleInterface, Imaginables\ImaginableRuleInterface};

enum CustomImaginableRule: string implements RuleInterface, ImaginableRuleInterface
{
    case INVOKE = 'invoke';
}
