<?php

namespace Mc0de\ConsoleTimer;

use Carbon\CarbonInterface;
use Closure;
use Symfony\Component\Console\Terminal;

trait ConsoleTimer
{
    /**
     * The time when the command started executing.
     */
    protected CarbonInterface $commandStartTime;

    /**
     * The time when the current operation started executing.
     */
    protected CarbonInterface $operationStartTime;

    /**
     * The message for the current operation.
     */
    protected string $currentOperationMessage = '';

    /**
     * Start tracking the command's execution time.
     */
    protected function startCommandTimer(): void
    {
        $this->commandStartTime = now();
    }

    /**
     * Start measuring an operation.
     */
    protected function startMeasure(string $message): void
    {
        $this->operationStartTime      = now();
        $this->currentOperationMessage = $message;

        $this->output->write("  <fg=gray>{$this->operationStartTime->toTimeString()}</> {$message} ");
    }

    /**
     * Finish measuring the current operation.
     */
    protected function finishMeasure(): void
    {
        if (empty($this->currentOperationMessage)) {
            return;
        }

        $this->displayOperationTime($this->currentOperationMessage);
        $this->currentOperationMessage = '';
    }

    /**
     * Measure the duration of the given operation.
     *
     * @template T
     *
     * @param  Closure(): T  $callback
     * @return T
     */
    protected function measure(string $message, Closure $callback): mixed
    {
        $this->startMeasure($message);

        try {
            $result = $callback();
            $this->finishMeasure();

            return $result;
        } catch (\Throwable $e) {
            $this->finishMeasure();
            throw $e;
        }
    }

    /**
     * Display the operation's execution time.
     */
    protected function displayOperationTime(string $message): void
    {
        $duration = $this->formatDuration(
            $this->operationStartTime->diffInRealMilliseconds(now())
        );

        $terminalWidth = (new Terminal)->getWidth();
        $messageLen    = mb_strlen($message . $this->operationStartTime->toTimeString() . $duration);
        $dots          = str_repeat('.', max($terminalWidth - 13 - $messageLen, 0));
        $dots          = empty($dots) ? '…' : $dots;
        $dots          = "<fg=gray>{$dots}</>";

        $this->output->writeln($dots . ' ' . $duration . ' <fg=green;options=bold>DONE</>');
    }

    /**
     * Display the total command execution time.
     */
    protected function displayCommandTime(): void
    {
        if (! isset($this->commandStartTime)) {
            return;
        }

        $duration = $this->formatDuration(
            $this->commandStartTime->diffInRealMilliseconds(now())
        );

        $this->output->newLine();
        $this->output->writeln("  <fg=green>Completed in:</> {$duration}");
    }

    /**
     * Format the given duration in milliseconds.
     */
    protected function formatDuration(int $milliseconds): string
    {
        if ($milliseconds >= 60000) {
            return now()->subMilliseconds($milliseconds)->diff(now())->forHumans(short: true);
        }

        if ($milliseconds >= 1000) {
            return number_format($milliseconds / 1000, 2) . 's';
        }

        return round($milliseconds) . 'ms';
    }
}
