<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class TeamsController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for teams
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'Teams', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = "id";

        $teams = Teams::find($parameters);
        if (count($teams) == 0) {
            $this->flash->notice("The search did not find any teams");

            $this->dispatcher->forward(array(
                "controller" => "teams",
                "action" => "index"
            ));

            return;
        }

        $paginator = new Paginator(array(
            'data' => $teams,
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
     * Edits a team
     *
     * @param string $id
     */
    public function editAction($id)
    {
        if (!$this->request->isPost()) {

            $team = Teams::findFirstByid($id);
            if (!$team) {
                $this->flash->error("team was not found");

                $this->dispatcher->forward(array(
                    'controller' => "teams",
                    'action' => 'index'
                ));

                return;
            }

            $this->view->id = $team->id;

            $this->tag->setDefault("id", $team->id);
            $this->tag->setDefault("name", $team->name);
            
        }
    }

    /**
     * Creates a new team
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward(array(
                'controller' => "teams",
                'action' => 'index'
            ));

            return;
        }

        $team = new Teams();
        $team->name = $this->request->getPost("name");
        

        if (!$team->save()) {
            foreach ($team->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward(array(
                'controller' => "teams",
                'action' => 'new'
            ));

            return;
        }

        $this->flash->success("team was created successfully");

        $this->dispatcher->forward(array(
            'controller' => "teams",
            'action' => 'index'
        ));
    }

    /**
     * Saves a team edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward(array(
                'controller' => "teams",
                'action' => 'index'
            ));

            return;
        }

        $id = $this->request->getPost("id");
        $team = Teams::findFirstByid($id);

        if (!$team) {
            $this->flash->error("team does not exist " . $id);

            $this->dispatcher->forward(array(
                'controller' => "teams",
                'action' => 'index'
            ));

            return;
        }

        $team->name = $this->request->getPost("name");
        

        if (!$team->save()) {

            foreach ($team->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward(array(
                'controller' => "teams",
                'action' => 'edit',
                'params' => array($team->id)
            ));

            return;
        }

        $this->flash->success("team was updated successfully");

        $this->dispatcher->forward(array(
            'controller' => "teams",
            'action' => 'index'
        ));
    }

    /**
     * Deletes a team
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        $team = Teams::findFirstByid($id);
        if (!$team) {
            $this->flash->error("team was not found");

            $this->dispatcher->forward(array(
                'controller' => "teams",
                'action' => 'index'
            ));

            return;
        }

        if (!$team->delete()) {

            foreach ($team->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward(array(
                'controller' => "teams",
                'action' => 'search'
            ));

            return;
        }

        $this->flash->success("team was deleted successfully");

        $this->dispatcher->forward(array(
            'controller' => "teams",
            'action' => "index"
        ));
    }

}
