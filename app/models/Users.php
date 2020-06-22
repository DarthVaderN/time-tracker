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

        $this->setSchema("timer");
        $this->setSource("users");
        $this->hasMany('id', Timer::class, 'user_id',[
            'alias' => 'timer',
            'reusable' => true, // additional parameters like where, order, limit etc...
            'params' => [
                'order' => 'time ASC',
//                'bind' => [
//                    'type' => get_class($this)
//                ]
            ]
        ]);
        $this->belongsTo('prof_id',Profiles::class, 'id', [
            'alias' => 'profile',
            'reusable' => true
        ]);

    }






}
