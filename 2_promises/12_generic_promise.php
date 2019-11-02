<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Amp\Deferred;
use Amp\Loop;
use function AmphpHowItWorks\println;

const SIGSTP = 20;
$resolved = false;

$deferred = new Deferred();
$promise = $deferred->promise();
$promise->onResolve(function (?\Throwable $failure, $value) {
    if (null !== $failure) {
        println(
            'Generic promise failed with `%s` and message `%s`!',
            get_class($failure),
            $failure->getMessage()
        );

        return;
    }

    println('Generic promise resolved with value `%s`!', $value);
});

Loop::onSignal(SIGSTP, function ($watcherId) use ($deferred, &$resolved) {
    if ($resolved) {
        // Calling Promise::resolve() twice is not allowed
        return;
    }

    $deferred->resolve('Brown Fox');
    $resolved = true;

    // Prevent loop from running if only current watcher is active
    Loop::unreference($watcherId);

    Loop::defer(function() {
        println('Press Ctrl+C to stop the loop');
    });
    Loop::onSignal(SIGINT, function ($sigintWatcherId) {
        println('Stopping the loop');
        Loop::unreference($sigintWatcherId);
    });
});

println('Before Loop::run()');
Loop::run(function () {
    println('Press Ctrl+Z to resolve promise');
});
println('After Loop::run()');

/**
 * Amp\Deferred is the abstraction responsible for resolving future values once they become available.
 * A library that resolves values asynchronously creates an Amp\Deferred
 * and uses it to return an Amp\Promise to API consumers.
 *
 * Output:
 *
 * Before Loop::run()
 * Press Ctrl+Z to resolve promise
 * ^ZGeneric promise resolved with value `Brown Fox`!
 * Press Ctrl+C to stop the loop
 * ^CStopping the loop
 * After Loop::run()
 *
 */
