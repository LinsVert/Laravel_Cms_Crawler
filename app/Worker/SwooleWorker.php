<?php


namespace App\Worker;


class SwooleWorker
{
    public $server = null;

    public function __construct($port = 9502)
    {
        $this->server = new \Swoole\Websocket\Server("127.0.0.1", 9502);
//        dd($this->server);
        $this->registerEvents();
    }

    public function registerEvents() {
//        $this->server->on();

        $reflectionClass = new \ReflectionClass('\Swoole\Websocket\Server');
        $methods = $reflectionClass->getMethods();
        dd($methods);
        $has = $reflectionClass->hasMethod('on');

    }

    public function open() {

    }

    public function start(){
        $this->server->start();
    }
}
