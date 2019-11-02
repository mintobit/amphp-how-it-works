<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use function AmphpHowItWorks\println;

$generator = (function (): \Generator {
    $value = yield 1;
    $value = yield $value * 2;
    $value = yield $value * 2;

    return $value;
})();

while ($generator->valid()) {
    $value = $generator->current();
    $generator->send($value);
}

$result = $generator->getReturn();

println('Coroutine returned: %s', $result);

/**
 * Coroutines are interruptible functions. In PHP they can be implemented using generators.
 * This example shows the backbone of amphp without actually using the library
 *
 * Output:
 *
 * Coroutine returned: 4
 *
 */