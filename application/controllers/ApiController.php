<?php

class ApiController extends Zend_Controller_Action
{
    protected $_auth;

    public function init()
    {
        $this->_auth = Zend_Auth::getInstance();
    }

    public function sessionAction()
    {
        if ($this->_auth->hasIdentity()) {
            $this->getResponse()->setHttpResponseCode(200);
        } else {
            $this->getResponse()->setHttpResponseCode(404);
        }
        $this->getResponse()->sendResponse();
        exit;
    }

    public function createAction()
    {
        if (!$this->_auth->hasIdentity())        return;
        if (!$this->getRequest()->isPost())      return;
        if (!$query = $this->_getParam('query')) return;
        $records = new Application_Model_Records();
        $records->create($query, $this->_auth->getIdentity());
    }
}

