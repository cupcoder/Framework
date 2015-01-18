<?
	namespace Framework;
	
	class Application {		
		public function get($route, $handle) {
			return Router\Adapter::shared()->add('GET', $route, $handle);
		}
		
		public function post($route, $handle) {
			return Router\Adapter::shared()->add('POST', $route, $handle);
		}
		
		public function all($route, $handle) {
			return Router\Adapter::shared()->add('ALL', $route, $handle);
		}
		
		public function set($name, $handle, $need = false) {
			Injector\Adapter::shared()->set($name, $handle, $need);
		}

		public function register($types = []) {
			if (is_array($types)) {
				foreach ($types as $type => $path) {
					switch ($type) {
						case 'controllers':
							Router\Adapter::shared()->register($path);
							break;
						case 'models':
						case 'classes':
							if (is_array($path)) {
								foreach ($path as $p) {
									spl_autoload_register(function($class) use ($p) {
										if (is_file($p . '/' . $class . '.php')) {
											require_once $p . '/' . $class . '.php';
										}
									});
								}
							}else {
								spl_autoload_register(function($class) use ($path) {
									if (is_file($path . '/' . $class . '.php')) {
										require_once $path . '/' . $class . '.php';
									}
								});
							} 
							break;
					}
				} 
			}
		}
		
		public function error($handle) {
			Exception::handle($handle);
		}
		
		public function run() {
			try {
				$injector = Injector\Adapter::shared();
				$req = $injector->get('request');
				$res = $injector->get('response');
				$res->append(Router\Adapter::shared()->exec($req->getMethod(), $req->getURL()))->show();
			} catch (\Exception $e) {
				if ($e instanceof Exception) {
					$e->show();
				} else {
					try {
						throw new Exception($e->getMessage());
					} catch (Exception $e) {
						$e->show();
					}
				}
			}
		}
	}