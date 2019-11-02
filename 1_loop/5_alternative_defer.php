<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Amp\Loop;
use function AmphpHowItWorks\println;

println('Before Loop::run()');
Loop::run(function ($watcherId) {
    println('Callback executed for DEFER watcher with id `%s`', $watcherId);
});
println('After Loop::run()');

/**
 * Providing optional callback argument to Loop::run($callback) method
 * is equivalent to Loop::defer($callback) + Loop::run()
 *
 * Output:
 *
 * Before Loop::run()
 * Callback executed for DEFER watcher with id `a`
 * After Loop::run()
 *
 */