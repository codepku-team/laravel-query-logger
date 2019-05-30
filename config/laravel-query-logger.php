<?php

return [
    /**
     * open the switch
     */
    'enabled' => env('LARAVEL_QUERY_LOGGER_ENABLED', false),

    /**
     * level to log
     */
    'log-level' => env('LARAVEL_QUERY_LOGGER_LOG_LEVEL', 'debug'),

    /**
     * slow query define
     * the unit is second
     */
    'slower' => env('LARAVEL_QUERY_LOGGER_SLOWER', 5),
];
