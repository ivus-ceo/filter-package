<?php

return [

    // ?filters=whereIn:id=1,2,3|whereIsTrue:boolean|whereNotNull:updated_at|whereGreaterThan:number=5

    /*
    |--------------------------------------------------------------------------
    | Enable debug mode
    |--------------------------------------------------------------------------
    |
    | Enable debug mode with logging.
    |
    */
    'debug' => true,

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
