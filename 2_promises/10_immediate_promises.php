<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Amp\Failure;
use Amp\Success;
use function AmphpHowItWorks\println;

$success = new Success('Brown Fox');
$success->onResolve(function ($alwaysNull, $value) {
    println('Success promise resolved with value `%s`!', $value);
});

$failure = new Failure(new \RuntimeException('Something went wrong'));
$failure->onResolve(function (\Throwable $failureException, $alwaysNull) {
    println(
        'Failure promise failed with `%s` and message `%s`!',
        get_class($failureException),
        $failureException->getMessage()
    );
});

/**
 * Sometimes values are immediately available. This might be due to them being cached,
 * but can also be the case if an interface mandates a promise to be returned to allow
 * for async I/O but the specific implementation always having the result directly available.
 * In these cases Amp\Success and Amp\Failure can be used to construct an immediately resolved promise.
 * Amp\Success accepts a resolution value.
 * Amp\Failure accepts an exception as failure reason.
 *
 * Output:
 *
 * Success promise resolved with value `Brown Fox`!
 * Failure promise failed with `RuntimeException` and message `Something went wrong`!
 *
 */
