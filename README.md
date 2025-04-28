# Laravel Console Timer

[![Latest Version on Packagist](https://img.shields.io/packagist/v/mc0de/console-timer.svg?style=flat-square)](https://packagist.org/packages/mc0de/console-timer)
[![Total Downloads](https://img.shields.io/packagist/dt/mc0de/console-timer.svg?style=flat-square)](https://packagist.org/packages/mc0de/console-timer)
[![License](https://img.shields.io/packagist/l/mc0de/console-timer.svg?style=flat-square)](https://packagist.org/packages/mc0de/console-timer)

A simple trait that adds execution time tracking to your Laravel console commands. Useful for:

- Tracking how long your commands take to run
- Measuring specific operations within commands
- Debugging slow operations
- Getting timing feedback during long-running processes

![Console Timer](https://github.com/user-attachments/assets/ac7ae2e3-5493-4aa9-ba96-e4335aac1614)

## Features

- Track total command execution time
- Measure individual operations
- Get operation return values
- Format durations in a readable way
- Start/stop measurements manually or with closures

## Requirements

- PHP ^8.1
- Laravel 10.x|11.x|12.x

## Installation

```bash
composer require mc0de/console-timer
```

## Usage

### Basic Usage

Add the trait to your command and wrap operations you want to measure:

```php
use Mc0de\ConsoleTimer\ConsoleTimer;
use Illuminate\Console\Command;

class YourCommand extends Command
{
    use ConsoleTimer;

    public function handle()
    {
        $this->startCommandTimer();

        $this->measure('Processing items', function () {
            // Your processing logic
        });

        $this->displayCommandTime();
    }
}
```

### Getting Return Values

```php
$result = $this->measure('Fetching data', function () {
    return ['data' => 'value'];
});

// $result contains ['data' => 'value']
```

### Manual Timing

For operations that span multiple methods:

```php
public function handle()
{
    $this->startCommandTimer();

    $this->startMeasure('Processing items');
    $this->processItems();
    $this->finishMeasure();

    $this->displayCommandTime();
}

protected function processItems()
{
    // This method is part of the measured operation
}
```

## Example Output

```
  19:17:45 Fetching CRM data ........................................ 1.92s ✓
  19:17:47 Syncing product catalog .................................. 2.34s ✓
  19:17:49 Processing user data ..................................... 394ms ✓
  19:17:49 Running ML recommendations ............................... 3.76s ✓
  19:17:53 Generating sales report .................................. 899ms ✓
  19:17:54 Creating analytics dashboard ............................. 5.26s ✓
  19:17:59 Saving to database ....................................... 389ms ✓

  Completed in 14.99s
```

## Methods

- `startCommandTimer()` - Start tracking total command time
- `measure(string $message, Closure $callback)` - Measure an operation and show progress
- `startMeasure(string $message)` - Start measuring without a closure
- `finishMeasure()` - End the current measurement
- `displayCommandTime()` - Show total command execution time

## License

MIT License - see [LICENSE.md](LICENSE.md)
