<?php

namespace Modules\Users\Accountant\Services\Implementations;
use Modules\Users\Accountant\Services\AccountantApiServiceInterface;
use Modules\Users\User\App\Models\User;
use Illuminate\Support\Str;

class AccountantApiService implements AccountantApiServiceInterface
{
	use \Modules\Common\Services\CrudServiceTrait;

	protected string $modelClass = User::class;

	public function list(array $filters = [])
	{
		$class = $this->modelClass;
		return $class::query()->where('role', 'accountant')->orderBy('id', 'asc')->get();
	}

	public function create(array $data)
	{
		$data['role'] = 'accountant';
		if (empty($data['password'])) {
			$data['password'] = Str::random(10);
		}
		$user = User::create($data);
		if (method_exists($user, 'assignRole')) {
			$user->assignRole('accountant');
		}
		return $user;
	}
}
