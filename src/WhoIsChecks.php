<?php

namespace Snaver\Sauron;

use App\Jobs\WhoIsCheck;
use App\Domain;

class WhoIsChecks
{

    public function run()
    {
        $domains = Domain::all();

        if($domains->isEmpty())
        {
            dd('No domains!');
        }

        $this->gist_id = env('GITHUB_GIST_ID');

        foreach($domains as $domain)
        {
            dispatch(new WhoIsCheck($domain, $this->gist_id));

            // Only run once on local..
            if (app('app')->environment() == 'local') break;
        }

        echo 'Whois checking scheduled.' . PHP_EOL;
    }
}
