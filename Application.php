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