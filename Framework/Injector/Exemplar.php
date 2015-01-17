<?
	namespace Framework\Injector;
	
	class Exemplar {
		private $name,
				$handle,
				$executed;
				
		public function __construct($name, $handle) {
			$this->name = $name;
			$this->handle = $handle;
		}
		
		public function exec() {
			if ($this->executed === null) {
				$this->executed = call_user_func($this->handle);
			}
			
			return $this->executed;
		}
	}