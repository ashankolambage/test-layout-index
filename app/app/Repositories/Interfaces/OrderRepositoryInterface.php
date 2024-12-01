<?php

namespace App\Repositories\Interfaces;

interface OrderRepositoryInterface
{
    public function create(array $data);
    public function getAll();
    public function findById($id);
}
