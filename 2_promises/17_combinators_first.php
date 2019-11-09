<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Amp\Failure;
use Amp\Loop;
use Amp\MultiReasonException;
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
    $value1 = yield Promise\first([
        async_divide(1, 0),
        async_divide(10, 5),
        async_divide(1, 1),
    ]);
    println('Promise (1) resolved with value %s', $value1);

    try {
        $value2 = yield Promise\first([
            async_divide(10, 0),
            async_divide(5, 0)
        ]);
    } catch (MultiReasonException $e) {
        println('Promise (2) failed. Reasons:');
        foreach ($e->getReasons() as $reason) {
            println('    ' . $reason->getMessage());
        }
    }
});
println('After Loop::run()');

/**
 * Combinator function `first` returns a promise that resolves to the value of the first promise that succeeds
 * If all promises fail, then it also fails with MultiReasonException containing each failure
 *
 * Output:
 *
 * Before Loop::run()
 * Promise (1) resolved with value 2
 * Promise (2) failed. Reasons:
 * Trying to perform 10 / 0. Division by zero is not allowed
 * Trying to perform 5 / 0. Division by zero is not allowed
 * After Loop::run()
 */