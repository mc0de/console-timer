# Laravel Console Timer

While Laravel provides excellent tools for building console applications through its Artisan commands and the Laravel Prompts package, one missing feature is built-in execution time tracking. This package fills that gap by providing a simple trait that automatically tracks and displays execution time for your console commands. It's particularly useful for:

- Monitoring long-running commands and batch operations
- Identifying performance bottlenecks in your console applications
- Providing visual feedback during multi-step processes
- Debugging and optimizing command execution times
- Creating professional-looking progress reports

The package integrates seamlessly with Laravel's existing console tools, adding beautiful, formatted timing information to your command output without any configuration.

## Features

- 🕒 Track total command execution time
- ⏱️ Measure individual operation durations
- 🎨 Beautiful console output
- ↩️ Return values from measured operations
- 🔄 Human-readable duration formatting
- ✨ Zero configuration required
- 🔀 Flexible measurement options (closure or manual start/finish)

## Requirements

- PHP ^8.1
- Laravel 10.x|11.x|12.x

## Installation

You can install the package via composer:

```bash
composer require mc0de/console-timer
```

## Usage

### Using with Closures

1. Add the `ConsoleTimer` trait to your command:

```php
use Mc0de\ConsoleTimer\ConsoleTimer;
use Illuminate\Console\Command;

class YourCommand extends Command
{
    use ConsoleTimer;

    public function handle()
    {
        // Start tracking command execution time
        $this->startCommandTimer();

        // Your command logic here
        $this->measure('Processing items', function () {
            // Your processing logic
        });

        // Display total execution time
        $this->displayCommandTime();
    }
}
```

2. Measure operation execution time with return values:

```php
$result = $this->measure('Fetching data', function () {
    // Your logic here
    return ['data' => 'value'];
});

// $result will contain ['data' => 'value']
```

### Using Manual Start/Finish

You can also start and finish measurements separately, which is useful when you need to measure operations that span multiple methods or when you can't use a closure:

```php
public function handle()
{
    $this->startCommandTimer();

    // Start measuring
    $this->startMeasure('Processing items');

    // Your processing logic here
    $this->processItems();

    // Finish measuring
    $this->finishMeasure();

    $this->displayCommandTime();
}

protected function processItems()
{
    // This method is part of the measured operation
    // ...
}
```

## Output Example

```
  12:34:56 Processing items .......................... 1.21s ✓
  12:34:57 Fetching data ............................. 800ms ✓
  12:34:58 Updating records .......................... 2.14s ✓

  Total execution time: 4.1s
```

## Available Methods

### `startCommandTimer()`
Start tracking the command's total execution time. Call this at the beginning of your command.

### `measure(string $message, Closure $callback): mixed`
Measure the execution time of an operation and display its progress.
- `$message`: The operation description to display
- `$callback`: The operation to measure
- Returns: Whatever the callback returns

### `startMeasure(string $message): void`
Start measuring an operation without using a closure.
- `$message`: The operation description to display

### `finishMeasure(): void`
Finish measuring the current operation. Call this after `startMeasure` when the operation is complete.

### `displayCommandTime()`
Display the total execution time of the command. Call this at the end of your command.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
