<?php

namespace Snaver\Sauron;

use Snaver\Sauron\Jobs\WhoIsCheck;
use Snaver\Sauron\Domain;

class WhoIsChecks
{

    public function run($dryRun = false)
    {
        $domains = Domain::all();

        if($domains->isEmpty())
        {
            dd('No domains!');
        }

        $this->gist_id = env('GITHUB_GIST_ID');

        foreach($domains as $domain)
        {
            dispatch(new WhoIsCheck($domain, $this->gist_id, $dryRun));

            // Only run once on local..
            if (app('app')->environment() == 'local') break;
        }

        echo 'Whois checking scheduled.' . PHP_EOL;
    }
}
