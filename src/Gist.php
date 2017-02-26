<?php

namespace Snaver\Sauron;

use GrahamCampbell\GitHub\GitHubManager;

class Gist
{

    protected $github;

    public function __construct(GitHubManager $github)
    {
        $this->github = $github;
    }

    public function debug()
    {
        //dd( GitHub::gist()->all() );
        dd( GitHub::gist()->show( config('sauron.github_gist_id') ) );
    }

    public function check()
    {

    }

     /**
     * Format a gist file
     *
     */
    public function format()
    {
        $this->domains = Domain::all();

        if($this->domains->isEmpty())
        {
            dd('No domains!');
        }

        $this->gist_id = config('sauron.github_gist_id');
        $this->records = config('sauron.records');
        $this->locations = config('sauron.locations');

        $this->gist = $this->github->gist()->show( $this->gist_id );

        //dd($this->gist);

        $template = array();
        foreach($this->locations as $k => $location)
        {
            foreach($this->records as $type)
            {
                $template[$location['node']][$type] = [];
            }
        }

        //dump($template, json_encode($template));

        $data['files'] = '';
        foreach($this->domains as $domain)
        {
            $data['files'][$domain->domain . '.dns.json']['content'] = json_encode($template, JSON_PRETTY_PRINT);

            $data['files'][$domain->domain . '.whois.json']['content'] = json_encode([], JSON_PRETTY_PRINT);
        }

        //dd($data);

        $gist = $this->github->api('gists')->update($this->gist_id, $data);

        dd($gist);
    }

    public function get()
    {

    }

    public function update()
    {

    }
}
