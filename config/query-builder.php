<?php

return [
    'strict_mode' => false,
    'handle_request_automatically' => true,
    'query_headers' => [
        'enabled' => false,
        'override_request_values' => true,
        'names' => [
            'search' => ['X-Query-Search'],
            'filters' => ['X-Query-Filters', 'X-Query-Filter'],
            'sort_by' => ['X-Query-Sort-By', 'X-Query-Sort'],
            'sort_dir' => ['X-Query-Sort-Dir'],
            'page' => ['X-Query-Page'],
            'per_page' => ['X-Query-Per-Page'],
            'date_from' => ['X-Query-Date-From'],
            'date_to' => ['X-Query-Date-To'],
            'date_column' => ['X-Query-Date-Column'],
            'columns' => ['X-Query-Columns'],
            'with' => ['X-Query-With', 'X-Query-Include', 'X-Query-Includes'],
            'trashed' => ['X-Query-Trashed'],
        ],
    ],
    'response' => [
        'status_key' => 'status',
        'status_value' => true,
        'message_key' => 'message',
    ],
    'search_like_mode' => 'contains',
    'filter_like_mode' => 'contains',
    'default_per_page' => 15,
    'max_per_page' => 100,
    'default_sort_direction' => 'asc',
    'min_search_length' => 3,
    'max_filter_count' => 15,
    'max_filter_value_count' => 100,
    'max_relation_depth' => 3,
];
