<?php

namespace Ivus\Filter\Enums\Rules;

use Ivus\Filter\Interfaces\Enums\{RuleInterface, ImaginableBuilderRuleInterface};

enum CustomableRule: string implements RuleInterface, ImaginableBuilderRuleInterface
{
    case WHERE_METHOD = 'whereMethod';
}
