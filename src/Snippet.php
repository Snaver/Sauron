<?php

namespace Snaver\Sauron;

use GrahamCampbell\Bitbucket\Facades\Bitbucket;

class Snippet
{

    protected $bitbucket;

    public function __construct(Bitbucket $bitbucket)
    {
        $this->bitbucket = $bitbucket;
    }

    public function debug()
    {
        dd(Bitbucket::api('Repositories\Repository')->get('gentlero', 'bitbucket-api'));
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

    }

    public function get()
    {

    }

    public function update()
    {

    }
}
