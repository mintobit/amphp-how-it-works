<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Amp\Loop;
use function AmphpHowItWorks\println;

$delayMs = 0;

Loop::delay($delayMs, function ($watcherId) {
    println('Callback executed for DELAY watcher with id `%s`', $watcherId);
});

Loop::defer(function (string $watcherId) {
    println('Callback executed for DEFER watcher with id `%s`', $watcherId);
});

println('Before Loop::run()');
Loop::run();
println('After Loop::run()');

/**
 * DEFER watcher schedules a callback to execute in the next iteration of the event loop
 * DEFER has a priority over other watchers, so it will be executed first
 *
 * Output:
 *
 * Before Loop::run()
 * Callback executed for DEFER watcher with id `b`
 * Callback executed for DELAY watcher with id `a`
 * After Loop::run()
 *
 */