<?php

use Phalcon\Mvc\Model;

class Users extends Model
{
    /** @var integer */
    public $id;

    /** @var string */
    public $name;

    /** @var string */
    public $email;

    /** @var string */
    public $password;

    public function initialize()
    {
        $this->hasMany('id', Timer::class, 'user_id');
    }

}
