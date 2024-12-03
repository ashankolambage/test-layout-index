<?php

namespace App\Repositories;

use App\Models\Order;
use App\Repositories\Interfaces\OrderRepositoryInterface;

class OrderRepository implements OrderRepositoryInterface
{
    protected $model;

    public function __construct(Order $order)
    {
        $this->model = $order;
    }

    public function getAll(array $filters = [], $sortBy = null, $sortDirection = 'asc')
    {
        $query = $this->model->newQuery();

        foreach ($filters as $key => $value) {
            $query->where($key, $value);
        }

        if ($sortBy) {
            $query->orderBy($sortBy, $sortDirection);
        }

        return $query->get();
    }

    public function findById($id)
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function getPendingOrders()
    {
        return $this->model->where('status', 'Pending')->get();
    }

    public function getOrdersByStatus($status)
    {
        return $this->model->where('status', $status)->get();
    }

    public function attachConcessions($order, $concessions)
    {
        foreach ($concessions as $concessionData) {
            $order->concessions()->attach($concessionData['concession_id'], [
                'quantity' => $concessionData['quantity'],
                'price' => $concessionData['price'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function delete($id)
    {
        $order = $this->findById($id);
        return $order->delete();
    }
}
