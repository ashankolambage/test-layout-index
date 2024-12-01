<?php

namespace App\Repositories;

use App\Models\Concession;
use App\Repositories\Interfaces\ConcessionRepositoryInterface;

class ConcessionRepository implements ConcessionRepositoryInterface
{
    protected $model;

    public function __construct(Concession $concession)
    {
        $this->model = $concession;
    }

    public function getAll()
    {
        return $this->model->all();
    }

    public function findById($id)
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $concession = $this->findById($id);
        $concession->update($data);
        return $concession;
    }

    public function delete($id)
    {
        $concession = $this->findById($id);
        return $concession->delete();
    }
}
