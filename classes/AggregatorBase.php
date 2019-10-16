<?php

namespace classes;

use \classes\interfaces\AggregatorInterface;

abstract class AggregatorBase
{
    private $url = false;
    private $port = 80;
    private $method = 'get';
    private $type = false;

    protected $curl = false;

    protected $config = [];

    public function __construct($config)
    {
        if (file_exists($config)) {
            $this->config = include($config);
        } else {
            $this->config = $config;
        }
        $this->curl = curl_init();
    }

    public function __destruct()
    {
        @curl_close($this->curl);
    }

    public function __set($name, $val)
    {
        switch ($name) {
            case 'url':
                if (curl_setopt($this->curl, CURLOPT_URL, $val)) {
                    $this->url = $val;
                }
                break;
            case 'port':
                if(curl_setopt($this->curl, CURLOPT_PORT, $val)) {
                    $this->port = $val;
                }
                break;
            case 'method':
                $success = false;
                $val = strtolower($val);
                if ($val == 'post') {
                    $success = curl_setopt($this->curl, CURLOPT_POST, true);
                }elseif ($val == 'put') {
                    $success = curl_setopt($this->curl, CURLOPT_PUT, true);
                }elseif ($val == 'get') {
                    $success = curl_setopt($this->curl, CURLOPT_POST, false);
                    $success &= curl_setopt($this->curl, CURLOPT_PUT, false);
                }
                if ($success) {
                    $this->method = $val;
                }
                break;
            case 'type':
                $success = false;
                $val = strtolower($val);
                if ($val == 'html') {
                    $success = curl_setopt($this->curl, CURLOPT_HEADER, ['Content-Type: text/html']);
                }elseif ($val == 'json') {
                    $success = curl_setopt($this->curl, CURLOPT_HEADER, ['Content-Type: application/json']);
                } // .... xml, ....
                if ($success) {
                    $this->type = $val;
                }
                break;
        }
    }

    public function __get($name)
    {
        if (key_exists($name, $this->config)) {
            return $this->config[$name];
        }
        throw new \Exception('Property '.$name.' does not exist');
    }

    protected function callAPI($method, $params)
    {
        if (empty($this->url)) {
            throw new \Exception('Can not use service: URL is not set');
        }
        if ($this->type === false) {
            $this->__set('type', 'json');
        }

        if ($this->type == 'json') {
            $params = json_encode($params);
        }

        if ($this->method == 'get') {
            $this->url = sprintf('%s/%s?%s', $this->url, $method, http_build_query($params));
        } elseif ($this->method == 'post') {
            $this->url = sprintf('%s/%s', $this->url, $method);
            curl_setopt($this->curl, CURLOPT_POSTFIELDS, $params);
        }

        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_HEADER, true);
        $retval = curl_exec($this->curl);
        return $retval;
    }

    public function __call($method, $params)
    {
        return $this->callAPI($method, $params[0]);
    }

    public static function getAggregator($class, $constructParams)
    {
        if (!class_exists($class)) {
            throw new \InvalidArgumentException('Class '.$class.' does not exist');
        }
        $aggregator = new $class($constructParams);
        if (!($aggregator instanceof AggregatorInterface)) {
            throw new \InvalidArgumentException('Class '.$class.' must implement AggregatorInterface');
        }
        return $aggregator;
    }
}