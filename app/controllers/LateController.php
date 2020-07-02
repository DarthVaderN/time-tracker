<?php


use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Late;

class LateController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
        $this->view->late = Late::find();
        $this->view->setTemplateBefore('private');
    }

    /**
     * Searches for late
     */
    public function searchAction()
    {
        $this->view->setTemplateBefore('private');
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'Late', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "id";

        $late = Late::find($parameters);
        if (count($late) == 0) {
            $this->flash->notice("The search did not find any late");

            $this->dispatcher->forward([
                "controller" => "late",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $late,
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
        $this->view->setTemplateBefore('private');
    }

    /**
     * Edits a late
     *
     * @param string $id
     */
    public function editAction($id)
    {
        if (!$this->request->isPost()) {

            $late = Late::findFirstByid($id);
            if (!$late) {
                $this->flash->error("late was not found");

                $this->dispatcher->forward([
                    'controller' => "late",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->id = $late->id;

            $this->tag->setDefault("id", $late->id);
            $this->tag->setDefault("time", $late->time);
            
        }
    }

    /**
     * Creates a new late
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "late",
                'action' => 'index'
            ]);

            return;
        }

        $late = new Late();
        $late->time = $this->request->getPost("time");
        

        if (!$late->save()) {
            foreach ($late->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "late",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("late was created successfully");

        $this->dispatcher->forward([
            'controller' => "late",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a late edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "late",
                'action' => 'index'
            ]);

            return;
        }

        $id = $this->request->getPost("id");
        $late = Late::findFirstByid($id);

        if (!$late) {
            $this->flash->error("late does not exist " . $id);

            $this->dispatcher->forward([
                'controller' => "late",
                'action' => 'index'
            ]);

            return;
        }

        $late->time = $this->request->getPost("time");
        

        if (!$late->save()) {

            foreach ($late->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "late",
                'action' => 'edit',
                'params' => [$late->id]
            ]);

            return;
        }

        $this->flash->success("late was updated successfully");

        $this->dispatcher->forward([
            'controller' => "late",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a late
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        $late = Late::findFirstByid($id);
        if (!$late) {
            $this->flash->error("late was not found");

            $this->dispatcher->forward([
                'controller' => "late",
                'action' => 'index'
            ]);

            return;
        }

        if (!$late->delete()) {

            foreach ($late->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "late",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("late was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "late",
            'action' => "index"
        ]);
    }

}
