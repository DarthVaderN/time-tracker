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
        $time_start = $time->format('H:i:s');
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


    //not working day calculate count (ignore  is parameters with day in weeks ignored for ex. [0 is sunday,  6 is saturday.])
    function countAction($year, $month, $ignore)
    {
        $count = 0;
        $counter = mktime(0, 0, 0, $month, 1, $year);
        while (date("n", $counter) == $month) {
            if (in_array(date("w", $counter), $ignore) == false) {
                $count++;
            }
            $counter = strtotime("+1 day", $counter);
        }
        return $count;
    }
}
