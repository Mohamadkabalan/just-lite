<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use RuntimeException;

trait CreatesApplication
{
    /**
     * Creates the application.
     *
     * Tests read and write the database (inside rolled-back transactions), so
     * they must never run against production. They only run when a dedicated
     * .env.testing exists and is the file Laravel actually loaded; it points
     * at the staging copy of the database.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        if (! is_file(__DIR__.'/../.env.testing')) {
            throw new RuntimeException(
                'Refusing to run tests: .env.testing is missing. Create it with the staging '
                .'database credentials; tests must never use the production .env.'
            );
        }

        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        if ($app->environmentFile() !== '.env.testing') {
            throw new RuntimeException(
                'Refusing to run tests: expected .env.testing to be loaded but got '
                .$app->environmentFile().' (is the config cached? run php artisan config:clear).'
            );
        }

        return $app;
    }
}
