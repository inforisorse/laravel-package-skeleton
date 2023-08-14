<?php

namespace Inforisorse\SmsGateway;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Inforisorse\SmsGateway\Commands\SmsGatewayCommand;

class SmsGatewayServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('smsgateway')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_smsgateway_table')
            ->hasCommand(SmsGatewayCommand::class);
    }
}
