<?php

use Phalcon\Mvc\Model;

class SuccessLogins extends Model
{
    /** @var integer */
    public $id;

    /** @var integer */
    public $users_id;

    /** @var string */
    public $ipAddress;

    /** @var string */
    public $userAgent;

    public function initialize()
    {
        $this->belongsTo('users_id', Users::class, 'id', [
            'alias' => 'user'
        ]);
    }
}
