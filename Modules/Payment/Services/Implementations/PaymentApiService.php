<?php

namespace Modules\Payment\Services\Implementations;

use Modules\Payment\app\Models\Payment;
use Modules\Payment\Services\PaymentApiServiceInterface;

class PaymentApiService implements PaymentApiServiceInterface
{
    public function list(array $filters = [])
    {
        return Payment::query()->paginate(15);
    }

    public function create(array $data)
    {
        return Payment::create($data);
    }

    public function update(int $id, array $data)
    {
        $subject = $this->find($id);
        $subject->update($data);
        return $subject;
    }

    public function delete(int $id)
    {
        $subject = $this->find($id);
        return $subject->delete();
    }

    public function find(int $id)
    {
        return Payment::findOrFail($id);
    }
}
