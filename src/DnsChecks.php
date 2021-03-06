<?php

namespace Snaver\Sauron;

use Snaver\Sauron\Jobs\DnsCheck;
use Snaver\Sauron\Domain;

use Log;

class DnsChecks
{

    /**
     * Setup each DNS jobs and queue them
     *
     */
    public function run($dryRun = false, $domain = '')
    {
        $this->gist_id = config('sauron.github_gist_id');
        $this->records = config('sauron.records');
        $this->locations = config('sauron.locations');

        if ($domain) {
            $domains = Domain::where('domain', $domain)->get();
        } else {
            $domains = Domain::all();
        }

        if($domains->isEmpty())
        {
            dd('No domains!');
        }

        foreach($domains as $domain)
        {
            foreach($this->records as $type)
            {
                foreach($this->locations as $location)
                {
                    dispatch(new DnsCheck($location['node'], $domain, $type, $this->gist_id, $dryRun));
                }
            }

            // Only run once on local..
            if (app('app')->environment() == 'local') break;
        }

        Log::info('Sauron DNS Records checking scheduled');
        echo 'Sauron DNS record checking scheduled.' . PHP_EOL;
    }
}
