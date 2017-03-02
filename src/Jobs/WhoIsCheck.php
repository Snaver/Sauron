<?php

namespace Snaver\Sauron\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Snaver\Sauron\Notifications\WhoisChanged;
use Snaver\Sauron\Domain;
use Snaver\Sauron\Admin;

use Log;
use Unirest;
use GrahamCampbell\GitHub\GitHubManager;

class WhoIsCheck implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $domain;
    protected $gist_id;
    protected $dryRun;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Domain $domain, $gist_id, $dryRun)
    {
        $this->domain = $domain;
        $this->gist_id = $gist_id;
        $this->dryRun = $dryRun;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(GitHubManager $github, Admin $admin)
    {
        if ($this->attempts() > 3)
        {
            $this->delete();
        }

        try {
            if (config('app.env') == 'local') Unirest\Request::verifyPeer(false);

            Unirest\Request::auth(
                config('sauron.jsonwhoisapi_customer_id'),
                config('sauron.jsonwhoisapi_api_key')
            );

            $response = Unirest\Request::get(
                "https://jsonwhoisapi.com/api/v1/whois?identifier=" . $this->domain->domain,
                array("Accept" => "application/json")
            );

            if($response && $response->code == 200)
            {
                // Grab entire record
                $this->gist = $github->gist()->show( $this->gist_id );

                $original_json = $this->gist['files'][$this->domain->domain . '.whois.json']['content'];

                // Encode back to json
                $new_json = json_encode($response->body, JSON_PRETTY_PRINT);

                //dd($original_json, $new_json);

                if($original_json != $new_json)
                {
                    // Update format for github
                    $update['files'][$this->domain->domain . '.whois.json']['content'] = $new_json;

                    //dd($update);

                    if(!$this->dryRun){
                        $gist = $github->api('gists')->update($this->gist_id, $update);

                        $admin->notify(new WhoisChanged($this->domain));
                    }

                    Log::info('Whois changed (' . $this->domain->domain . ') ' . json_encode($response->body));

                    echo $this->domain->domain . ' changes found - updating.'.PHP_EOL;
                }
                else
                {
                    echo $this->domain->domain .' no changes found - skipping.'.PHP_EOL;
                }
            } else {
                Log::info('Whois lookup failed');
            }

            // Go easy..
            sleep(1);
        } catch (\Exception $e) {
            echo $e->getMessage();

            Log::error($e->getMessage());
        }
    }
}
