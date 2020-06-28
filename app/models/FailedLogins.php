<?php

use Phalcon\Mvc\Model;

class FailedLogins extends Model
{
    /** @var integer */
    public $id;

    /** @var integer */
    public $usersId;

    /** @var string */
    public $ipAddress;

    /** @var integer */
    public $attempted;

    public function initialize()
    {
        $this->belongsTo('users_id', Users::class, 'id', [
            'alias' => 'user'
        ]);
    }
}
