<?php

namespace App\Services;

use App\Constants\OrderStatus;
use App\Events\OrderSentToKitchen;
use App\Jobs\UpdateOrderStatus;
use App\Repositories\Interfaces\ConcessionRepositoryInterface;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use App\Traits\ResponseHelper;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class OrderService
{
    protected OrderRepositoryInterface $orderRepository;
    protected ConcessionRepositoryInterface $concessionRepository;
    use ResponseHelper;

    public function __construct(OrderRepositoryInterface $orderRepository, ConcessionRepositoryInterface $concessionRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->concessionRepository = $concessionRepository;
    }

    public function getAllOrders()
    {
        return $this->orderRepository->getAll([],'','');
    }

    public function fetchOrders($request)
    {
        $status = $request->status;
        $sortBy = 'send_to_kitchen_time';
        $sortDirection = 'asc';

        if ($status && !in_array($status, OrderStatus::getAllowedStatuses())) {
            return $this->response('error', 'Invalid status provided', [], 500);
        }

        if ($status) {
            $orders = $this->orderRepository->getAll(['status' => $status], $sortBy, $sortDirection);
        } else {
            $orders = $this->orderRepository->getAll([], $sortBy, $sortDirection);
        }

        return $this->response('success', '', ['orders' => $orders], 200);
    }

    public function storeOrder($request)
    {
        try {
            $totalCost = 0;

            foreach ($request->concessions as $concessionData) {
                $concession = $this->concessionRepository->findById($concessionData['concession_id']);
                
                if ($concession) {
                    $totalCost += $concession->price * $concessionData['quantity'];
                } else {
                    return $this->response('error', 'Concession not found', [], 404);
                }
            }

            $order = $this->orderRepository->create([
                'user_id' => auth()->id(),
                'status' => OrderStatus::PENDING,
                'send_to_kitchen_time' => $request->send_to_kitchen_time,
                'total_cost' => $totalCost,
            ]);

            foreach ($request->concessions as $concessionData) {
                $concession = $this->concessionRepository->findById($concessionData['concession_id']);
                
                if ($concession) {
                    $totalCost += $concession->price * $concessionData['quantity'];

                    $order->concessions()->attach($concessionData['concession_id'], [
                        'quantity' => $concessionData['quantity'],
                        'price' => $concession->price,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                } else {
                    return $this->response('error', 'Concession not found', [], 404);
                }
            }

            $sendToKitchenTime = Carbon::parse($request->input('send_to_kitchen_time'));
            $delay = $sendToKitchenTime->diffInSeconds(now());

            UpdateOrderStatus::dispatch($order->id, $request->send_to_kitchen_time)->delay(now()->addSeconds($delay));

            return $this->response('success', 'Order created successfully!', [], 200);
        } catch (\Throwable $th) {
            Log::error('Error creating order: ' . $th->getMessage());
            return $this->response('error', 'An error occurred while creating the order.', [], 500);
        }
    }

    public function updateOrderStatus($request, $id)
    {
        try {
            $newStatus = $request->input('status');
            $order = $this->orderRepository->findById($id);
            
            if (!$order) {
                return $this->response('error', 'Order not found.', [], 404);
            }

            if (!in_array($newStatus, OrderStatus::getAllowedStatuses())) {
                return $this->response('error', 'Invalid status provided', [], 500);
            }

            if ($newStatus === OrderStatus::IN_PROGRESS) {
                $order->send_to_kitchen_time = Carbon::now();
            }

            $order->status = $newStatus; 
            $order->save();

            event(new OrderSentToKitchen($order));

            return $this->response('success', 'Order updated successfully!', [], 200);
        } catch (\Throwable $th) {
            Log::error('Error updating order: ' . $th->getMessage());
            return $this->response('error', 'An error occurred while updating the order.', $th->getMessage(), 500);
        }
    }

    public function viewOrder($id)
    {
        try {
            $order = $this->orderRepository->findById($id)
                ->load(['concessions' => function ($query) {
                    $query->withPivot('quantity', 'price')->withTrashed();
                }]);

            return $order;
        } catch (\Throwable $th) {
            Log::error('Error fetching order: ' . $th->getMessage());
        }
    }

    public function deleteOrder($id)
    {
        try {
            $this->orderRepository->delete($id);
            return $this->response('success', 'Order deleted successfully!', [], 200);
        } catch (\Throwable $th) {
            Log::error('Error deleting order: ' . $th->getMessage());
            return $this->response('error', 'An error occurred while deleting the order.', $th->getMessage(), 500);
        }
    }
}
