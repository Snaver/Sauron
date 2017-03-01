<?php

namespace Snaver\Sauron;

use Snaver\Sauron\Jobs\WhoIsCheck;
use Snaver\Sauron\Domain;

class WhoIsChecks
{

    public function run($dryRun = false, $domain = '')
    {
        $this->gist_id = config('sauron.github_gist_id');

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
            dispatch(new WhoIsCheck($domain, $this->gist_id, $dryRun));

            // Only run once on local..
            if (app('app')->environment() == 'local') break;
        }

        Log::info('Sauron Whois checking scheduled');
        echo 'Sauron Whois checking scheduled.' . PHP_EOL;
    }
}
