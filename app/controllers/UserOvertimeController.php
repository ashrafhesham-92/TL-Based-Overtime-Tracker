<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class UserOvertimeController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;

        $this->view->overtimes = $this->session->get('user')->overtimes;
    }

    /**
     * Searches for user_overtime
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'UserOvertime', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = "id";

        $user_overtime = UserOvertime::find($parameters);
        if (count($user_overtime) == 0) {
            $this->flash->notice("The search did not find any user_overtime");

            $this->dispatcher->forward(array(
                "controller" => "user_overtime",
                "action" => "index"
            ));

            return;
        }

        $paginator = new Paginator(array(
            'data' => $user_overtime,
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
        $this->view->units = Units::find();
    }

    /**
     * Edits a user_overtime
     *
     * @param string $id
     */
    public function editAction($id)
    {
        if (!$this->request->isPost()) {

            $user_overtime = UserOvertime::findFirstByid($id);
            if (!$user_overtime) {
                $this->flash->error("user_overtime was not found");

                $this->dispatcher->forward(array(
                    'controller' => "user_overtime",
                    'action' => 'index'
                ));

                return;
            }

            $this->view->id = $user_overtime->id;

            $this->tag->setDefault("id", $user_overtime->id);
            $this->tag->setDefault("date", $user_overtime->date);
            $this->tag->setDefault("overtime_amount", $user_overtime->overtime_amount);
            $this->tag->setDefault("overtime_unit_id", $user_overtime->overtime_unit_id);
            $this->tag->setDefault("project_name", $user_overtime->project_name);
            $this->tag->setDefault("user_id", $user_overtime->user_id);
            $this->tag->setDefault("approved", $user_overtime->approved);
            $this->tag->setDefault("approved_by", $user_overtime->approved_by);
            $this->tag->setDefault("approve_date", $user_overtime->approve_date);
            $this->tag->setDefault("created_at", $user_overtime->created_at);
            $this->tag->setDefault("updated_at", $user_overtime->updated_at);
            
        }
    }

    /**
     * Creates a new user_overtime
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->flash->error("Request Error!");
            return $this->response->redirect('userovertime/new');
        }

        $user_overtime = new UserOvertime();
        $user_overtime->date = strtotime($this->request->getPost("date"));
        $user_overtime->overtime_amount = $this->request->getPost("overtime_amount");
        $user_overtime->overtime_unit_id = $this->request->getPost("overtime_unit_id");
        $user_overtime->project_name = $this->request->getPost("project_name");
        $user_overtime->user_id = $this->session->get('user')->id;
        $user_overtime->created_at = time();
        $user_overtime->updated_at = time();
        

        if (!$user_overtime->save()) {
            foreach ($user_overtime->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->response->redirect('userovertime/new');
        }

        $this->flash->success("user_overtime was created successfully");
        return $this->response->redirect('userovertime/index');
    }

    /**
     * Saves a user_overtime edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward(array(
                'controller' => "user_overtime",
                'action' => 'index'
            ));

            return;
        }

        $id = $this->request->getPost("id");
        $user_overtime = UserOvertime::findFirstByid($id);

        if (!$user_overtime) {
            $this->flash->error("user_overtime does not exist " . $id);

            $this->dispatcher->forward(array(
                'controller' => "user_overtime",
                'action' => 'index'
            ));

            return;
        }

        $user_overtime->date = $this->request->getPost("date");
        $user_overtime->overtime_amount = $this->request->getPost("overtime_amount");
        $user_overtime->overtime_unit_id = $this->request->getPost("overtime_unit_id");
        $user_overtime->project_name = $this->request->getPost("project_name");
        $user_overtime->user_id = $this->request->getPost("user_id");
        $user_overtime->approved = $this->request->getPost("approved");
        $user_overtime->approved_by = $this->request->getPost("approved_by");
        $user_overtime->approve_date = $this->request->getPost("approve_date");
        $user_overtime->created_at = $this->request->getPost("created_at");
        $user_overtime->updated_at = $this->request->getPost("updated_at");
        

        if (!$user_overtime->save()) {

            foreach ($user_overtime->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward(array(
                'controller' => "user_overtime",
                'action' => 'edit',
                'params' => array($user_overtime->id)
            ));

            return;
        }

        $this->flash->success("user_overtime was updated successfully");

        $this->dispatcher->forward(array(
            'controller' => "user_overtime",
            'action' => 'index'
        ));
    }

    /**
     * Deletes a user_overtime
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        $user_overtime = UserOvertime::findFirstByid($id);
        if (!$user_overtime) {
            $this->flash->error("user_overtime was not found");

            return $this->response->redirect('userovertime/index');
        }

        if (!$user_overtime->delete()) {

            foreach ($user_overtime->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->response->redirect('userovertime/index');
        }

        $this->flash->success("user_overtime was deleted successfully");

        return $this->response->redirect('userovertime/index');
    }

}
