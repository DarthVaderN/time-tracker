<?php


use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class ProfilesController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {

    }

    /**
     * Searches for profiles
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'Profiles', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "id";

        $profiles = Profiles::find($parameters);
        if (count($profiles) == 0) {
            $this->flash->notice("The search did not find any profiles");

            $this->dispatcher->forward([
                "controller" => "profiles",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $profiles,
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

    }

    /**
     * Edits a profile
     *
     * @param string $id
     */
    public function editAction($id)
    {
        if (!$this->request->isPost()) {

            $profile = Profiles::findFirstByid($id);
            if (!$profile) {
                $this->flash->error("profile was not found");

                $this->dispatcher->forward([
                    'controller' => "profiles",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->id = $profile->id;

            $this->tag->setDefault("id", $profile->id);
            $this->tag->setDefault("name", $profile->name);
            $this->tag->setDefault("active", $profile->active);
            
        }
    }

    /**
     * Creates a new profile
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "profiles",
                'action' => 'index'
            ]);

            return;
        }

        $profile = new Profiles();
        $profile->name = $this->request->getPost("name");
        $profile->active = $this->request->getPost("active");
        

        if (!$profile->save()) {
            foreach ($profile->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "profiles",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("profile was created successfully");

        $this->dispatcher->forward([
            'controller' => "profiles",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a profile edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "profiles",
                'action' => 'index'
            ]);

            return;
        }

        $id = $this->request->getPost("id");
        $profile = Profiles::findFirstByid($id);

        if (!$profile) {
            $this->flash->error("profile does not exist " . $id);

            $this->dispatcher->forward([
                'controller' => "profiles",
                'action' => 'index'
            ]);

            return;
        }

        $profile->name = $this->request->getPost("name");
        $profile->active = $this->request->getPost("active");
        

        if (!$profile->save()) {

            foreach ($profile->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "profiles",
                'action' => 'edit',
                'params' => [$profile->id]
            ]);

            return;
        }

        $this->flash->success("profile was updated successfully");

        $this->dispatcher->forward([
            'controller' => "profiles",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a profile
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        $profile = Profiles::findFirstByid($id);
        if (!$profile) {
            $this->flash->error("profile was not found");

            $this->dispatcher->forward([
                'controller' => "profiles",
                'action' => 'index'
            ]);

            return;
        }

        if (!$profile->delete()) {

            foreach ($profile->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "profiles",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("profile was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "profiles",
            'action' => "index"
        ]);
    }

}
