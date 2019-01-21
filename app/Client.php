<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;//added for passport

class Client extends Model
{
    use Notifiable, HasApiTokens;
    
    protected $fillable = ['name','location','email','phone_number','product'];
}
