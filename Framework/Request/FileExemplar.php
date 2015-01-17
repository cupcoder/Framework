<?
	namespace Framework\Request;
	
	class FileExemplar {
		private $name,
				$error,
				$tmp_name,
				$size;
				
		public function __construct($keys) {
			$keys = $this->prepare($keys, 'error');
			$lnk = &$_FILES;
			foreach ($keys as $one) {
				if (isset($lnk[$one])) $lnk = &$lnk[$one];
			}
			$this->error = $lnk;
			$keys = $this->prepare($keys, 'name');
			$lnk = &$_FILES;
			foreach ($keys as $one) {
				if (isset($lnk[$one])) $lnk = &$lnk[$one];
			}
			$this->name = $lnk;
			$keys = $this->prepare($keys, 'tmp_name');
			$lnk = &$_FILES;
			foreach ($keys as $one) {
				if (isset($lnk[$one])) $lnk = &$lnk[$one];
			}
			$this->tmp_name = $lnk;
			$keys = $this->prepare($keys, 'size');
			$lnk = &$_FILES;
			foreach ($keys as $one) {
				if (isset($lnk[$one])) $lnk = &$lnk[$one];
			}
			$this->size = $lnk;
		}
		
		public function error() {
			return $this->error;
		}
		
		public function hasError() {
			return $this->error === 0;
		}
		
		public function name() {
			return $this->name;
		}
		
		public function tmp() {
			return $this->tmp_name;
		}
		
		public function size() {
			return $this->size;
		}
		
		public function move($path) {
			return move_uploaded_file($this->tmp_name, $path);
		}
		
		public function isFile() {
			return true;
		}
		
		public function isArray() {
			return false;
		}
		
		private function prepare($keys, $tag) {
			if (count($keys) > 1) {
				$tmp = array_shift($keys);
				array_unshift($keys, $tag);
				array_unshift($keys, $tmp);
			} else {
				$this->key[] = $tag;
			}
			return $keys;
		}
	}