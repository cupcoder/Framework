<?
	namespace Framework;
	
	class Exception extends \Exception {
		private static $_handle;
		private $rcode = 500;
		
		public function __construct($msg, $code = 500) {
			$code = intval($code);
			if ($code < 100 || $code >= 600) {
				throw new Exception('Invalid http code: ' . $code);
			}
			$this->rcode = $code;
			parent::__construct($msg);
		}
		
		
		public function show() {
			if (self::$_handle === null) {
				http_response_code($this->code);
				echo $this->rcode;
			} else {
				call_user_func_array(self::$_handle, [
					$this->rcode,
					$this->getMessage(),
					$this->getTraceAsString()
				]);
			}
			die();
		}
		
		public static function handle($handle) {
			self::$_handle = $handle;
		}
	}