<?php

namespace Snaver\Sauron\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Snaver\Sauron\Notifications\DnsChanged;
use Snaver\Sauron\Domain;
use Snaver\Sauron\Admin;

use Unirest;
use GrahamCampbell\GitHub\GitHubManager;

class DnsCheck implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $location;
    protected $domain;
    protected $type;
    protected $gist_id;
    protected $dryRun;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($location, Domain $domain, $type, $gist_id, $dryRun)
    {
        $this->location = $location;
        $this->domain = $domain;
        $this->type = $type;

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

            Unirest\Request::jsonOpts(true);

            $response = Unirest\Request::get(
                'http://www.dns-lg.com/'.$this->location.'/'.$this->domain->domain.'/'.$this->type,
                array("Accept" => "application/json")
            );

            if($response)
            {
                $data = $response->body;

                if(empty($data['answer']))
                {
                    $answers = [];
                }
                else
                {
                    $answers = array_column($data['answer'], 'rdata');
                    natsort($answers);
                    $answers = array_values($answers);
                }

                // Grab entire record
                $this->gist = $github->gist()->show( $this->gist_id );

                $original_json = $this->gist['files'][$this->domain->domain . '.dns.json']['content'];

                // Decode specific data back to array
                $new = (array)json_decode($original_json, true);

                // Replace with new data
                $new[$this->location][$this->type] = $answers;

                // Encode back to json
                $new_json = json_encode($new, JSON_PRETTY_PRINT);

                //dd($original_json, $new_json);

                if($original_json != $new_json)
                {
                    // Update format for github
                    $update['files'][$this->domain->domain . '.dns.json']['content'] = $new_json;

                    //dd($update);

                    if(!$this->dryRun){
                        $gist = $github->api('gists')->update($this->gist_id, $update);

                        $admin->notify(new DnsChanged($this->location, $this->domain, $this->type));
                    }

                    echo $this->location.'/'.$this->domain->domain.'/'.$this->type . ' changes found - updating.'.PHP_EOL;
                }
                else
                {
                    echo $this->location.'/'.$this->domain->domain.'/'.$this->type . ' no changes found - skipping.'.PHP_EOL;
                }
            }

            // Go easy..
            sleep(1);
        } catch (Exception $ex) {
            echo $e->getMessage();

            Log::error($e->getMessage());
        }
    }
}
