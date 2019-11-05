<?php

declare(strict_types=1);

use Amp\Coroutine;
use Amp\Delayed;
use Amp\Loop;
use Amp\Promise;
use function AmphpHowItWorks\println;

require __DIR__ . '/../vendor/autoload.php';

function async_multiply(int $x, int $y): Promise
{
    return new Delayed(2000, $x * $y);
}

$generator = (function (): \Generator {
    $nestedCoroutine = new Coroutine((function(): \Generator {
        $value2 = yield async_multiply(2, 10);
        println('Promise (2) resolved with value `%s`!', $value2);

        $value3 = yield async_multiply($value2, 2);
        println('Promise (3) resolved with value `%s`!', $value3);

        return $value3 * 2;
    })());

    $value4 = yield $nestedCoroutine;
    println('Promise (4) resolved with value `%s`!', $value4);

    $value5 = yield async_multiply($value4, 2);
    println('Promise (5) resolved with value `%s`!', $value5);

    return $value5 * 2;
})();

$coroutine = new Coroutine($generator);
$coroutine->onResolve(function (?\Throwable $failure, $value) {
    println('Promise (1) resolved with value `%s`!', $value);
});

println('Before Loop::run()');
Loop::run();
println('After Loop::run()');

/**
 *
 * Output:
 *
 * Before Loop::run()
 * Promise (2) resolved with value `20`!
 * Promise (3) resolved with value `40`!
 * Promise (4) resolved with value `80`!
 * Promise (5) resolved with value `160`!
 * Promise (1) resolved with value `320`!
 * After Loop::run()
 *
 */