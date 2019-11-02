<?php


declare(strict_types=1);

use Amp\Coroutine;
use Amp\Delayed;
use Amp\Loop;
use function AmphpHowItWorks\println;

require __DIR__ . '/../vendor/autoload.php';

$generator = (function (): \Generator {
    $promise2 = new Delayed(1000, 'Brown Fox');
    $promise2->onResolve(function (?\Throwable $failure, $value) {
        println('(2) Delayed promise resolved with value `%s`!', $value);
    });
    $value1 = yield $promise2;

    $promise3 = new Delayed(1000, 'Linux/MacOs/Windows rocks');
    $promise3->onResolve(function (?\Throwable $failure, $value) {
        println('(3) Delayed promise resolved with value `%s`!', $value);
    });
    $value3 = yield $promise3;

    $promise4 = new Delayed(1000, 'Random blocks');
    $promise4->onResolve(function (?\Throwable $failure, $value) {
        println('(4) Delayed promise resolved with value `%s`!', $value);
    });
    $value4 = yield $promise4;

    return sprintf('%s, %s, %s', $value1, $value3, $value4);
})();

$coroutine = new Coroutine($generator);
$coroutine->onResolve(function (?\Throwable $failure, $value) {
    println('(1) Coroutine promise resolved with value `%s`!', $value);
});

Loop::repeat(500, function ($watcherId) {
    println('500ms');
});

println('Before Loop::run()');
Loop::run();
println('After Loop::run()');

/**
 *
 * Before Loop::run()
 * 500ms
 * (2) Delayed promise resolved with value `Brown Fox`!
 * 500ms
 * 500ms
 * (3) Delayed promise resolved with value `Linux/MacOs/Windows rocks`!
 * 500ms
 * 500ms
 * (4) Delayed promise resolved with value `Random blocks`!
 * (1) Coroutine promise resolved with value `Brown Fox, Linux/MacOs/Windows rocks, Random blocks`!
 * 500ms
 * 500ms
 * 500ms
 * ...
 *
 */