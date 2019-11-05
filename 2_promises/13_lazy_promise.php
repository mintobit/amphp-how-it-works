<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Amp\Delayed;
use Amp\LazyPromise;
use Amp\Loop;
use Amp\Promise;
use function AmphpHowItWorks\println;

function schedule_heartbeat(): void
{
    $repeatWatcherId = Loop::repeat(500, function ($watcherId) {
        println('500ms');
    });
    // Unreference so that loop is stopped when no other active watchers left
    Loop::unreference($repeatWatcherId);
}

function async_multiply(int $x, int $y): Promise
{
    return new Delayed(2000, $x * $y);
}

function lazy_async_multiply(int $x, int $y): Promise
{
    $promisor = function () use ($x, $y): Promise {
        return async_multiply($x, $y);
    };

    return new LazyPromise($promisor);
}

schedule_heartbeat();
$lazyPromise = lazy_async_multiply(2, 10);

Loop::delay(2000, function ($watcherId) use ($lazyPromise) {
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
 * 500ms
 * 500ms
 * Lazy promise resolved with value `20`!
 * 500ms
 * After Loop::run()

 *
 */
