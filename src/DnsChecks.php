<?php

namespace Snaver\Sauron;

use App\Jobs\DnsCheck;
use App\Domain;

class DnsChecks
{

    /**
     * Setup each DNS jobs and queue them
     *
     */
    public function run()
    {
        $domains = Domain::all();

        if($domains->isEmpty())
        {
            dd('No domains!');
        }

        $this->gist_id = env('GITHUB_GIST_ID');
        $this->records = config('sauron.records');
        $this->locations = config('sauron.locations');

        foreach($domains as $domain)
        {
            foreach($this->records as $type)
            {
                foreach($this->locations as $location)
                {
                    dispatch(new DnsCheck($location['node'], $domain, $type, $this->gist_id));
                }
            }

            // Only run once on local..
            if (app('app')->environment() == 'local') break;
        }

        echo 'DNS record checking scheduled.' . PHP_EOL;
    }
}
