<?php

namespace test\Vivait\TenantBundle\app;

if (!file_exists($file = 'vendor/autoload.php')) {
    throw new \RuntimeException('Install the dependencies to run the test suite.');
}

require $file;
