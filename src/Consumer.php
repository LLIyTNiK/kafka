<?php


namespace lliytnik\kafka;


class Consumer extends Kafka
{
    public $kafkaConf;
    public $topic;
    public $consumer;
    public $offsetResetType = RD_KAFKA_OFFSET_STORED;
    public $partition = 0;
    public $consumeMessageNumber = 1;
    public $sleep = 0;
    public $messageCallBack;
    public $errorCallBack;

    public function __construct(array $conf)
    {
        parent::__construct($conf['general']);
        if(isset($conf['consumer'])) {
            $this->setConfig($conf['consumer']);
        }
        $this->consumer = new \RdKafka\Consumer($this->_conf);
        ConfigFactory::setObjectParams($this,$conf,'consumer');
        $topicConfig = ConfigFactory::getKafkaConf(ConfigFactory::TOPIC_CONFIG,$conf,'topicConf');
        $this->topic = $this->consumer->newTopic($conf['topicName'],$topicConfig);
    }

    public function consume(){
        echo "Start Consume".PHP_EOL;
        $this->topic->consumeStart($this->partition, $this->offsetResetType);
        if($this->consumeMessageNumber==1){
            $this->consumeOne();
        }else{
            $this->consumeBatch();
        }
    }

    public function consumeOne(){
        $message = null;
        $lastMessage = null;
        while (true) {
            $this->process($this->topic->consume (0, 1000));
        }
    }

    public function consumeBatch(){
        $messages = null;
        $lastMessage = null;
        while (true) {
            $this->process($this->topic->consumeBatch (0, 1000, $this->consumeMessageNumber));
        }
    }

    public function process($message){
        $lastMessage = null;
        if(!empty($message)){
            try {
                if($this->messageCallBack){
                    $lastMessage = ($this->messageCallBack)($message);
                }
                else {
                    $this->topic->consumeStop($this->partition);
                    throw new \Exception("you need to init callback function");
                }
            }catch (\Exception $ex){
                if($this->errorCallBack){
                    $lastMessage = ($this->errorCallBack)($message,$ex);
                }else {
                    $this->topic->consumeStop($this->partition);
                    throw new \Exception("Error in process message, no errorCallBack, consumer stoped. Error:" . $ex->getMessage());
                }
            }
            $this->topic->offsetStore($this->partition, $lastMessage->offset);
        }
    }
}