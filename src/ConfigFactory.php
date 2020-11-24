<?php


namespace lliytnik\kafka;


class ConfigFactory
{
    const KAFKA_CONFIG = '\RdKafka\Conf';
    const TOPIC_CONFIG = '\RdKafka\TopicConf';
    
    public static function getKafkaConf($configClassName,$configArray,$arrayItemName=''){
        $conf = new $configClassName;
        $config = self::prepareConfig($configArray,$arrayItemName);
        if($config){
            if($configClassName == self::TOPIC_CONFIG){
                if(isset($config['partitioner'])){
                    $conf->setPartitioner = $config['partitioner'];
                    unset($config['partitioner']);
                }
            }
            self::setConfigParams($conf,$config);
        }
        return $conf;
    }

    public static function setConfigParams($configObj,$configArray,$arrayItemName=''){
        $config = self::prepareConfig($configArray,$arrayItemName);
        if(!empty($config)){
            self::setConfObjParams($configObj,$configArray);
        }
    }

    public static function setConfObjParams($confObj,$params){
        foreach ($params as $paramName=>$paramValue){
            self::setConfObjParam($confObj,$paramName,$paramValue);
        }
    }

    public static function setConfObjParam($confObj,$name,$value){
        $confObj->set($name,$value);
    }

    public static function setObjectParams($object, $configArray,$arrayItemName=''){
        $config = self::prepareConfig($configArray,$arrayItemName);
        if($config){
            foreach ($config as $paramName=>$paramValue){
                $object->$paramName = $paramValue;
            }
        }
        return $object;
    }

    public static function checkConfig($configArray){
        return !empty($configArray) && is_array($configArray);
    }

    public static function prepareConfig($configArray,$arrayItemName){
        if(self::checkConfig($configArray)){
            if($arrayItemName==''){
                return $configArray;
            }
            if(isset($configArray[$arrayItemName])){
                return $configArray[$arrayItemName];
            }
        }
        return [];
    }
}