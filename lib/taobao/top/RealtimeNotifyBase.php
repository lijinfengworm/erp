<?php
/**
 * RealtimeNotifyBase 类封装了“TOP 主动业务推送服务”的基本处理逻辑。
 * 
 * 参考资料：
 * 
 *   TOP - Stream API介绍
 *   http://dev.open.taobao.com/dev/index.php/Stream_API%E4%BB%8B%E7%BB%8D
 * 
 *   TOP - 主动通知业务 API（增量 API）
 *   http://open.taobao.com/doc/api_cat_detail.htm?cat_id=39&category_id=102
 * 
 * 设计考虑：
 * 
 *   1. 为避免由于网络等原因导致处理中断后的长时间空闲，本程序应安排以一个比较小的
 *      周期来启动运行（比如 1 分钟）。
 * 
 *   2. 程序运行起来后，通过加锁阻止后面启动的实例，直到一定时间后（缺省为 1 小时）
 *      解锁，允许第二个实例运行，将前一个长连接踢出，最终将导致前一个实例退出。
 * 
 * 一般用法：
 * 
 *   RealtimeNotifyBase 的设计目标是封装底层的处理逻辑，它提供了一种扩展架构，其本身
 *   并不适合直接使用。
 * 
 *   一般的应用程序要使用这个功能，需要对其进行扩展，补充具体的业务消息处理逻辑（包括
 *   调用“主动通知业务 API”补取丢失的业务消息）。
 * 
 *   RealtimeNotifySimple 是一个最简单的扩展例子，事实上它现在除了把业务消息包显示出
 *   来，别的什么也没有做。但你可以修改它，加入你自己的处理逻辑。
 * 
 *   RealtimeNotifyForward 这个扩展类，可以对业务消息包进行转发，相当于是把从长连接方式
 *   收到的业务消息转化为回调地址的通知方式。如果要用它，你需要自己另外实现 web 程序来
 *   接收并处理转发的内容。
 * 
 *   以 RealtimeNotifyForward 为例，长连接接收程序一般是这样的：
 * 
 *      $rn = new RealtimeNotifyForward();
 *      $rn->setConfig( 'app_key', '这是 appkey' );
 *      $rn->setConfig( 'secret', '这个……你懂的 :)' );
 *      $rn->setConfig( 'forward_host', 'localhost' );
 *      $rn->setConfig( 'forward_path', '/forward_notify' );
 *      $rn->setConfig( 'forward_headers', 'X_REQUESTED_WITH: RealtimeNotifyForward' );
 *      $rn->run();
 * 
 *   然后在操作系统里配置后台任务（Linux 里可以用 cron），每 1 分钟启动执行这个程序。
 *   调试的话可以在命令行以 CLI 方式运行。
 */
class RealtimeNotifyBase
{
	protected $config;
	protected $fnLock;
	protected $fpLock;
	protected $tsUnlock;

	/**
	 * 设置配置参数。
	 */
	public function setConfig( $name, $value )
	{
		if ( ! is_array( $this->config ) ) {
			$this->config = array();
		}
		$oldvalue = empty($this->config[$name])?FALSE:$this->config[$name];
		$this->config[$name] = $value;
		return $oldvalue;
	}

	public function __construct()
	{
		// TOP 服务器相关参数
		$this->setConfig( 'server_host', 'stream.api.taobao.com' );
		$this->setConfig( 'server_port', 80 );
		$this->setConfig( 'server_path', '/stream' );
		$this->setConfig( 'server_timeout', 30 );

		// 本地运行参数

		// 本程序需要产生的文件都将写在这个目录下
		$this->setConfig( 'writable_dir', dirname(__FILE__) );
		// 文件锁
		$this->setConfig( 'lock_file', 'taobao_socket.lock' );
		$this->setConfig( 'lock_time', 60*60*2 );

		// 应用标识
		$this->setConfig( 'app_key', '--------' );
		$this->setConfig( 'secret', '--------------------------------' );

		// 针对特定用户的数据通道
		$this->setConfig( 'user_id', NULL );
	}

	/**
	 * 程序主循环。该方法返回后，程序应该结束运行。
	 */
	public function run()
	{
		// 加锁
		if ( ! $this->enterLock() ) {
			return;
		}

		$fp = @fsockopen( $this->config['server_host'], $this->config['server_port'], $errno, $errstr, 30 );
		if ( ! $fp ) {
			$this->outputDebug( "[errno:{$errno}] {$errstr}" );
			return;
		}

		stream_set_timeout( $fp, intval( $this->config['server_timeout'] * 2.5 ) );

		$timestamp = date( 'Y-m-d H:i:s', time() );

		$str = "app_key{$this->config['app_key']}timestamp{$timestamp}";
		$content = "app_key=" . $this->config['app_key'] ."&timestamp=" . urlencode($timestamp);
		if ( !empty($this->config['user_id']) ) {
			$str .= "user{$this->config['user_id']}";
			$content .= "&user={$this->config['user_id']}";
		}
		$sign = strtoupper( md5( "{$this->config['secret']}{$str}{$this->config['secret']}", false ) );
		$content .= "&sign=" . $sign;

		$contentLength = strlen($content);

		$out = "POST {$this->config['server_path']} HTTP/1.1\r\n";
		$out .= "Host: {$this->config['server_host']}\r\n";
		$out .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$out .= "Content-Length: {$contentLength}\r\n";
		$out .= "Connection: Keep-Alive\r\n\r\n";
		$out .= $content;

		$len = fwrite( $fp, $out );
		$this->outputDebug( "发送了 {$len} 字节\r\n{$out}" );

		while ( $len ) {
			$line = fgets( $fp );
	
			$meta = stream_get_meta_data( $fp );
			if ( $meta['timed_out'] ) {
				// 如果超时未读到任何数据，则认为网络故障，本程序退出
				$this->outputDebug( "连接接收超时" );
				break;
			}

			if ( $meta['eof'] ) {
				$this->outputDebug( "连接被关闭" );
				break;
			}

			if ( strlen($line) == 0 ) {
				$this->outputDebug( "没有收到数据？！" );
				break;
			}

			//$this->outputDebug( $line );

			// 刷新锁状态
			if($this->checkLock()){
                break;
            }

			// 如果是 json 数据，则处理
			if ( $line[0] == '{' ) {

				// 对“整数值”增加双引号，避免 json_decode 时溢出
				$line = preg_replace( '/([":])([0-9]+)([,}])/isU', '${1}"${2}"${3}', $line );

				if ( $this->dispatchPacket( $line ) ) {
					break;
				}
			}
		}
		fclose($fp);
	}

	/**
	 * 输出调试信息。
	 * 正式上线的时候，可以考虑把这里的语句注释掉。
	 */
	protected function outputDebug( $txt )
	{
		$ts = date( 'Y-m-d H:i:s', time() );
        //echo "[{$ts}] {$txt}";
	}

	/**
	 * 加锁，阻止本程序的第二个实例启动运行。
	 */
	private function enterLock()
	{
		$this->outputDebug( "启动加锁" );

		$this->fnLock = $this->config['writable_dir'] . DIRECTORY_SEPARATOR . $this->config['lock_file'];
		if ( !empty($this->config['user_id']) ) {
			$this->fnLock .= ".{$this->config['user_id']}";
		}

		$this->fpLock = fopen( $this->fnLock, 'w+' );
		if ( $this->fpLock ) {
			if ( flock( $this->fpLock, LOCK_EX | LOCK_NB ) ) {
				$this->tsUnlock = time() + $this->config['lock_time'];
				return true;
			}
			fclose( $this->fpLock );
			$this->fpLock = null;
		}

		$this->outputDebug( "    加锁失败" );

		return false;
	}

	/**
	 * 刷新锁状态。
	 * 如果超过锁定期，则解锁，允许本程序的第二个实例启动运行。后执行的程序会导致先前的长连接中断，进而使程序退出。
	 */
	private function checkLock()
	{
		
        
		if ( time() < $this->tsUnlock - 120 ) {
            //假如离程序最长运行时间还很长
            //文件句柄必须存在 不然退出
            if($this->fpLock){
                return False;
            }else{
                return True;
            }
		}
        //假如离程序最长运行时间不远了或者已经超出最长运行时间。就先释放文件链接。
        //如果还在运行时间内 这个进程继续运行，
        //会有新进程代替当前进程继续连接淘宝服务器，
        if ( $this->fpLock ) {
			$this->outputDebug( "解锁" );
            flock( $this->fpLock, LOCK_UN );
            fclose( $this->fpLock );
            $this->fpLock = null;
            unlink( $this->fnLock );
		}
        //
        if(time() < $this->tsUnlock){
            return FALSE;
        }
        return TRUE;
	}

	/**
	 * 根据消息包的类型分别处理。
	 * @param string $json - json 字符串。
	 * @return boolean - true 表示需要关闭连接并退出处理程序。
	 */
	private function dispatchPacket( $json )
	{
		$json = json_decode( $json, true );
        
		if ( ! isset($json['packet']) ) {
			return false;
		}
		$packet = $json['packet'];
		$method = "onPacket{$packet['code']}";
		if ( method_exists( $this, $method ) ) {
            $this->outputDebug($packet['code']);
			return $this->$method( $packet['msg'] );
		}
		$this->outputDebug( "未知的消息" );
		return false;
	}

	protected function onPacket200( $msg )
	{
		// $this->outputDebug( "连接成功" );
		// app端行为：准备读取数据流
	}

	protected function onPacket201( $msg )
	{
		// $this->outputDebug( "服务端发的心跳包，防止连接被网络设备断开" );
		// app端行为：无
	}

	protected function onPacket202( $msg )
	{
		// $this->outputDebug( "业务消息包" );
		// app端行为：取出业务消息包的内容，并作业务逻辑处理
		$this->doBizMessage( $msg );
        
	}

	protected function onPacket203( $msg )
	{
		// $this->outputDebug( "服务端在发送业务消息包时第一次发送失败的时间" );
		// app端行为：begin表示最后发送失败的时间，end表示新的连接被接收的当前时间，app需要把这段时间内丢失的消息包通过增量api获取到
		$begin = intval( $msg['begin'] / 1000 );
		$end = intval( $msg['end'] / 1000 );
		$this->doRecoverMessage( $begin, $end );
	}

	protected function onPacket101( $msg )
	{
		// $this->outputDebug( "连接到达最大时间" );
		// app端行为：客户端主动重连
		return true; // 关闭连接并退出
	}

	protected function onPacket102( $msg )
	{
		// $this->outputDebug( "服务端在升级" );
		// app端行为：msg表示服务端升级大概需要的时间，单位：s，app在这段时间之后重新连接服务端，并且使用增量api把这段时间内丢失的消息获取到
		return true; // 关闭连接并退出
	}

	protected function onPacket103( $msg )
	{
		// $this->outputDebug( "由于某些原因服务端出现了一些问题，需要断开客户端" );
		// app端行为：msg表示建议app在多少s之后发起新的请求连接，app可以选择马上发起新的连接请求，也可以选择在一段时间后发起连接请求
		return true; // 关闭连接并退出
	}

	protected function onPacket104( $msg )
	{
		// $this->outputDebug( "由于客户端发起了重复的连接请求，服务端会把前一个连接主动断开" );
		// app端行为：在新的连接上接收消息，并且把前一个连接上剩余的消息接收完
        return TRUE;
	}

	protected function onPacket105( $msg )
	{
		// $this->outputDebug( "服务端有大量的消息积压" );
		// app端行为：app端需要检查一下网络环境，或者接收消息和处理消息的线程没有分开
	}

	/**
	 * 处理业务消息
	 * @param array $msg - 业务消息包
	 */
	protected function doBizMessage( $msg )
	{
		// 需要在扩展类中实现相应的处理逻辑
	}

	/**
	 * 通过增量 API 补充提取丢失的业务消息
	 * @param int $begin - 时间段起点
	 * @param int $end - 时间段终点
	 */
	protected function doRecoverMessage( $begin, $end )
	{
		// 需要在扩展类中实现相应的处理逻辑
	}
}

/**
 * RealtimeNotifyTask 是一个使用 为task 使用的 RealtimeNotifyBase 的简单示例。
 * 
 * 在 RealtimeNotifyBase 的基础上，RealtimeNotifySimple 只是简单地引入了业务消息处理逻辑，它
 * 没有把处理过程分到另外的线程里去单独执行。在预期业务消息数量比较稀少，处理过程耗时不会太
 * 长的情况下，这种用法比较简单有效。
 */
class RealtimeNotifyTask extends RealtimeNotifyBase
{
	public function __construct(&$task)
	{
        $this->task = $task;
		parent::__construct();
	}
    protected function outputDebug($txt) {
        $ts = date( 'Y-m-d H:i:s', time() );
        if(!empty($txt)){
            $this->task->log($ts.':'.$txt);
        }
    }
    protected function doBizMessage( $msg )
	{
		
		// 在这里处理业务消息 增加到更新队列
		if($msg['notify_item']['num_iid']){
            $this->outputDebug( TaobaoItemUpdateUtil::getInstance(TRUE)->addUpdated($msg['notify_item']['num_iid']));
            $this->outputDebug( $msg['notify_item']['num_iid'].'加入更新商品队列');
        }
	}
	protected function doRecoverMessage( $begin, $end )
	{
		$this->outputDebug( "RealtimeNotifySimple::doRecoverMessage()" );
		// 在这里通过增量 API 提取指定时间段的业务消息
		$begin = date( 'Y-m-d H:i:s', $begin );
		$end = date( 'Y-m-d H:i:s', $end );
		$this->outputDebug( "    begin[{$begin}] end[{$end}]" );
	}
}

