<?php

namespace App\Providers;

use App\Permission;
use App\Repositories\Role\RoleRepository;
use App\Repositories\User\UserRepository;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RouteServiceProvider extends ServiceProvider {
	/**
	 * This namespace is applied to the controller routes in your routes file.
	 *
	 * In addition, it is set as the URL generator's root namespace.
	 *
	 * @var string
	 */
	protected $namespace = 'App\Http\Controllers';

	/**
	 * Define your route model bindings, pattern filters, etc.
	 *
	 * @param  \Illuminate\Routing\Router  $router
	 * @return void
	 */
	public function boot(Router $router) {
		parent::boot($router);
	}

	/**
	 * Define the routes for the application.
	 *
	 * @param  \Illuminate\Routing\Router  $router
	 * @return void
	 */
	public function map(Router $router) {
		$router->group(['namespace' => $this->namespace], function ($router) {
			require app_path('Http/routes.php');
		});
	}

	/**
	 * @param Router $router
	 */
	private function bindUser(Router $router) {
		$this->bindUsingRepository($router, 'user', UserRepository::class);
	}

	private function bindRole(Router $router) {
		$this->bindUsingRepository($router, 'role', RoleRepository::class);
	}

	private function bindPermission($router) {
		$router->model('permission', Permission::class);
	}

	private function bindUsingRepository($router, $entity, $repositoryClass, $method = 'find') {
		$router->bind($entity, function ($id) use ($repositoryClass, $method) {
			if ($object = app($repositoryClass)->$method($id)) {
				return $object;
			}

			throw new NotFoundHttpException;
		});
	}
}
