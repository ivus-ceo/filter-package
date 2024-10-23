<?php

namespace Ivus\Filter\Enums\Rules\Existables;

use Ivus\Filter\Interfaces\Enums\Rules\{RuleInterface, Existables\ExistableRuleInterface};

enum RelationExistableRule: string implements RuleInterface, ExistableRuleInterface
{
    case WHERE_HAS = 'whereHas';
    case OR_WHERE_HAS = 'orWhereHas';
    case WHERE_DOESNT_HAVE = 'whereDoesntHave';
    case OR_WHERE_DOESNT_HAVE = 'orWhereDoesntHave';

    case WITH_EXISTS = 'withExists';
    case WITH_COUNT = 'withCount';
    case WITH_SUM = 'withSum';
    case WITH_MIN = 'withMin';
    case WITH_MAX = 'withMax';
    case WITH_AVG = 'withAvg';
}
