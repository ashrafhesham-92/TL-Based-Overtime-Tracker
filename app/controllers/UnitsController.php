<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class UnitsController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for units
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'Units', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = "id";

        $units = Units::find($parameters);
        if (count($units) == 0) {
            $this->flash->notice("The search did not find any units");

            $this->dispatcher->forward(array(
                "controller" => "units",
                "action" => "index"
            ));

            return;
        }

        $paginator = new Paginator(array(
            'data' => $units,
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
     * Edits a unit
     *
     * @param string $id
     */
    public function editAction($id)
    {
        if (!$this->request->isPost()) {

            $unit = Units::findFirstByid($id);
            if (!$unit) {
                $this->flash->error("unit was not found");

                $this->dispatcher->forward(array(
                    'controller' => "units",
                    'action' => 'index'
                ));

                return;
            }

            $this->view->id = $unit->id;

            $this->tag->setDefault("id", $unit->id);
            $this->tag->setDefault("name", $unit->name);
            
        }
    }

    /**
     * Creates a new unit
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward(array(
                'controller' => "units",
                'action' => 'index'
            ));

            return;
        }

        $unit = new Units();
        $unit->name = $this->request->getPost("name");
        

        if (!$unit->save()) {
            foreach ($unit->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward(array(
                'controller' => "units",
                'action' => 'new'
            ));

            return;
        }

        $this->flash->success("unit was created successfully");

        $this->dispatcher->forward(array(
            'controller' => "units",
            'action' => 'index'
        ));
    }

    /**
     * Saves a unit edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward(array(
                'controller' => "units",
                'action' => 'index'
            ));

            return;
        }

        $id = $this->request->getPost("id");
        $unit = Units::findFirstByid($id);

        if (!$unit) {
            $this->flash->error("unit does not exist " . $id);

            $this->dispatcher->forward(array(
                'controller' => "units",
                'action' => 'index'
            ));

            return;
        }

        $unit->name = $this->request->getPost("name");
        

        if (!$unit->save()) {

            foreach ($unit->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward(array(
                'controller' => "units",
                'action' => 'edit',
                'params' => array($unit->id)
            ));

            return;
        }

        $this->flash->success("unit was updated successfully");

        $this->dispatcher->forward(array(
            'controller' => "units",
            'action' => 'index'
        ));
    }

    /**
     * Deletes a unit
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        $unit = Units::findFirstByid($id);
        if (!$unit) {
            $this->flash->error("unit was not found");

            $this->dispatcher->forward(array(
                'controller' => "units",
                'action' => 'index'
            ));

            return;
        }

        if (!$unit->delete()) {

            foreach ($unit->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward(array(
                'controller' => "units",
                'action' => 'search'
            ));

            return;
        }

        $this->flash->success("unit was deleted successfully");

        $this->dispatcher->forward(array(
            'controller' => "units",
            'action' => "index"
        ));
    }

}
