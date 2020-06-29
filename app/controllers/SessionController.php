<?php


use Timer\Forms\LoginForm;
use Timer\Forms\SignUpForm;
use Timer\Forms\ForgotPasswordForm;

/**
 * ControllerBase. This is the base controller for all controllers in the application
 * @property \Timer\Forms\LoginForm
 */
class SessionController extends ControllerBase
{
    /**
     * Default action. Set the public layout (layouts/public.volt)
     */
//    public function initialize()
//    {
//        $this->view->setTemplateBefore('public');
//    }

    public function indexAction()
    {
    }


    public function loginAction()
    {
        $form = new LoginForm();

        try {
            if (!$this->request->isPost()) {
                if ($this->auth->hasRememberMe()) {
                    return $this->auth->loginWithRememberMe();
                }
            } else {
                if ($form->isValid($this->request->getPost()) == false) {
                    foreach ($form->getMessages() as $message) {
                        $this->flash->error($message);
                    }
                } else {
                    $this->auth->check([
                        'email' => $this->request->getPost('email'),
                        'password' => $this->request->getPost('password'),
                        'remember' => $this->request->getPost('remember')
                    ]);

                    return $this->response->redirect('timer/index');
                }
            }
        } catch (Exception $e) {
            $this->flash->error($e->getMessage());
        }

        $this->view->form = $form;
    }


    /**
     * Closes the session
     */
    public function logoutAction()
    {
        $this->auth->remove();

        return $this->response->redirect('index');
    }
}
