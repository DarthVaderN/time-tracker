<?php

use Phalcon\Mvc\Model\Relation;

class Profiles extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var string
     */
    public $active;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("timer");
        $this->setSource("profiles");

        $this->hasMany('id', Users::class, 'profiles_id', [
            'alias' => 'users',
            'foreignKey' => [
                'message' => 'Profile cannot be deleted because it\'s used on Users'
            ]
        ]);
        $this->hasMany('id', Permissions::class, 'profiles_id', [
            'alias' => 'permission',
            'foreignKey' => [
                'action' => Relation::ACTION_CASCADE
            ]
        ]);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'profiles';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Profiles[]|Profiles|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Profiles|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
