<?php
/**
 * 日志处理类
 * @name Log.php
 * @package cwms
 * @category controller
 * @link http://www.changekeji.com
 * @author jianfang
 * @version 1.0
 * @since 2015-10-28
 */
class Log{
	//单例模式
	private static $instance    = NULL;
	//文件句柄
	private static $handle      = NULL;
	//日志开关
	private $log_switch     = NULL;
	//日志相对目录
	private $logFilePath      = NULL;
	//日志文件最大长度，超出长度重新建立文件
	private $LogMaxLen        = NULL;
	//日志文件前缀,入 log_0
	private $logFilePre       = 'log_';
	 
	/**
	 * 构造函数
	 */
	protected function __construct(){//注意：以下是配置文件中的常量，请读者自行更改
		$this->logFilePath   = "log/";
		$this->log_switch  = TRUE;
		$this->LogMaxLen   = 1024 * 1024  * 10;//10M
	}
	 
	/**
	* 单例
	 */
	 public static function getInstance(){
		 if(!self::$instance instanceof self){
	 		self::$instance = new self;
		 }
		 return self::$instance;
	 }
	 
	/**
	*
	* 日志记录
	* @param string $info
	* @param string $type ERROR DEBUG
	* @param bool $isAutoClose 是否自动关闭文件句柄
	*/
	public function log($info,$type = "DEBUG",$isAutoClose = true){
		if($this->log_switch){
			if(self::$handle == NULL){
				$filename = $this->logFilePre . $this->getMaxLogFileSuf();
				self::$handle = fopen($this->logFilePath . $filename, 'a');
			}
			$info = json_encode($info);
			$info = $this->unicodeDecode($info);
			$time = date("Y-m-d H:i:s");
			switch($type){
				case 'DEBUG':
				fwrite(self::$handle, 'DEBUG:' . ' ' . $info . ' ' . $time . chr(13));
				break;
				case 'ERROR':
				fwrite(self::$handle, 'ERROR:' . ' ' . $info . ' ' . $time . chr(13));
				break;
				default:
				fwrite(self::$handle, 'DEBUG:' . ' ' . $info . ' ' . $time . chr(13));
				break;
			}
		}
		if($isAutoClose){
			$this->close();
		}
	}
	
	// 将UNICODE编码后的内容进行解码
	function unicodeDecode($name){
		// 转换编码，将Unicode编码转换成可以浏览的utf-8编码
		$pattern = '/([\w]+)|(\\\u([\w]{4}))/i';
		preg_match_all($pattern, $name, $matches);
		if (!empty($matches)){
			$name = '';
			for ($j = 0; $j < count($matches[0]); $j++){
				$str = $matches[0][$j];
				if (strpos($str, '\\u') === 0){
					$code = base_convert(substr($str, 2, 2), 16, 10);
					$code2 = base_convert(substr($str, 4), 16, 10);
					$c = chr($code).chr($code2);
					$c = iconv('UCS-2', 'UTF-8', $c);
					$name .= $c;
				}
				else{
					$name .= $str;
				}
			}
		}
		return $name;
	}
	
	function test($var){
		static $result = array();
		if(is_array($var) || is_object($var)){
			foreach ($var as $key=>$value){
				$this->test($value);
			}
		}else{
			$result[] = $var;
			return $result;
		}
	}
	
	/**
	* 获取当前日志的最新文档的后缀
	*/
	private function getMaxLogFileSuf(){
			$logFileSuf = null;
			if(is_dir($this->logFilePath)){
				if($dh = opendir($this->logFilePath)){
					while(($file = readdir($dh)) != FALSE){
						if($file != '.' && $file != '..'){
							if(filetype( $this->logFilePath . $file) == 'file'){
								$rs = split('_', $file);
								if($logFileSuf < $rs[1]){
								$logFileSuf = $rs[1];
								}
							}
						}
					}
					if($logFileSuf == NULL){
						$logFileSuf = 0;
					}
					//截断文件
					if( file_exists($this->logFilePath . $this->logFilePre . $logFileSuf) && filesize($this->logFilePath . $this->logFilePre . $logFileSuf) >= $this->LogMaxLen){
						$logFileSuf = intval($logFileSuf) + 1;
					}
					return $logFileSuf;
			}
		}
		return 0;
	}
 
	/**
	* 关闭文件句柄
	*/
	public function close(){
		fclose(self::$handle);
	}
}
