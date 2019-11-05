<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Amp\Deferred;
use Amp\Loop;
use Amp\Promise;
use function AmphpHowItWorks\println;

function async_multiply(int $x, int $y): Promise
{
    $deferred = new Deferred();
    Loop::delay(2000, function () use ($x, $y, $deferred) {
        $deferred->resolve($x * $y);
    });

    return $deferred->promise();
}

$promise = async_multiply(2, 5);
$promise->onResolve(function (?\Throwable $failure, $value) {
    // If promise is resolved with failure
    if (null !== $failure) {
        println(
            'Generic promise failed with `%s` and message `%s`!',
            get_class($failure),
            $failure->getMessage()
        );

        return;
    }

    // If promise is resolved with value
    println('Generic promise resolved with value `%s`!', $value);
});

println('Before Loop::run()');
Loop::run();
println('After Loop::run()');

/**
 * Amp\Deferred is the abstraction responsible for resolving future values once they become available.
 * A library that resolves values asynchronously creates an Amp\Deferred
 * and uses it to return an Amp\Promise to API consumers.
 *
 * Output:
 *
 * Before Loop::run()
 * Generic promise resolved with value `10`!
 * After Loop::run
 *
 */
