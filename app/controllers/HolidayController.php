<?php


use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class HolidayController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
        $this->view->setTemplateBefore('public');
    }

    /**
     * Searches for holiday
     */
    public function searchAction()
    {

        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'Holiday', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "id";

        $holiday = Holiday::find($parameters);
        if (count($holiday) == 0) {
            $this->flash->notice("The search did not find any holiday");

            $this->dispatcher->forward([
                "controller" => "holiday",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $holiday,
            'limit'=> 10,
            'page' => $numberPage
        ]);

        $this->view->page = $paginator->getPaginate();
    }

    /**
     * Displays the creation form
     */
    public function newAction()
    {
        $this->view->setTemplateBefore('public');
    }

    /**
     * Edits a holiday
     *
     * @param string $id
     */
    public function editAction($id)
    {
        if (!$this->request->isPost()) {

            $holiday = Holiday::findFirstByid($id);
            if (!$holiday) {
                $this->flash->error("holiday was not found");

                $this->dispatcher->forward([
                    'controller' => "holiday",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->id = $holiday->id;

            $this->tag->setDefault("id", $holiday->id);
            $this->tag->setDefault("name", $holiday->name);
            $this->tag->setDefault("day", $holiday->day);
            $this->tag->setDefault("month", $holiday->month);
            $this->tag->setDefault("active", $holiday->active);
            
        }
    }

    /**
     * Creates a new holiday
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "holiday",
                'action' => 'index'
            ]);

            return;
        }

        $holiday = new Holiday();
        $holiday->name = $this->request->getPost("name");
        $holiday->day = $this->request->getPost("day");
        $holiday->month = $this->request->getPost("month");
        $holiday->active = $this->request->getPost("active");
        

        if (!$holiday->save()) {
            foreach ($holiday->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "holiday",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("holiday was created successfully");

        $this->dispatcher->forward([
            'controller' => "holiday",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a holiday edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "holiday",
                'action' => 'index'
            ]);

            return;
        }

        $id = $this->request->getPost("id");
        $holiday = Holiday::findFirstByid($id);

        if (!$holiday) {
            $this->flash->error("holiday does not exist " . $id);

            $this->dispatcher->forward([
                'controller' => "holiday",
                'action' => 'index'
            ]);

            return;
        }

        $holiday->name = $this->request->getPost("name");
        $holiday->dateHoliday = $this->request->getPost("day");
        $holiday->dateHoliday = $this->request->getPost("month");
        $holiday->active = $this->request->getPost("active");
        

        if (!$holiday->save()) {

            foreach ($holiday->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "holiday",
                'action' => 'edit',
                'params' => [$holiday->id]
            ]);

            return;
        }

        $this->flash->success("holiday was updated successfully");

        $this->dispatcher->forward([
            'controller' => "holiday",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a holiday
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        $holiday = Holiday::findFirstByid($id);
        if (!$holiday) {
            $this->flash->error("holiday was not found");

            $this->dispatcher->forward([
                'controller' => "holiday",
                'action' => 'index'
            ]);

            return;
        }

        if (!$holiday->delete()) {

            foreach ($holiday->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "holiday",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("holiday was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "holiday",
            'action' => "index"
        ]);
    }

}
