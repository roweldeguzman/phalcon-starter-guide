<?php
use Phalcon\Mvc\View,
    Phalcon\Mvc\Controller,
    Phalcon\Filter;
class IndexController extends ControllerBase{
    public function beforeExecuteRoute($dispatcher) {}
    
    public function indexAction(){}
    
    public function homeAction(){
        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
    }
    
    public function aboutAction(){
        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
    }
    public function contactAction(){
        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
    }
    public function careerAction(){
        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
    }
    
    public function singlePageAction(){
        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
    }
}