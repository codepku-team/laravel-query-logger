<?php

namespace Overtrue\LaravelQueryLogger;

use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class LaravelQueryLoggerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/laravel-query-logger.php' => config_path('laravel-query-logger.php'),
            ], 'config');
        }

        $this->setupListener();
    }

    /**
     * Setting up listener.
     */
    private function setupListener()
    {
        if (!config('laravel-query-logger.enabled')) {
            return;
        }

        DB::listen(function (QueryExecuted $queryExecuted) {
            $time = $queryExecuted->time;

            $slower = (float)config('laravel-query-logger.slower');
            if ($slower < 0 || $time < $slower) {
                return;
            }

            $level = config('laravel-query-logger.log-level', 'debug');
            $tpl = str_replace(['%', '?'], ['%%', '%s'], $queryExecuted->sql);
            $pdo = $queryExecuted->connection->getPdo();
            $bindings = $queryExecuted->bindings;
            $realSql = vsprintf($tpl, array_map([$pdo, 'quote'], $bindings));
            $duration = $this->beautifyDuration($queryExecuted->time / 1000);

            Log::channel('single')->log($level, sprintf('[%s] %s | %s: %s', $duration, $realSql, request()->method(), request()->getRequestUri()));
        });
    }

    /**
     * Beautify duration.
     *
     * @param float $seconds
     * @return string
     */
    private function beautifyDuration($seconds)
    {
        if ($seconds < 0.001) {
            return round($seconds * 1000000) . 'μs';
        }

        if ($seconds < 1) {
            return round($seconds * 1000, 2) . 'ms';
        }

        return round($seconds, 2) . 's';
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/laravel-query-logger.php', 'laravel-query-logger'
        );
    }
}
