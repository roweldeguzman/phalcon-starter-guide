<?php
    use Phalcon\Mvc\Controller,
        Phalcon\Cache\Backend\File as BackFile,
        Phalcon\Cache\Frontend\Data as FrontData,
        Phalcon\Logger\Adapter\File as FileAdapter;

    use Phalcon\Logger,
        Phalcon\Logger\Adapter\Syslog as SyslogAdapter;
    use \Phalcon\Logger\Adapter;
    use \Phalcon\Logger\AdapterInterface;
    use Phalcon\Filter;

    class ControllerBase extends Controller{
        protected function respond($devMessage){
            $this->response->setStatusCode(200, "Ok")->sendHeaders();
            echo json_encode($devMessage);
        }
    }
