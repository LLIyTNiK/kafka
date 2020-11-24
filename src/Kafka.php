<?php


namespace lliytnik\kafka;


class Kafka
{
    protected $_conf;
    protected $isConfigured=false;

    /**
     * Kafka constructor.
     * @param array $kafkaConf
     */
    public function __construct(array $kafkaConf=[],string $arrayItemName='')
    {
        $this->_conf = ConfigFactory::getKafkaConf(ConfigFactory::KAFKA_CONFIG,$kafkaConf,$arrayItemName);
    }
}