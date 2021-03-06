<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class UsersController extends ControllerBase
{
    private $permitted_rules = [];

    public function initialize()
    {
        parent::initialize();
        $this->permitted_rules[] = Rules::teamLeader();
    }
    /**
     * Index action
     */
    public function indexAction()
    {
        if(in_array($this->session->get('user')->rule_id, $this->permitted_rules))
        {
            $this->persistent->parameters = null;

            $this->view->users = Users::getTeamMembers($this->session->get('user')->team_id);

        }
        else
        {
            $this->flashSession->error('You are not permitted!');
            return $this->response->redirect('userovertime');
        }

    }

    /**
     * Searches for users
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'Users', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = "id";

        $users = Users::find($parameters);
        if (count($users) == 0) {
            $this->flash->notice("The search did not find any users");

            $this->dispatcher->forward(array(
                "controller" => "users",
                "action" => "index"
            ));

            return;
        }

        $paginator = new Paginator(array(
            'data' => $users,
            'limit'=> 10,
            'page' => $numberPage
        ));

        $this->view->page = $paginator->getPaginate();
    }

    /**
     * Displays the creation form
     */
    public function newAction()
    {

    }

    /**
     * Edits a user
     *
     * @param string $id
     */
    public function editAction($id)
    {
        if (!$this->request->isPost()) {

            $user = Users::findFirstByid($id);
            if (!$user) {
                $this->flash->error("user was not found");

                $this->dispatcher->forward(array(
                    'controller' => "users",
                    'action' => 'index'
                ));

                return;
            }

            $this->view->id = $user->id;

            $this->tag->setDefault("id", $user->id);
            $this->tag->setDefault("email", $user->email);
            $this->tag->setDefault("password", $user->password);
            $this->tag->setDefault("name", $user->name);
            $this->tag->setDefault("rule_id", $user->rule_id);
            $this->tag->setDefault("team_id", $user->team_id);
            
        }
    }

    /**
     * Creates a new user
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward(array(
                'controller' => "users",
                'action' => 'index'
            ));

            return;
        }

        $user = new Users();
        $user->email = $this->request->getPost("email", "email");
        $user->password = $this->request->getPost("password");
        $user->name = $this->request->getPost("name");
        $user->rule_id = $this->request->getPost("rule_id");
        $user->team_id = $this->request->getPost("team_id");
        

        if (!$user->save()) {
            foreach ($user->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward(array(
                'controller' => "users",
                'action' => 'new'
            ));

            return;
        }

        $this->flash->success("user was created successfully");

        $this->dispatcher->forward(array(
            'controller' => "users",
            'action' => 'index'
        ));
    }

    /**
     * Saves a user edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward(array(
                'controller' => "users",
                'action' => 'index'
            ));

            return;
        }

        $id = $this->request->getPost("id");
        $user = Users::findFirstByid($id);

        if (!$user) {
            $this->flash->error("user does not exist " . $id);

            $this->dispatcher->forward(array(
                'controller' => "users",
                'action' => 'index'
            ));

            return;
        }

        $user->email = $this->request->getPost("email", "email");
        $user->password = $this->request->getPost("password");
        $user->name = $this->request->getPost("name");
        $user->rule_id = $this->request->getPost("rule_id");
        $user->team_id = $this->request->getPost("team_id");
        

        if (!$user->save()) {

            foreach ($user->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward(array(
                'controller' => "users",
                'action' => 'edit',
                'params' => array($user->id)
            ));

            return;
        }

        $this->flash->success("user was updated successfully");

        $this->dispatcher->forward(array(
            'controller' => "users",
            'action' => 'index'
        ));
    }

    /**
     * Deletes a user
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        $user = Users::findFirstByid($id);
        if (!$user) {
            $this->flashSession->error("user was not found");
            $this->response->redirect('users/index');
            return;
        }

        if (!$user->delete()) {

            foreach ($user->getMessages() as $message) {
                $this->flashSession->error($message);
            }
            $this->response->redirect('users/index');
            return;
        }

        $this->flashSession->success("user was deleted successfully");
        $this->response->redirect('users/index');
        return;
    }

}
