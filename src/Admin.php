<?php

namespace Snaver\Sauron;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Admin extends Model
{
    use Notifiable;

    protected $email = '';

    protected $slack_url = '';

    public function __construct()
    {
        $this->email = config('sauron.email');
    }

    public function routeNotificationForSlack()
    {
        return $this->slack_url;
    }
}
