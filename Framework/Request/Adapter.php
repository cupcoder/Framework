<?
	namespace Framework\Request;
	
	class Adapter extends Getter {
		private $get,
				$post,
				$cookies,
				$headers,
				$files;
		
		public function __construct() {
			$this->get = new Getter($_GET);
			$this->post = new Getter($_POST);
			$this->cookies = new Getter($_COOKIE);
			$this->headers = new HeadersGetter($_SERVER);
			$this->files = new FilesGetter();
			parent::__construct($_REQUEST);
		}
		
		public function getURL($full = false) {
			return $_SERVER['DOCUMENT_URI'] . ($full === true ? $this->getQS() : '');
		}
		
		public function getQS() {
			return $_SERVER['QUERY_STRING'];
		}
		
		public function getMethod() {
			return $_SERVER['REQUEST_METHOD'] === 'POST' ? 'POST' : 'GET';
		}
		
		public function __get($key) {
			if (in_array($key, ['get', 'post', 'cookies', 'headers', 'files'])) {
				return $this->{$key};
			}
		}
	}