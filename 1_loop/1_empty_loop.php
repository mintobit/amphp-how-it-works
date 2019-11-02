<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Amp\Loop;
use function AmphpHowItWorks\println;

println('Before Loop::run()');
Loop::run();
println('After Loop::run()');

/**
 * Loop is immediately stopped if there are no ACTIVE or REFERENCED watchers
 *
 * Output:
 *
 * Before Loop::run()
 * After Loop::run()
 *
 */

