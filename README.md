## Install
```bash
composer require codepku/laravel-query-logger --dev -vvv
```
Then run this command in your Shell:
```bash
php artisan vendor:publish --provider="CodePKu\LaravelQueryLogger\LaravelQueryLoggerServiceProvider"
```
## Configuration
### enabled
Enable Laravel Query Logger

### log-level
Set the log-level for log

### slower
Only log queries longer than this value in seconds, it is `5` second by default