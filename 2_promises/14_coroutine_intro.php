<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use function AmphpHowItWorks\println;

/** @var Generator $generator */
$generator = (function (): \Generator {
    $received1 = yield 1;
    println('Generator received %s', $received1);
    $received2 = yield $received1 * 2;
    println('Generator received %s', $received2);
    $received3 = yield $received2 * 2;
    println('Generator received %s', $received3);

    return $received3;
})();

while ($generator->valid()) {
    $yielded = $generator->current();
    println('Generator yielded %s', $yielded);
    $generator->send($yielded);
}

$result = $generator->getReturn();

println('Coroutine returned: %s', $result);

/**
 * Coroutines are interruptible functions. In PHP they can be implemented using generators.
 * This example shows the backbone of amphp without actually using the library
 *
 * Output:
 *
 * Generator yielded 1
 * Generator received 1
 * Generator yielded 2
 * Generator received 2
 * Generator yielded 4
 * Generator received 4
 * Coroutine returned: 4
 *
 */