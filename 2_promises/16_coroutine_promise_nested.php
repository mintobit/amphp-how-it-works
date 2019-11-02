<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Amp\Coroutine;
use Amp\Delayed;
use Amp\Loop;
use function AmphpHowItWorks\println;

$generator = (function (): \Generator {
    $generator2 = (function (): \Generator {
        $promise5 = new Delayed(1000, 'Hit the box');
        $promise5->onResolve(function (?\Throwable $failure, $value) {
            println('(5) Delayed promise resolved with value `%s`!', $value);
        });

        $value5 = yield $promise5;

        $promise6 = new Delayed(1000, 'Which one talks');
        $promise6->onResolve(function (?\Throwable $failure, $value) {
            println('(6) Delayed promise resolved with value `%s`!', $value);
        });

        $value6 = yield $promise6;

        return sprintf('%s, %s', $value5, $value6);
    })();
    $promise2 = new Coroutine($generator2);
    $promise2->onResolve(function (?\Throwable $failure, $value) {
        println('(2) Coroutine promise resolved with value `%s`!', $value);
    });

    $value2 = yield $promise2;

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

    return sprintf('%s, %s, %s', $value2, $value3, $value4);
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
 * (5) Delayed promise resolved with value `Hit the box`!
 * 500ms
 * 500ms
 * (6) Delayed promise resolved with value `Which one talks`!
 * (2) Coroutine promise resolved with value `Hit the box, Which one talks`!
 * 500ms
 * 500ms
 * (3) Delayed promise resolved with value `Linux/MacOs/Windows rocks`!
 * 500ms
 * 500ms
 * (4) Delayed promise resolved with value `Random blocks`!
 * (1) Coroutine promise resolved with value `Hit the box, Which one talks, Linux/MacOs/Windows rocks, Random blocks`!
 * 500ms
 * 500ms
 * ...
 *
 */