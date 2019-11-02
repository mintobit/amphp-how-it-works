<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Amp\Loop;
use function AmphpHowItWorks\println;

Loop::onSignal(SIGINT, function ($watcherId) {
    println('Callback executed for SIGNAL watcher with id `%s`', $watcherId);
    Loop::stop();
});

println('Before Loop::run()');
Loop::run(function () {
    println('Press Ctrl+C to stop the loop');
});
println('After Loop::run()');

/**
 * Providing optional callback argument to Loop::run($callback) method
 * is equivalent to Loop::defer($callback) + Loop::run()
 *
 * Output:
 *
 * Before Loop::run()
 * Press Ctrl+C to stop the loop
 * ^CCallback executed for SIGNAL watcher with id `a`
 * After Loop::run()
 *
 */
