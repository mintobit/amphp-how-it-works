<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Amp\Delayed;
use Amp\Loop;
use function AmphpHowItWorks\println;

$delayMs = 1000;

$delayed = new Delayed($delayMs, 'Brown Fox');
$delayed->onResolve(function ($alwaysNull, $value) {
    println('Delayed promise resolved with value `%s`!', $value);
});

println('Before Loop::run()');
Loop::run();
println('After Loop::run()');

/**
 * Same as Success, but resolves after provided delay. Uses DELAY watcher under the hood,
 * so it will not resolve without Loop::run()
 *
 * Output:
 *
 * Before Loop::run()
 * Delayed promise resolved with value `Brown Fox`!
 * After Loop::run()
 *
 */
