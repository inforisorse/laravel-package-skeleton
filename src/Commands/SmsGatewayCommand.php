<?php

namespace Inforisorse\SmsGateway\Commands;

use Illuminate\Console\Command;

class SmsGatewayCommand extends Command
{
    public $signature = 'smsgateway';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
