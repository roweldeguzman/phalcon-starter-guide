<?php
use Phalcon\Mvc\View,
    Phalcon\Mvc\Controller,
    Phalcon\Filter;
class PartialController extends ControllerBase{
    public function beforeExecuteRoute($dispatcher) {
        
    }
    
    public function indexAction(){
         
    }
    
    public function headerAction(){
        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
    }
}