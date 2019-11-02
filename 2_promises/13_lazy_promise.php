<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Amp\Delayed;
use Amp\LazyPromise;
use Amp\Loop;
use Amp\Promise;
use function AmphpHowItWorks\println;

$getDelayedPromise = function (): Promise {
    return new Delayed(1000, 'Brown Fox');
};

$lazyPromise = new LazyPromise($getDelayedPromise);

$repeatIntervalMs = 500;
$repeatWatcherId = Loop::repeat($repeatIntervalMs, function ($watcherId) {
    println('500ms');
});
Loop::unreference($repeatWatcherId);

$delayMs = 2000;
Loop::delay($delayMs, function ($watcherId) use ($lazyPromise) {
    $lazyPromise->onResolve(function (?\Throwable $failure, $value) {
        println('Lazy promise resolved with value `%s`!', $value);
    });
    println('Callback executed for DELAY watcher with id `%s`', $watcherId);
});

println('Before Loop::run()');
Loop::run();
println('After Loop::run()');

/**
 * LazyPromise accepts callback that returns a promise.
 * This callback will only be called once somebody subscribes to LazyPromise via LazyPromise::onResolve()
 *
 * Output:
 *
 * Before Loop::run()
 * 500ms
 * 500ms
 * 500ms
 * Callback executed for DELAY watcher with id `b`
 * 500ms
 * 500ms
 * Lazy promise resolved with value `Brown Fox`!
 * 500ms
 * After Loop::run()
 *
 */
