<?php

use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Dispatcher;

class ControllerBase extends Controller
{
    public function initialize()
    {
        $this->view->setTemplateBefore('main');
    }

    public function beforeExecuteRoute(Dispatcher $dispatcher)
    {
        if($this->session->get('user') == NULL && $dispatcher->getControllerName() !== 'session')
        {
            $this->flashSession->error('Please login first!');

            return $this->response->redirect('session/register');
        }
    }
}
