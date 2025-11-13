<?php
namespace Modules\Users\Students\app\Services;

use Modules\Users\Students\app\Http\Request\StudentRequest;

interface StudentApiServiceInterface
{
    public function index();
    public function store(StudentRequest $request);
    public function show($id);
    public function update(StudentRequest $request, $id);
    public function destroy($id);
}
