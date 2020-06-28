<?php

use Phalcon\Validation;
use Phalcon\Validation\Validator\Email as EmailValidator;

class Users extends \Phalcon\Mvc\Model
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
    public $email;

    /**
     *
     * @var string
     */
    public $password;

    /**
     *
     * @var string
     */
    public $mustChangePassword;

    /**
     *
     * @var integer
     */
    public $profiles_id;

    /**
     *
     * @var string
     */
    public $banned;

    /**
     *
     * @var string
     */
    public $suspended;

    /**
     *
     * @var string
     */
    public $active;

    /**
     * Validations and business logic
     *
     * @return boolean
     */
    public function validation()
    {
        $validator = new Validation();

        $validator->add(
            'email',
            new EmailValidator(
                [
                    'model'   => $this,
                    'message' => 'Please enter a correct email address',
                ]
            )
        );

        return $this->validate($validator);
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("timer");
        $this->setSource("users");

        $this->belongsTo('profiles_id', Profiles::class, 'id', [
            'alias' => 'profile',
            'reusable' => true
        ]);
        $this->hasMany('id', Timer::class, 'user_id',[
            'alias' => 'timer',
            'reusable' => true, // additional parameters like where, order, limit etc...
            'params' => [
                'order' => 'time ASC'
            ]
        ]);
        $this->hasMany('id', SuccessLogins::class, 'users_id', [
            'alias' => 'successLogins',
            'foreignKey' => [
                'message' => 'User cannot be deleted because he/she has activity in the system'
            ]
        ]);
        $this->hasMany('id', PasswordChanges::class, 'users_id', [
            'alias' => 'passwordChanges',
            'foreignKey' => [
                'message' => 'User cannot be deleted because he/she has activity in the system'
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
        return 'users';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Users[]|Users|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Users|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
