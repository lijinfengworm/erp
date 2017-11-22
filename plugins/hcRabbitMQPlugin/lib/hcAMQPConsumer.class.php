<?php

abstract class hcAMQPConsumer {

    protected $max_jobs_to_process = 250;
    protected $counter = 0;
    protected $connection;
    protected $channel;
    protected $options = array();

    public function processMessage($msg) {
        try {
            $job = unserialize($msg->body);

            sfContext::getInstance()->getLogger()->log('{hcAMQPConsumer} got job: ' . var_export($job, true));

            if ($this->isValidJob($job)) {
                $this->log($job);
                $this->doJob($job);
                $this->channel->basic_ack($msg->delivery_info['delivery_tag']);
            }
        } catch (Exception $e) {
            echo $e->getMessage(), "\n";
        }

        $this->increaseCounter();
        $this->checkCounter();
    }

    abstract protected function doJob($job);

    protected function log($log) {
        sfContext::getInstance()->getLogger()->log(sprintf('{%s} job: %s', get_class($this), var_export($log, true)));
    }

    protected function isValidJob($job) {
        return true;
    }

    public function __construct($options, $host) {
        $this->options = $options;
        $this->host = $host;
        try {
            $this->initialize();
        } catch (Exception $e) {
            echo "Error While Starting Consumer\n";
        }
    }

    protected function initialize() {
        $this->connect();
        $this->channel = $this->connection->channel();
    }

    protected function increaseCounter() {
        $this->counter++;
    }

    protected function checkCounter() {
        if ($this->counter == $this->max_jobs_to_process) {
            $this->channel->basic_cancel($this->options['consumer_tag']);
        }
    }

    public function startConsuming() {
        $this->queueDeclare();
        $this->exchangeDeclare();
        $this->queueBind();
        $this->accessRequest();
        $this->basicConsume();
        $this->loop();
    }

    protected function connect() {
        $this->connection = new AMQPConnection($this->host, $this->options['port'],
                        $this->options['user'], $this->options['pass']);
    }

    protected function queueDeclare() {
        $this->channel->queue_declare($this->options['queue'],
                !AMQPQueueOptions::PASSIVE, AMQPQueueOptions::DURABLE,
                !AMQPQueueOptions::EXCLUSIVE, !AMQPQueueOptions::AUTO_DELETE);
    }

    protected function exchangeDeclare() {
        $this->channel->exchange_declare($this->options['exchange'],
                AMQPExchangeType::DIRECT, !AMQPExchangeOptions::PASSIVE,
                AMQPExchangeOptions::DURABLE, !AMQPExchangeOptions::AUTO_DELETE);
    }

    protected function queueBind() {
        $this->channel->queue_bind($this->options['queue'], $this->options['exchange']);
    }

    protected function accessRequest() {
        $this->channel->access_request($this->options['vhost'],
                !AMQPRequestOptions::EXCLUSIVE, !AMQPRequestOptions::PASSIVE,
                AMQPRequestOptions::ACTIVE, AMQPRequestOptions::WRITE);
    }

    protected function basicConsume() {
        $this->channel->basic_consume($this->options['queue'],
                $this->options['consumer_tag'],
                !AMQPConsumerOptions::NO_LOCAL, !AMQPConsumerOptions::NO_ACK,
                !AMQPConsumerOptions::EXCLUSIVE, !AMQPConsumerOptions::NO_WAIT,
                array($this, 'processMessage'));
    }

    protected function loop() {
        // Loop as long as the channel has callbacks registered
        while (count($this->channel->callbacks)) {
            $this->channel->wait();
        }

        $this->closeChannel();
        $this->closeConnection();
    }

    protected function closeChannel() {
        $this->channel->close();
    }

    protected function closeConnection() {
        $this->connection->close();
    }

}