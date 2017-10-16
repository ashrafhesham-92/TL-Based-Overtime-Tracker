<?php

/**
 * Created by PhpStorm.
 * User: Ash
 * Date: 10/16/2017
 * Time: 9:19 AM
 */
class TeamleaderController extends ControllerBase
{
    public function beforeExecuteRoute(\Phalcon\Mvc\Dispatcher $dispatcher)
    {
        if($dispatcher->getActionName() == 'register')
        {
            if($this->session->has('user') && $this->session->get('user')->rule_id === Rules::teamLeader())
            {
                return $this->response->redirect('users');
            }
        }
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
            $this->response->redirect('teamleader/register');
            return;
        }

        if(!Users::exists($this->request->getPost('email')))
        {
            $user = new Users();

            $user->email = $this->request->getPost('email');
            $user->password = $this->security->hash($this->request->getPost('password'));
            $user->name = $this->request->getPost('firstname').' '.$this->request->getPost('lastname');
            $user->team_id = $this->request->getPost('team');
            $user->rule_id = Rules::teamLeader();

            if(!$user->save())
            {
                $this->flashSession->error('Error saving your data, please try again');
                $this->response->redirect('teamleader/register');
                return;
            }

            $this->flashSession->success('You registered successfully, please login');
            $this->response->redirect('teamleader/register');
            return;
        }
        else
        {
            $this->flashSession->error('Email already exists!');
            $this->response->redirect('teamleader/register');
            return;
        }
    }

    public function loginAction()
    {
        if(!$this->request->isPost())
        {
            $this->flashSession->error('Request Error');
            $this->response->redirect('teamleader/register');
            return;
        }
        else
        {
            if(!Users::exists($this->request->getPost('email')))
            {
                $this->flashSession->error('Email doesn\'t exist!');
                $this->response->redirect('teamleader/register');
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
                    if($user->rule_id === Rules::teamLeader())
                    {
                        $this->session->set('user', $user);
                        $this->flashSession->success('You logged in successfully');

                        $redirection = $user->redirectMe();

                        $this->response->redirect($redirection);
                        return;
                    }
                    else
                    {
                        $this->security->hash(rand());
                        $this->flashSession->error('Invalid user rule');
                        $this->response->redirect('teamleader/register');
                        return;
                    }
                }
                else
                {
                    $this->security->hash(rand());
                    $this->flashSession->error('Incorrect password!');
                    $this->response->redirect('teamleader/register');
                    return;
                }
            }
        }
    }

    public function approveAction($overtime_id)
    {
        $overtime = UserOvertime::findFirst($overtime_id);

        if($overtime)
        {
            $overtime->approved = 1;
            $overtime->approve_date = time();
            $overtime->updated_at = time();
            $overtime->approved_by = $this->session->get('user')->id;

            if($overtime->save())
            {
                $this->flashSession->success('Overtime approved');
                $this->response->redirect('userovertime/index/'.$overtime->user_id);
            }
            else
            {
                $this->flashSession->error('Error approving overtime, try again');
                $this->response->redirect('userovertime/index/'.$overtime->user_id);
            }
        }
        else
        {
            $this->flashSession->error('Overtime doesn\'t exist');
            $this->response->redirect('usres');
        }
    }

    public function rejectAction($overtime_id)
    {
        $overtime = UserOvertime::findFirst($overtime_id);

        if($overtime)
        {
            $overtime->approved = 0;
            $overtime->approve_date = time();
            $overtime->updated_at = time();
            $overtime->approved_by = $this->session->get('user')->id;

            if($overtime->save())
            {
                $this->flashSession->success('Overtime rejected');
                $this->response->redirect('userovertime/index/'.$overtime->user_id);
            }
            else
            {
                $this->flashSession->error('Error rejecting overtime, try again');
                $this->response->redirect('userovertime/index/'.$overtime->user_id);
            }
        }
        else
        {
            $this->flashSession->error('Overtime doesn\'t exist');
            $this->response->redirect('usres');
        }
    }
}