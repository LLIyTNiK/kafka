<?php


namespace lliytnik\eventbust\transports\kafka;


class Kafka
{
    protected $_conf;
    public $kafkaConf = [
        'topicName'=>'test',
        'log_level'=>LOG_ERR,
        'groupID'=>'myGroup',
    ];
    public $brokers = [];

    /**
     * Kafka constructor.
     * @param array $kafkaConf
     */
    public function __construct(Array $kafkaConf)
    {
        $this->_conf = new \RdKafka\Conf();
        $confArray = array_merge($this->kafkaConf,$kafkaConf);
        foreach ($confArray as $configName => $configValue){
            $this->_conf->set($configName, $configValue);
        }
        $this->_conf->set('log_level', (string) $this->logLevel);
    }
}