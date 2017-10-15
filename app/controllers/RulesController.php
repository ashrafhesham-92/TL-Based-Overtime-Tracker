<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class RulesController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for rules
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'Rules', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = "id";

        $rules = Rules::find($parameters);
        if (count($rules) == 0) {
            $this->flash->notice("The search did not find any rules");

            $this->dispatcher->forward(array(
                "controller" => "rules",
                "action" => "index"
            ));

            return;
        }

        $paginator = new Paginator(array(
            'data' => $rules,
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
     * Edits a rule
     *
     * @param string $id
     */
    public function editAction($id)
    {
        if (!$this->request->isPost()) {

            $rule = Rules::findFirstByid($id);
            if (!$rule) {
                $this->flash->error("rule was not found");

                $this->dispatcher->forward(array(
                    'controller' => "rules",
                    'action' => 'index'
                ));

                return;
            }

            $this->view->id = $rule->id;

            $this->tag->setDefault("id", $rule->id);
            $this->tag->setDefault("name", $rule->name);
            
        }
    }

    /**
     * Creates a new rule
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward(array(
                'controller' => "rules",
                'action' => 'index'
            ));

            return;
        }

        $rule = new Rules();
        $rule->name = $this->request->getPost("name");
        

        if (!$rule->save()) {
            foreach ($rule->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward(array(
                'controller' => "rules",
                'action' => 'new'
            ));

            return;
        }

        $this->flash->success("rule was created successfully");

        $this->dispatcher->forward(array(
            'controller' => "rules",
            'action' => 'index'
        ));
    }

    /**
     * Saves a rule edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward(array(
                'controller' => "rules",
                'action' => 'index'
            ));

            return;
        }

        $id = $this->request->getPost("id");
        $rule = Rules::findFirstByid($id);

        if (!$rule) {
            $this->flash->error("rule does not exist " . $id);

            $this->dispatcher->forward(array(
                'controller' => "rules",
                'action' => 'index'
            ));

            return;
        }

        $rule->name = $this->request->getPost("name");
        

        if (!$rule->save()) {

            foreach ($rule->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward(array(
                'controller' => "rules",
                'action' => 'edit',
                'params' => array($rule->id)
            ));

            return;
        }

        $this->flash->success("rule was updated successfully");

        $this->dispatcher->forward(array(
            'controller' => "rules",
            'action' => 'index'
        ));
    }

    /**
     * Deletes a rule
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        $rule = Rules::findFirstByid($id);
        if (!$rule) {
            $this->flash->error("rule was not found");

            $this->dispatcher->forward(array(
                'controller' => "rules",
                'action' => 'index'
            ));

            return;
        }

        if (!$rule->delete()) {

            foreach ($rule->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward(array(
                'controller' => "rules",
                'action' => 'search'
            ));

            return;
        }

        $this->flash->success("rule was deleted successfully");

        $this->dispatcher->forward(array(
            'controller' => "rules",
            'action' => "index"
        ));
    }

}
