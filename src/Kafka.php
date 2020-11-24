<?php


namespace lliytnik\kafka;


class Kafka
{
    protected $_conf;
    protected $isConfigured=false;

    /*public $config = [
        'group.id'=>'',
        'metadata.broker.list'=>'',
        'log_level'=> ''.LOG_ERR.'',
    ];*/

    /**
     * Kafka constructor.
     * @param array $kafkaConf
     */
    public function __construct(array $kafkaConf=[],string $arrayItemName='')
    {
        $this->_conf = ConfigFactory::getKafkaConf(ConfigFactory::KAFKA_CONFIG,$kafkaConf,$arrayItemName);
        /*$this->_conf = new \RdKafka\Conf();
        if(!empty($kafkaConf)) {
            $this->setConfig($kafkaConf);
        }*/
    }

    /*public function setConfig(array $conf){
        foreach ($conf as $configName => $configValue){
            $this->setConfigParam($configName, $configValue);
        }
        $this->isConfigured = true;
    }

    public function setConfigParam(string $paramName, $paramValue){
        $this->_conf->set($paramName,$paramValue);
    }*/
}