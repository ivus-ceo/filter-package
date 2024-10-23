<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Debug Mode
    |--------------------------------------------------------------------------
    |
    | When debug mode is enabled, additional logging will be activated to
    | help with debugging filter queries. This is useful for development
    | but should be disabled in production to avoid verbose logs.
    |
    */
    'debug' => true,

    /*
    |--------------------------------------------------------------------------
    | Filter Query Parameter Name
    |--------------------------------------------------------------------------
    |
    | This defines the name of the query parameter in the URL that contains
    | the filtering logic. For example, if set to 'filters', the URL would
    | look like: ?filters=whereIn:id=1,2,3|whereNull:name.
    |
    */
    'query_name' => 'filters',

    /*
    |--------------------------------------------------------------------------
    | Union Separator for Multiple Filters
    |--------------------------------------------------------------------------
    |
    | The character that separates different filters in the query string.
    | By default, it's set to '|', allowing for multiple conditions to be
    | combined, such as: whereIn:id=1,2,3|whereNull:name.
    |
    */
    'union_separator' => '|',

    /*
    |--------------------------------------------------------------------------
    | Rule Separator Between Filter Components
    |--------------------------------------------------------------------------
    |
    | This separator is used between the filter's rule and the column.
    | For instance, in the filter 'whereIn:id=1,2,3', the colon (:) is the
    | separator between 'whereIn' (rule) and 'id' (column).
    |
    */
    'rule_separator' => ':',

    /*
    |--------------------------------------------------------------------------
    | Column and Value Separator
    |--------------------------------------------------------------------------
    |
    | This defines the character that separates a column and its value.
    | For example, in 'id=1,2,3', the equals sign ('=') separates the column
    | 'id' from its values '1,2,3'.
    |
    */
    'column_separator' => '=',

    /*
    |--------------------------------------------------------------------------
    | Value Separator for Multiple Values
    |--------------------------------------------------------------------------
    |
    | The character that separates multiple values for a column in a filter.
    | For instance, 'id=1,2,3' uses a comma (',') to separate the values
    | '1', '2', and '3'. You can customize it based on your needs.
    |
    */
    'value_separator' => ',',

    /*
    |--------------------------------------------------------------------------
    | Relation Separator for Nested Filters
    |--------------------------------------------------------------------------
    |
    | This separator is used to handle nested filters that apply to related
    | models. For example, if you're querying an Author model and want to
    | filter by related Articles, you can use the following syntax:
    |
    | Example: 'whereHasEqual:articles=5~whereIn:id=1,2,3~whereNotNull:title'
    |
    | This will query authors who have 5 articles where the article ID is
    | either 1, 2, or 3. The '~' operator separates the relation ('articles')
    | from the nested filter rule that applies to the related model.
    |
    */
    'relation_separator' => '~',

];
