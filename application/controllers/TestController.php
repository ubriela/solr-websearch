<?php

class TestController extends Zend_Controller_Action {

    public function init() {
	/* Initialize action controller here */
    }

    public function testAction() {
	// action body
//	$this->_helper->viewRenderer->setNoRender();
	echo "Hello";
    }

}

