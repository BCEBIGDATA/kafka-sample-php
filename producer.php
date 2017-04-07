<?php

$shortopts  = "";
$shortopts .= "t:";  // topic

$longopts  = array(
    "topic:",
    "broker::",         // default = kafka.bj.baidubce.com:9091. The broker address can be found in https://cloud.baidu.com/doc/Kafka/QuickGuide.html
    "security_protocol::",  // default = ssl. SSL is required to access Baidu Kafka service.
    "client_pem::",     // default = client.pem. File path to client.pem provided in kafka-key.zip from console.
    "client_key::",     // default = client.key. File path to client.key provided in kafka-key.zip from console.
    "ca_pem::",         // default = ca.pem. File path to ca.pem provided in kafka-key.zip from console.
);
$options = getopt($shortopts, $longopts);

if (!isset($options['topic'])) {
    die("Usage: producer.php --topic=<topic_name> [--broker=<broker-address>]\n");
}

$topic = $options['topic'];
$broker = isset($options['broker']) ? $options['broker'] : 'kafka.bj.baidubce.com:9091';
$security_protocol = isset($options['security_protocol']) ? $options['security_protocol'] : 'ssl';
$client_pem = isset($options['client_pem']) ? $options['client_pem'] : 'client.pem';
$client_key = isset($options['client_key']) ? $options['client_key'] : 'client.key';
$ca_pem = isset($options['ca_pem']) ? $options['ca_pem'] : 'ca.pem';

$conf = new RdKafka\Conf();
$conf->set('metadata.broker.list', $broker);
$conf->set('security.protocol', $security_protocol);
$conf->set('ssl.certificate.location', $client_pem);
$conf->set('ssl.key.location', $client_key);
$conf->set('ssl.ca.location', $ca_pem);

$rk = new RdKafka\Producer($conf);
$rk->setLogLevel(LOG_DEBUG); 
$rk->addBrokers($broker);
$topic = $rk->newTopic($topic);

for ($i = 0; $i < 10; $i++) {
    $topic->produce(RD_KAFKA_PARTITION_UA, 0, "$i-Hello kafka");
}

?>
