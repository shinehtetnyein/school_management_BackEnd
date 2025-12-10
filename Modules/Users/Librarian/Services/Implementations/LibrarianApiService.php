<?php

namespace Modules\Users\Librarian\Services\Implementations;
use Modules\Users\Librarian\Services\LibrarianApiServiceInterface;
use Modules\Users\User\App\Models\User;
use Illuminate\Support\Str;

class LibrarianApiService implements LibrarianApiServiceInterface
{
	use \Modules\Common\Services\CrudServiceTrait;

	protected string $modelClass = User::class;

	public function list(array $filters = [])
	{
		$class = $this->modelClass;
		return $class::query()->where('role', 'librarian')->orderBy('id', 'asc')->get();
	}

	public function create(array $data)
	{
		$data['role'] = 'librarian';
		if (empty($data['password'])) {
			$data['password'] = Str::random(10);
		}
		$user = User::create($data);
		if (method_exists($user, 'assignRole')) {
			$user->assignRole('librarian');
		}
		return $user;
	}
}
