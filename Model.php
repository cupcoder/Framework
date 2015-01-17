<?
	namespace Framework;
	
	class Model implements \JsonSerializable {
		
		protected $tablename,
				  $key = 'id',
				  $storage = [],
				  $changed = [];
		
		public function __construct($tablename, $key = 'id') {
			$this->tablename = str_replace(['\'', '"', '`'], '', $tablename);
			$this->key = str_replace(['\'', '"', '`'], '', $key);
		}
		
		public function id($value) {
			$select = $this->inj('db')->fetchAssoc('SELECT * FROM `' . $this->tablename . '` WHERE `' . $this->key . '` = :value LIMIT 0, 1', ['value' => $value]);
			if ($select !== false) {
				$this->storage = $select;
				return $this;
			}
			return null;
		}
		
		public function inj($key) {
			return Injector\Adapter::shared()->get($key);
		}
		
		public function save() {
			$keys = array_unique($this->changed);
			$update = isset($this->storage[$this->key]);
			if (count($keys) != 0) {
				if ($update) {
					$query = 'UPDATE `' . $this->tablename . '` SET ';
					$params = [$this->key => $this->storage[$this->key]];
				} else {
					$query = 'INSERT INTO `' . $this->tablename . '` SET ';
				}
				foreach ($keys as $key) {
					$query .= '`' . $key . '` = :' . $key . ($key !== end($keys) ? ', ' : '');
					$params[$key] = $this->storage[$key];
				}
				if ($update)
					$query .= ' WHERE `' . $this->key . '` = :' . $this->key;
				$resp = $this->inj('db')->prepare($query)->execute($params);
				if ($resp) {
					$this->changed = [];
					$this->storage[$this->key] = $this->inj('db')->lastInsertId();
				}
				return $resp;
			}
			return $update;
		}
		
		public function __set($key, $value) {
			if ($key !== $this->key) {
				$this->storage[$key] = $value;
				$this->changed[] = $key;
			}
		}
		
		public function __get($key) {
			if (isset($this->storage[$key])) {
				return $this->storage[$key];
			}
		}
		
		public function jsonSerialize() {
			return $this->storage;
		}
	}