<?php

namespace Modules\Common\Services;

trait CrudServiceTrait
{
    /**
     * Child classes must set this to the full model class name.
     *
     * @var string
     */
    protected string $modelClass;

    public function list(array $filters = [])
    {
        $class = $this->modelClass;
        return $class::query()->orderBy('id', 'asc')->get();
    }

    public function create(array $data)
    {
        $class = $this->modelClass;
        return $class::create($data);
    }

    public function show(int $id)
    {
        $class = $this->modelClass;
        return $class::findOrFail($id);
    }

    public function update(int $id, array $data)
    {
        $class = $this->modelClass;
        $model = $class::findOrFail($id);
        $model->update($data);
        return $model;
    }

    public function delete(int $id)
    {
        $class = $this->modelClass;
        $model = $class::findOrFail($id);
        return $model->delete();
    }
}
