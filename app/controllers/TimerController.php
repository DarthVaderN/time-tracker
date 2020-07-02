<?php


use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Timer;

class TimerController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->view->users = Users::find();
        $this->view->holiday = Holiday::find();
        $this->view->setTemplateBefore('public');
    }

    /**
     * Edits a timer
     *
     * @param string $id
     */
    public function editAction($id)
    {
        if (!$this->request->isPost()) {

            $timer = Timer::findFirstByid($id);
            if (!$timer) {
                $this->flash->error("timer was not found");

                $this->dispatcher->forward([
                    'controller' => "timer",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->id = $timer->id;

            $this->tag->setDefault("id", $timer->id);
            $this->tag->setDefault("time", $timer->time);
            $this->tag->setDefault("stop", $timer->stop);
            $this->tag->setDefault("day", $timer->day);
            $this->tag->setDefault("month", $timer->month);
            $this->tag->setDefault("year", $timer->year);
            $this->tag->setDefault("total_time", $timer->total_time);

        }
    }

    /**
     * Saves a timer edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "timer",
                'action' => 'index'
            ]);

            return;
        }

        $id = $this->request->getPost("id");
        $timer = Timer::findFirstByid($id);

        if (!$timer) {
            $this->flash->error("timer does not exist " . $id);

            $this->dispatcher->forward([
                'controller' => "timer",
                'action' => 'index'
            ]);

            return;
        }

        $timer->time = $this->request->getPost("time");
        $timer->stop = $this->request->getPost("stop");


        if (!$timer->save()) {

            foreach ($timer->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "timer",
                'action' => 'edit',
                'params' => [$timer->id]
            ]);

            return;
        }

        $this->flash->success("timer was updated successfully");

        $this->dispatcher->forward([
            'controller' => "timer",
            'action' => 'index'
        ]);
    }

    public function timerAction()
    {
        $lateTime = Late::findFirst();
        if ($_POST['time'] === 'start') {
            $timer = new Timer();
            $timer->user_id = $this->request->getPost("user_id");
            $timer->time = $timer->getTime();
            if($timer->time > $lateTime ){
                $timer->state = 'Not late';
            }else {
                $timer->state = 'Late';
            }
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
            return $this->response->redirect('timer/index');
        }

        if ($_POST['time'] === 'stop') {
            $timer =  Timer::find();
            foreach ($timer as $stop_timer) {
                if($stop_timer->stop == null ){
                    $stop_timer->stop = $stop_timer->getTime();
                    $datetime1 = new DateTime($stop_timer->time);
                    $datetime2 = new DateTime($stop_timer->getTime());
                    $interval = $datetime1->diff($datetime2);
                    $diff_total = $interval->format('%H:%I:%S');
                    $stop_timer->total_time = $diff_total;
                    $stop_timer->update();
                }
            }
            $this->flash->success("timer was created successfully");
            return $this->response->redirect('timer/index');
        }
    }


}
