<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Amp\Loop;
use function AmphpHowItWorks\println;

$delayMs = 1000;

Loop::delay($delayMs, function (string $watcherId) {
    println('Callback executed for DELAY watcher with id `%s`', $watcherId);
});

println('Before Loop::run()');
Loop::run();
println('After Loop::run()');

/**
 * DELAYED watcher callback is executed ONCE and is automatically cancelled
 *
 * Output:
 *
 * Before Loop::run()
 * Callback executed for DELAY watcher with id `a`
 * After Loop::run()
 *
 */