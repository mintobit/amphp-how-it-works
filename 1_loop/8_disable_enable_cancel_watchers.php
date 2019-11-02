<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Amp\Loop;
use function AmphpHowItWorks\println;

$repeatIntervalMs = 100;
$firstDelayMs = 300;
$secondDelayMs = 600;
$thirdDelayMs = 800;

$repeatWatcherId = Loop::repeat($repeatIntervalMs, function (string $watcherId) {
    println('Callback executed for REPEAT watcher with id `%s`', $watcherId);
});

Loop::delay($firstDelayMs, function ($watcherId) use ($repeatWatcherId) {
    Loop::disable($repeatWatcherId);
    println('Callback executed for DELAY watcher with id `%s`', $watcherId);
});

Loop::delay($secondDelayMs, function ($watcherId) use ($repeatWatcherId) {
    Loop::enable($repeatWatcherId);
    println('Callback executed for DELAY watcher with id `%s`', $watcherId);
});

Loop::delay($thirdDelayMs, function ($watcherId) use ($repeatWatcherId) {
    Loop::cancel($repeatWatcherId);
    println('Callback executed for DELAY watcher with id `%s`', $watcherId);
});

println('Before Loop::run()');
Loop::run();
println('After Loop::run()');

/**
 * You can pause, resume and cancel watchers via
 * Loop::disable($watcherId), Loop::enable($watcherId), and Loop::cancel($watcherId) respectively
 *
 * Output:
 *
 * Before Loop::run()
 * Callback executed for REPEAT watcher with id `a`
 * Callback executed for REPEAT watcher with id `a`
 * Callback executed for DELAY watcher with id `b`
 * Callback executed for DELAY watcher with id `c`
 * Callback executed for REPEAT watcher with id `a`
 * Callback executed for DELAY watcher with id `d`
 * After Loop::run()
 *
 */
