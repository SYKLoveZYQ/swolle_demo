<?php

	use function Swoole\Coroutine\run;
	use function Swoole\Coroutine\go;


	$ws = new Swoole\WebSocket\Server('0.0.0.0', 3306);
	

	$ws->on('Open', function ($ws, $request) {
		echo 'request:'.$request->fd;
		go(function() use($ws,$request) {
			echo '2222222';
			sleep(10);
			$ws->push($request->fd, '欢迎来到俪xxxxx，你有啥事么');
			sleep(10);
			$ws->push($request->fd,'美容,美发，xxx');
		});	     
	});

	//监听WebSocket消息事件
	$ws->on('Message', function ($ws, $frame) {
		echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";
		global $ws;//调用外部的server
		    // $server->connections 遍历所有websocket连接用户的fd，给所有用户推送
		     foreach ($ws->connections as $fd) {
			     // 需要先判断是否是正确的websocket连接，否则有可能会push失败
			     var_dump($fd.'-------'.$frame->fd);
		            if ($ws->isEstablished($fd) && $fd != $frame->fd) {
	                                 $ws->push($fd, $frame->data);
		             }
		      }
    		echo "Message: {$frame->data}\n";
	});

	//监听WebSocket连接关闭事件
	$ws->on('Close', function ($ws, $fd) {
    		echo "client-{$fd} is closed\n";
	});

	$ws->start();
