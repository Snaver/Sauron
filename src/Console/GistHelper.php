<?php

namespace Snaver\Sauron\Console;

use Illuminate\Console\Command;

use App\Gist;

class GistHelper extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gist:format';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Format a blank gist';

    protected $gist;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Gist $gist)
    {
        parent::__construct();

        $this->gist = $gist;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->gist->format();
    }
}
