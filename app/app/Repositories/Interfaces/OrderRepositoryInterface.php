<?php

namespace App\Repositories\Interfaces;

interface OrderRepositoryInterface
{
    public function getAll(array $filters, $sortBy, $sortDirection);
    public function findById($id);
    public function create(array $data);
    public function getPendingOrders();
    public function getOrdersByStatus($status);
    public function attachConcessions($order, $concessions);
    public function delete($id);
}
