<?
	namespace Framework;
	
	class Controller {
		public function json($data, $code = 200) {
			return $this->response->code($code)->header('Content-type', 'application/json')->body(json_encode($data));
		}
		
		public function redirect($link, $code = 302) {
			return $this->response->code($code)->header('Location', $link);
		}
		
		public function error($code, $msg = "Controller called exception.") {
			return function() use ($msg, $code){ 
				throw new Exception($msg, $code);
			};
		}
		
		public function __get($name) {
			$injector = Injector\Adapter::shared();
			if ($injector->has($name)) {
				return $injector->get($name);
			}
		}
	}