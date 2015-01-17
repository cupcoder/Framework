<?
	namespace Framework\Request;
	
	class FilesGetter {
		private $keys = [];
		
		public function __construct($keys = []) {
			$this->keys = $keys;
		}
		
		public function has($key) {
			$keys = $this->prepare($key);
			$lnk = &$_FILES;
			foreach ($keys as $one) {
				if (isset($lnk[$one])) $lnk = &$lnk[$one];
				else return false;
			}
			return true;
		}
		
		public function get($key) {
			$keys = $this->prepare($key);
			$lnk = &$_FILES;
			foreach ($keys as $one) {
				if (isset($lnk[$one])) $lnk = &$lnk[$one];
				else return null;
			}
			$fk = $this->keys;
			$fk[] = $key;
			return is_array($lnk) ? new self($fk) : new FileExemplar($fk);
		}
		
		public function each() {
			$for = [];
			$keys;
			if (count($this->keys) == 1) {
				$keys = $this->keys;
				$keys[] = 'error';
			} else {
				$keys = $this->keys;
				$tmp = array_shift($keys);
				array_unshift($keys, 'error');
				array_unshift($keys, $tmp);
			}
			$lnk = &$_FILES;
			foreach ($keys as $one) {
				if (isset($lnk[$one])) $lnk = &$lnk[$one];
			}
			foreach ($lnk as $k => $file) {
				$fk = $this->keys;
				$fk[] = $k;
				if (is_array($file)) {
					$for[$k] = new self($fk);
				} else {
					$for[$k] = new FileExemplar($fk);
				}
			}
			return $for;
		}
		
		public function isFile() {
			return false;
		}
		
		public function isArray() {
			return true;
		}
		
		private function prepare($key) {
			$keys = $this->keys;
			$keys[] = $key;
			if (count($keys) > 1) {
				$tmp = array_shift($keys);
				array_unshift($keys, 'error');
				array_unshift($keys, $tmp);
			} else {
				$this->key[] = 'error';
			}
			return $keys;
		}
	}