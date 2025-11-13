<?php
namespace Modules\Users\Students\app\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Modules\Users\Students\app\Http\Request\StudentRequest;
use App\Console\Enums\Role;

class StudentApiService implements StudentApiServiceInterface
{
    public function index()
    {
        return User::where('role', Role::STUDENT->value)->get();
    }

    public function store(StudentRequest $request)
    {
        $data = $request->validated();
        $data['role'] = Role::STUDENT->value;
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        return User::create($data);
    }

    public function show($id)
    {
        return User::where('role', Role::STUDENT->value)->findOrFail($id);
    }

    public function update(StudentRequest $request, $id)
    {
        $student = User::where('role', Role::STUDENT->value)->findOrFail($id);
        $data = $request->validated();
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        $student->update($data);
        return $student;
    }

    public function destroy($id)
    {
        $student = User::where('role', Role::STUDENT->value)->findOrFail($id);
        $student->delete();
        return true;
    }
}
