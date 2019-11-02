<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Amp\Loop;
use function AmphpHowItWorks\println;

const IO_GRANULARITY = 32768;

Loop::onReadable(STDIN, function (string $watcherId, $socket) {
    $newData = trim(@fread($socket, IO_GRANULARITY));
    if ($newData != "") {
        println('Hello, %s!', $newData);
        // Disables this watcher and removes it from the loop
        Loop::cancel($watcherId);
    }
});

println('Before Loop::run()');
Loop::run(function () {
    echo 'Enter your name: ';
});
println('After Loop::run()');

/**
 * READABLE watcher callback is executed when provided stream has data OR if stream EOF is reached
 *
 * Output:
 *
 * Before Loop::run()
 * Enter your name: Andrey
 * Hello, Andrey!
 * After Loop::run()
 *
 */