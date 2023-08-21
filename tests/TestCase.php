<?php

namespace Inforisorse\SmsGateway\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Inforisorse\SmsGateway\SmsGatewayServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Inforisorse\\SmsGateway\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            SmsGatewayServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        /*
        $migration = include __DIR__.'/../database/migrations/create_smsgateway_table.php.stub';
        $migration->up();
        */
    }
}
