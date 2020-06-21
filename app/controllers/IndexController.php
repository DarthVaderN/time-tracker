<?php

use http\Env\Response;
use MongoDB\Driver\Query;

class IndexController extends ControllerBase
{

    public function indexAction()
    {
        $this->view->users = Users::find();
        $this->view->timerStart = Timer::find();



    }
    //send to database time , id , user_id , state
    public function timerAction()
    {
        if ($_POST['time'] === 'start') {
        $timer = new Timer();
        $timer->user_id = $this->request->getPost("user_id");
        $timer->state = '1';
        $timer->time = $timer->getTime();
            $timer->day = (int)date('d');
            $timer->month = (int)date('m');
            $timer->year = (int)date('Y');
        if (!$timer->save()) {
            foreach ($timer->getMessages() as $message) {
                $this->flash->error($message);
            }
            $this->flash->error($message);
            return $this->response->redirect('index/index');
        }
        $this->flash->success("timer was created successfully");
        return $this->response->redirect('index/index');
        }

        if ($_POST['time'] === 'stop') {
            $timer =  Timer::find();
            foreach ($timer as $stop_timer) {
                if($stop_timer->stop == null ){
                $stop_timer->stop = $stop_timer->getTime();
                    $datetime1 = new DateTime($stop_timer->time);
                    $datetime2 = new DateTime();
                    $interval = $datetime1->diff($datetime2);
                    $diff_total = $interval->format('%i');
                    $stop_timer->total_time = (int)$diff_total;
                $stop_timer->update();
                }
            }
            $this->flash->success("timer was created successfully");
            return $this->response->redirect('index/index');
        }
    }


}

