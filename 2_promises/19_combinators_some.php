<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Amp\Failure;
use Amp\Loop;
use Amp\MultiReasonException;
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
    $value1 = yield Promise\some([
        async_divide(5, 1),
        async_divide(10, 2),
        async_divide(10, 5),
    ], 1);
    println('Promise (1) resolved with values [[], [%s]]', implode(', ', $value1[1]));

    $value2 = yield Promise\some([
        async_divide(10, 0),
        async_divide(5, 1),
    ], 1);
    println('Promise (2) resolved with values [[%s], [%s]]', implode(', ', array_to_class($value2[0])), implode(', ', $value2[1]));

    $resolved = true;
    try {
        $value3 = yield Promise\some([
            async_divide(5, 1),
            async_divide(5, 0),
            async_divide(9, 0),
            async_divide(10, 0),
        ], 1);
        println('Promise (3) resolved with values [[%s], [%s]]', implode(', ', array_to_class($value3[0])), implode(', ', $value3[1]));
    } catch (MultiReasonException $e) {
        println('Promise (3) failed. Reasons:');
        foreach ($e->getReasons() as $reason) {
            println('    ' . $reason->getMessage());
        }
    }
});
println('After Loop::run()');

/**
 * Resolves with a two-item array [$failures, $successes]
 * Second argument (1 by default) defines the minimum number of promises to succeed
 * If the number of succeeded promises is less than required, then promise fails
 * with MultiReasonException containing each individual failure
 *
 * Output:
 *
 * Before Loop::run()
 * Promise (1) resolved with values [[], [5, 5, 2]]
 * Promise (2) resolved with values [[DivisionByZeroError], [5]]
 * Promise (3) resolved with values [[DivisionByZeroError, DivisionByZeroError, DivisionByZeroError], [5]]
 * After Loop::run()
 */