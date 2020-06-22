<?php

class Timer extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $user_id;

    /**
     *
     * @var string
     */
    public $state;

    /**
     *
     * @var string
     */
    public $time;

    /**
     *
     * @var string
     */
    public $stop;
    /**
     *
     * @var string
     */
    public $day;
    /**
     *
     * @var string
     */
    public $month;
    /**
     *
     * @var string
     */
    public $year;

    // relationships one to many
    public function initialize()
    {
        $this->setSchema("timer");
        $this->setSource("timer");

        $this->belongsTo('user_id', Users::class, 'id',[
            'alias' => 'user']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'timer';
    }
    //get time for form
    public function getTime()
    {
        date_default_timezone_set('Asia/Bishkek');
        $time = new DateTime();
        $time_start = $time->format('Y-m-d H:i:s');
        return $time_start;
    }


    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Timer[]|Timer|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }




    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Timer|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters = null);
    }
    // test get time for view where state 1(end time) or 0(start time) ;
//    public static function getTimer($parameters)
//    {
//        $di = Phalcon\DI\FactoryDefault::getDefault()->get('modelsManager')
//            ->createBuilder();
//
//        $result = $di
//            ->from('timer')->where('timer.state = '.$parameters)
//            ->getQuery()
//            ->execute();
//
//        return $result;
//    }
}
