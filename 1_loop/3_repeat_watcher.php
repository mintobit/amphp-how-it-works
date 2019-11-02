<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Amp\Loop;
use function AmphpHowItWorks\println;

$repeatIntervalMs = 1000;

Loop::repeat($repeatIntervalMs, function (string $watcherId) {
    println('Callback executed for REPEAT watcher with id `%s`', $watcherId);
});

println('Before Loop::run()');
Loop::run();
println('After Loop::run()');

/**
 * Loop will never stop while repeat watcher is ACTIVE or REFERENCED
 *
 * Output:
 *
 * Before Loop::run()
 * Callback executed for REPEAT watcher with id `a`
 * Callback executed for REPEAT watcher with id `a`
 * Callback executed for REPEAT watcher with id `a`
 * ...
 *
 */
