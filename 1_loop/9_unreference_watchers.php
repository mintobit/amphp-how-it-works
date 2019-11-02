<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Amp\Loop;
use function AmphpHowItWorks\println;

$repeatIntervalMs = 1000;

$signalWatcherId = Loop::onSignal(SIGINT, function (string $watcherId) {
    println('Callback executed for SIGNAL watcher with id `%s`', $watcherId);
});
Loop::unreference($signalWatcherId);

Loop::delay(5000, function ($watcherId) use ($signalWatcherId) {
    println('Callback executed for DELAY watcher with id `%s`', $watcherId);
});

println('Before Loop::run()');
Loop::run(function ($watcherId) {
    println('If you Press Ctrl+C, SIGNAL watcher callback will be executed.' . PHP_EOL
        . 'If you don not press it, the loop will still be stopped after delay watcher callback is executed');
});
println('After Loop::run()');

/**
 * An unreferenced watcher does not keep the loop alive. All watchers are referenced by default.
 * This is useful when you do not want to disable the watcher and want the loop to be stopped
 * if there are not other referenced watchers. A good example could be SIGNAL watcher
 *
 * Output:
 *
 * Before Loop::run()
 * Press Ctrl+C
 * ^CCallback executed for SIGNAL watcher with id `a`
 * Callback executed for DELAY watcher with id `b`
 * After Loop::run()
 *
 */
