<?php

/**
* 
*/
class CrontabController
{
	public function index(Input $i)
	{
	
		$conn = array(
			'host' => '127.0.0.1',
			'port' => '5672',
			'login' => 'guest',
			'password' => 'guest',
			'vhost' => '/',
		);
		// print_r($conn);
		// 创建连接
		try {


			$rmq = new AMQPConnection($conn);
			if (!$rmq->connect()) {
				exit('down');
			}			

			// 创建交换器
			$channel = new AMQPChannel($rmq);
			
			$ex = new AMQPExchange($channel);

			$ex->setName('yanchiex');
			$ex->setType(AMQP_EX_TYPE_DIRECT);
			$ex->setFlags(AMQP_DURABLE);

			// echo "Exchange Status:".$ex->declare()."\n";

			// 创建队列
			$q = new AMQPQueue($channel);
			$q->setName('taskdead');
			$q->setFlags(AMQP_DURABLE);
			

			// 绑定交换机与队列 
			// $q 队列 
			echo 'Queue Bind: '.$q->bind('yanchiex', 'dead')."\n";
			
			$par = array('expiration'=> '80000'); // 失效时间 毫秒
			// $par = array();

			// $ex->publish('TEST', 'addtask', AMQP_NOPARAM, $par);
			//echo "Message Total:".$q->declare()."\n";
			while (1) {
				$q->consume(function($envelope, $queue) {
					$msg = $envelope->getBody();
					echo $msg.PHP_EOL;
					$queue->ack($envelope->getDeliveryTag()); // 确认收到

				});
			}
			$rmq->disconnect();
		} catch (Exception $e) {
			echo $e->getMessage();
		}

	}



}