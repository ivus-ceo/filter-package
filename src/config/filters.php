<?php

use Ivus\Filter\Enums\Rules\{ArrayableRule, CustomableRule, NullableRule, StringableRule};

return [

    /*
    |--------------------------------------------------------------------------
    | Filter Query Value Separator
    |--------------------------------------------------------------------------
    |
    | Separator for multiple values, for example: "1,2,3".
    |
    */
    'rules' => [
        ArrayableRule::class,
        NullableRule::class,
        StringableRule::class,
        CustomableRule::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Filter Query Name
    |--------------------------------------------------------------------------
    |
    | This is the name of the filter query parameter.
    |
    */
    'query_name' => 'filters',

    /*
    |--------------------------------------------------------------------------
    | Filter Query Union Separator
    |--------------------------------------------------------------------------
    |
    | Separator between different filter queries.
    |
    */
    'union_separator' => '|',

    /*
    |--------------------------------------------------------------------------
    | Filter Query Rule Separator
    |--------------------------------------------------------------------------
    |
    | Separator for filter query rules.
    |
    */
    'rule_separator' => ':',

    /*
    |--------------------------------------------------------------------------
    | Filter Query Column Separator
    |--------------------------------------------------------------------------
    |
    | Separator for filter query columns and their values.
    |
    */
    'column_separator' => '=',

    /*
    |--------------------------------------------------------------------------
    | Filter Query Value Separator
    |--------------------------------------------------------------------------
    |
    | Separator for multiple values, for example: "1,2,3".
    |
    */
    'value_separator' => ',',

];
