<?php

namespace Snaver\Sauron;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Admin extends Model
{
    use Notifiable;

    protected $email = 'richi.evans@gmail.com';

    protected $slack_url = '';

    public function routeNotificationForSlack()
    {
        return $this->slack_url;
    }
}
