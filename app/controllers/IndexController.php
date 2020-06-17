<?php

use http\Env\Response;
use MongoDB\Driver\Query;

class IndexController extends ControllerBase
{

    public function indexAction()
    {
        $this->view->users = Users::find();
        //display start time test
        $this->view->timerStart = Timer::getTimer(1);
        //display end time test
        $this->view->timerStop = Timer::getTimer(0);

    }

    //send to database time , id , user_id , state
    public function timerAction()
    {
        if ($_POST['time'] === 'start') {
        $timer = new Timer();
        $timer->user_id = $this->request->getPost("user_id");
        $timer->state = 0;
        $timer->time = $timer->getTime();

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
            $timer = new Timer();
            $timer->user_id = $this->request->getPost("user_id");
            $timer->state = 1;
            $timer->time = $timer->getTime();

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
    }


}

