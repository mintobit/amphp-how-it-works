<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Amp\Failure;
use Amp\Loop;
use Amp\Promise;
use Amp\Success;
use function AmphpHowItWorks\println;

function async_divide(int $x, int $y): Promise
{
    if (0 === $y) {
        return new Failure(new DivisionByZeroError(sprintf(
            'Trying to perform %s / %s. Division by zero is not allowed',
            $x,
            $y
        )));
    }

    return new Success($x / $y);
}

println('Before Loop::run()');
Loop::run(function () {
    $value1 = yield Promise\all([
        async_divide(5, 1),
        async_divide(10, 2),
        async_divide(10, 5),
    ]);
    println('Promise (1) resolved with values [%s]', implode(', ', $value1));

    try {
        $value2 = yield Promise\all([
            async_divide(10, 0),
            async_divide(5, 1),
        ]);
    } catch (\Throwable $e) {
        println('Promise (2) failed. Failure message: %s', $e->getMessage());
    }
});
println('After Loop::run()');

/**
 * `all` returns a promise that resolves to array of values (indexing is the same as input promises array)
 * As soon as any provided promise fails, `all` fails with the same exception
 *
 * Output:
 *
 * Before Loop::run()
 * Promise (1) resolved with values [5, 5, 2]
 * Promise (2) failed. Failure message: Trying to perform 10 / 0. Division by zero is not allowed
 * After Loop::run()
 */