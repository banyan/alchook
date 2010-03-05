<?php

require_once 'Ak33m/Openid/Consumer.php';
require_once 'Ak33m/Openid/Extension/Ax.php';
require_once 'Ak33m/Auth/Adapter/OpenId.php';

class IndexController extends Zend_Controller_Action
{
    private $_auth;
    private $_alcUrl = 'http://www.alc.co.jp/';

    public function init()
    {
        $this->_auth = Zend_Auth::getInstance();
    }

    public function indexAction()
    {
        if ($this->_auth->hasIdentity()) {
            $records = new Application_Model_Records();
            $this->view->records    = $records->getByIdentity($this->_auth->getIdentity());
            $this->view->graph_data = Zend_Json::encode($records->getGraphData($this->_auth->getIdentity()));
            $this->view->count      = count($this->view->records);
            $this->view->email      = $this->_auth->getIdentity();
        }
        $this->view->hasIdentity = $this->_auth->hasIdentity();
    }

    public function sessionAction()
    {
        $sreg = new Ak33m_OpenId_Extension_Ax(
            array('email' => true)
        );

        $openidIdentifier = $this->_request->getParam('openid_identifier');
        if (!is_null($openidIdentifier) || $this->_request->getParam('openid_mode')) {
            $result = $this->_auth->authenticate(
                new Ak33m_Auth_Adapter_OpenId($openidIdentifier, null, null, null, $sreg)
            );
            if ($result->isValid()) {
                $data = $sreg->getProperties();
                // email を identity にする
                Zend_Auth::getInstance()->getStorage()->write($data['email']);
            } else {
                $this->_auth->clearIdentity();
            }
        }
        $this->_redirect('/');
    }

    public function logoutAction()
    {
        if ($this->_auth->hasIdentity()) {
            $this->_auth->clearIdentity();
        }
        $this->_redirect('/');
    }
}

