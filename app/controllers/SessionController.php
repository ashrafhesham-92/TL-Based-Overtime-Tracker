<?php

class SessionController extends ControllerBase
{

    public function beforeExecuteRoute(\Phalcon\Mvc\Dispatcher $dispatcher)
    {
        if($this->session->has('user') && $dispatcher->getActionName() !== 'logout')
        {
            $redirection = $this->session->get('user')->redirectMe();
            $this->response->redirect($redirection);
            return;
        }
    }

    public function indexAction()
    {

    }

    public function registerAction()
    {
        $this->view->teams = Teams::find();
    }

    public function signupAction()
    {
        if(!$this->request->isPost())
        {
            $this->flashSession->error('Request Error');
            $this->response->redirect('session/register');
            return;
        }

        if(!Users::exists($this->request->getPost('email')))
        {
            $user = new Users();

            $user->email = $this->request->getPost('email');
            $user->password = $this->security->hash($this->request->getPost('password'));
            $user->name = $this->request->getPost('firstname').' '.$this->request->getPost('lastname');
            $user->team_id = $this->request->getPost('team');
            $user->rule_id = Rules::member();

            if(!$user->save())
            {
                $this->flashSession->error('Error saving your data, please try again');
                $this->response->redirect('session/register');
                return;
            }

            $this->flashSession->success('You registered successfully, please login');
            $this->response->redirect('session/register');
            return;
        }
        else
        {
            $this->flashSession->error('Email already exists!');
            $this->response->redirect('session/register');
            return;
        }
    }

    public function loginAction()
    {
        if(!$this->request->isPost())
        {
            $this->flashSession->error('Request Error');
            $this->response->redirect('session/register');
            return;
        }
        else
        {
            if(!Users::exists($this->request->getPost('email')))
            {
                $this->flashSession->error('Email doesn\'t exist!');
                $this->response->redirect('session/register');
                return;
            }
            else
            {
                $user = Users::findFirst([
                    'email like {email}',
                    'bind' => [
                        'email' => '%'.$this->request->getPost('email').'%'
                    ]
                ]);

                if($this->security->checkHash($this->request->getPost('password'), $user->password))
                {
                    $this->session->set('user', $user);
                    $this->flashSession->success('You registered successfully, please login');

                    $redirection = $user->redirectMe();

                    $this->response->redirect($redirection);
                    return;
                }
                else
                {
                    $this->security->hash(rand());
                    $this->flashSession->error('Incorrect password!');
                    $this->response->redirect('session/register');
                    return;
                }
            }
        }
    }

    public function logoutAction()
    {
        $this->session->destroy();
        $this->flashSession->success('Logout successful');
        $this->response->redirect('session/register');
        return;
    }

}

