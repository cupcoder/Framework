<?
	namespace Framework\Response;
	
	class Cookie {
		private $name,
				$value, 
				$expire = 0,
				$path = '/', 
				$domain, 
				$secure = false, 
				$httponly = false;
		
		public function __construct($name, $value = null, $expire = 0, $path = '/', $domain = null, $secure = false, $httponly = false) {
			$this->name = $name;
			$this->value = $value;
			$this->expire = intval($expire);
			$this->path = $path;
			$this->domain = $domain;
			$this->secure = (bool)($secure);
			$this->httponly = (bool)($httponly);
		}
		
		public function getName() {
			return $this->name;
		}
		
		public function httponly($on = true) {
			$this->httponly = (bool)($on);
			return $this;
		}
		
		public function secure($on = true) {
			$this->secure = (bool)($on);
			return $this;
		}
		
		public function exec() {
			setcookie($this->name, $this->value, $this->expire, $this->path, $this->domain, $this->secure, $this->httponly);
		}
	}