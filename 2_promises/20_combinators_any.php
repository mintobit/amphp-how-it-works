<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Amp\Failure;
use Amp\Loop;
use Amp\Promise;
use Amp\Success;
use function AmphpHowItWorks\println;

function array_to_class(array $array): array
{
    return array_map(function ($item) {
        return get_class($item);
    }, $array);
}

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
    $value1 = yield Promise\any([
        async_divide(5, 1),
        async_divide(10, 2),
        async_divide(10, 5),
    ]);
    println('Promise (1) resolved with values [[], [%s]]', implode(', ', $value1[1]));

    $value2 = yield Promise\any([
        async_divide(10, 0),
        async_divide(5, 1),
    ]);
    println('Promise (2) resolved with values [[%s], [%s]]', implode(', ', array_to_class($value2[0])), implode(', ', $value2[1]));
});
println('After Loop::run()');

/**
 * Returns a promise that is resolved when all promises are resolved. The returned promise will not fail.
 * Returned promise succeeds with a two-item array [$failures, $successes],
 * with keys identical and corresponding to the original given array.
 *
 * Output:
 *
 * Before Loop::run()
 * Promise (1) resolved with values [[], [5, 5, 2]]
 * Promise (2) resolved with values [[DivisionByZeroError], [5]]
 * After Loop::run()
 */