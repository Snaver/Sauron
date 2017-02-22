<?php

namespace Snaver\Sauron\Console;

use Illuminate\Console\Command;

use Snaver\Sauron\DnsChecks;
use Snaver\Sauron\WhoIsChecks;

class Checks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'checks:run {type}
    {--dry-run : Execute as a dry run. }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run Sauron Checks, `dns` or `whois`';

    protected $dnscheck;
    protected $whoischeck;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(DnsChecks $dnscheck, WhoIsChecks $whoischeck)
    {
        parent::__construct();

        $this->dnscheck = $dnscheck;
        $this->whoischeck = $whoischeck;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        switch ($this->argument('type')) {
            case 'dns':
                $this->dnscheck->run($this->option('dry-run') ? true : false);
            break;
            case 'whois':
                $this->whoischeck->run($this->option('dry-run') ? true : false);
            break;
        }
    }
}
