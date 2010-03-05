<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected $_view;

    protected function _init()
    {
        Zend_Layout::startMvc();
    }

    protected function _initDoctype()
    {
        $this->bootstrap('view');
        $view = $this->getResource('view');
        $view->doctype('XHTML1_STRICT');
    }
}

