<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helpers\TransactionHelper;

class TransactionStatusCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transaction:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //update outstanding status to overdue in case due_on < current date
        TransactionHelper::updateTransactionStatus();
    }
}
