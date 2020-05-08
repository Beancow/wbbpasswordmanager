<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PasswordEntry extends Model
{
    protected $table = 'password_entries';
    protected $fillable = [
        'uid',
        'site',
        'password'
    ];

    public function setUId($Id)
    {
        $this->attributes['uid'] = $Id;
    }
    public function setSite($site)
    {
        $this->attributes['site'] = $site;
    }
    public function setPassword($password)
    {
        $this->attributes['passwordhash'] = $password;
    }
}
