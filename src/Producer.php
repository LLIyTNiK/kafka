<?php
namespace lliytnik\kafka;
use lliytnik\kafka\Kafka;

class Producer extends Kafka
{
    public $producer;
    public $topic;
    public $resendNum = 5;
	public $flushTimeout = 100;
	public $partition = RD_KAFKA_PARTITION_UA;
	public $msgFlags = 0;

    /**
     * Sender constructor.
     * @param array $conf
     */
    public function __construct(array $conf){
        parent::__construct($conf,'general');
        $this->producer = new \RdKafka\Producer($this->_conf);
        $this->topic = $this->producer->newTopic($conf['topicName'],ConfigFactory::getKafkaConf(ConfigFactory::TOPIC_CONFIG,$conf,'topic'));
        if(isset($conf['producer'])) {
            foreach ($conf['producer'] as $name => $value) {
                $this->$name = $value;
            }
        }
    }

    /**
     * @param $message for send to broker
     * @return bool message sended
     */
    public function send($message){
        $this->topic->produce($this->partition,$this->msgFlags,$message);
        $this->producer->poll(0);
        for ($flushRetries = 0; $flushRetries < $this->resendNum; $flushRetries++) {
            $result = $this->producer->flush($this->flushTimeout);
            if (RD_KAFKA_RESP_ERR_NO_ERROR === $result) {
                break;
            }
        }
        if (RD_KAFKA_RESP_ERR_NO_ERROR !== $result) {
            return false;
        }
        return true;
    }
}