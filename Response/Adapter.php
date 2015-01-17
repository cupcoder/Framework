<?
	namespace Framework\Response;
	
	class Adapter {
		private $code = 200,
				$body,
				$headers = [],
				$cookies = [];
		
		public function __construct($body = null, $code = 200) {
			$this->body($body);
			$this->code($code);
		}
		
		public function code($code) {
			$code = intval($code);
			if ($code < 100 || $code >= 600) {
				throw new \Framework\Exception('Invalid http code: ' . $code);
			}
			
			$this->code = $code;
			
			return $this;
		}
		
		public function header($key, $value = null) {
			if ($value === null && is_array($key)) {
				$this->headers = array_merge($this->headers, $key);
			} else {
				$this->headers[$key] = $value;
			}
			return $this;
		}
		
		public function cookie(Cookie $cookie) {
			$this->cookies[$cookie->getName()] = $cookie;
			return $this;
		}
		
		public function body($body) {
			if (is_array($body)) {
				throw new \Framework\Exception('Response body can not be array.');
			} elseif (is_object($body) && !($body instanceof \Closure)) {
				throw new \Framework\Exception('Response body can not be object.');
			}
			
			$this->body = $body;
			
			return $this;
		}
		
		// Getters
		public function export() {
			return [
				'code' => $this->code,
				'body' => $this->body,
				'headers' => $this->headers,
				'cookies' => $this->cookies
			];
		}
		
		public function append($obj) {
			if (is_object($obj) && $obj instanceof Adapter) {
				$data = $obj->export();
				$this->code($data['code']);
				$this->headers = array_merge($this->headers, $data['headers']);
				$this->cookies = array_merge($this->cookies, $data['cookies']);
				if ($data['body'] !== null) {
					$this->body($data['body']);
				}
			}else {
				$this->body($obj);
			}
			return $this;
		}
		
		public function show() {
			http_response_code($this->code);
			if (count($this->headers) != 0) {
				foreach ($this->headers as $name => $value) {
					header($name . ': ' . $value, true, $this->code);
				}
			}
			if (count($this->cookies) != 0) {
				foreach ($this->cookies as $cookie) {
					if ($cookie !== null)
						$cookie->exec();
				}
			}
			if ($this->body !== null) {
				if (is_object($this->body)) {
					call_user_func($this->body);
				} else {
					echo $this->body;
				}
			}
		}
	}