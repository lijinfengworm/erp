<?php

include(sfConfigCache::getInstance()->checkConfig(sfConfig::get('sf_app_config_dir_name') . '/custom/rabbitmq.yml'));

class hcConsumerDispatcher {

    private static $instance = null;
    private $consumer;
    private $options;
    private $consumer_class;

    private function __construct($argv) {
        $this->initConsumerName($argv);
        $this->initConsumerHost($argv);
        $this->initializeOptions();
    }

    public static function getInstance($argv) {
        if (self::$instance == null) {
            self::$instance = new hcConsumerDispatcher($argv);
        }

        return self::$instance;
    }

    public function dispatch() {
        $consumer_class = $this->getConsumerClass();
        $c = new $consumer_class($this->getConsumerOptions(), $this->getConsumerHost());
        $c->startConsuming();
    }

    private function initializeOptions() {
        $this->options = sfConfig::get('custom_rabbitmq_options_queues', array());
        $this->validateOptions();
    }

    private function getConsumerHost() {
        return $this->consumer_host;
    }

    private function initConsumerName($argv) {
        if (isset($argv[2])) {
            $this->consumer = $argv[2];
        } else {
            throw new Exception("you must provide the consumer name\n");
        }
    }

    private function initConsumerHost($argv) {
        if (isset($argv[3])) {
            $params = sfConfig::get('custom_rabbitmq_options_params');
            $this->consumer_host = $params[$argv[3]];
        } else {
            throw new Exception("you must provide the consumer host\n");
        }
    }

    private function validateOptions() {
        if (!isset($this->options[$this->consumer])) {
            throw new Exception(sprintf("there's no configuration for consumer: %s\n", $this->consumer));
        }
    }

    private function getConsumerClass() {
        return $this->options[$this->consumer]['class'];
    }

    private function getConsumerOptions() {
        return array_merge(sfConfig::get('custom_rabbitmq_options_params'),
                $this->options[$this->consumer],
                array('consumer_tag' => $this->consumer));
    }

}