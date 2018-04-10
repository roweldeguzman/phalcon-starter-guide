<?php
    use Phalcon\Mvc\View;
    class ErrorController extends ControllerBase{
        public function indexAction(){
            $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        }
    }

