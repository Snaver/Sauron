<?php

namespace Snaver\Sauron;

use Snaver\Sauron\Jobs\DnsCheck;
use Snaver\Sauron\Domain;

class DnsChecks
{

    /**
     * Setup each DNS jobs and queue them
     *
     */
    public function run($dryRun = false, $domain = '')
    {
        $this->gist_id = env('GITHUB_GIST_ID');
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

        echo 'DNS record checking scheduled.' . PHP_EOL;
    }
}
