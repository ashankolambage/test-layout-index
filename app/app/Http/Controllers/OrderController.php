<?php

namespace App\Http\Controllers;

use App\Constants\OrderStatus;
use App\Repositories\Interfaces\ConcessionRepositoryInterface;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    protected $orderRepository;
    protected $concessionRepository;

    public function __construct(OrderRepositoryInterface $orderRepository, ConcessionRepositoryInterface $concessionRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->concessionRepository = $concessionRepository;
    }

    public function index()
    {
        $orders = $this->orderRepository->getAll();
        return Inertia::render('Orders/Index', ['orders' => $orders]);
    }

    public function create()
    {
        $concessions = $this->concessionRepository->getAll();
        return Inertia::render('Orders/Create', ['concessions' => $concessions]);
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'concessions' => 'required|array',
                'concessions.*.concession_id' => 'exists:concessions,id',
                'concessions.*.quantity' => 'required|integer|min:1',
                'send_to_kitchen_time' => 'required|date|after_or_equal:' . now()->addMinutes(330)->toDateTimeString(), 
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => 'Validation failed!',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $totalCost = 0;
            foreach ($request->concessions as $concessionData) {
                $concession = $this->concessionRepository->findById($concessionData['concession_id']);

                if ($concession) {
                    $totalCost += $concession->price * $concessionData['quantity'];
                } else {
                    return response()->json(['error' => 'Concession not found'], 404);
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
                    $order->concessions()->attach($concessionData['concession_id'], [
                        'quantity' => $concessionData['quantity'],
                        'price' => $concession->price,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                } else {
                    return response()->json(['error' => 'Concession not found'], 404);
                }
            }

            return response()->json(['message' => 'Order created successfully!'], 200);
        } catch (\Throwable $th) {
            Log::error('Error creating order: ' . $th->getMessage());
            return response()->json(['error' => 'An error occurred while creating the order.'], 500);
        }
    }

    public function view($id)
    {
        $order = $this->orderRepository->findById($id)
        ->load(['concessions' => function($query) {
            $query->withPivot('quantity', 'price');
        }]);

        return Inertia::render('Orders/View', ['order' => $order]);
    }

    public function destroy($id)
    {
        try {
            $this->orderRepository->delete($id);
            return response()->json(['message' => 'Order deleted successfully!'], 200);
        } catch (\Throwable $th) {
            Log::error('Error deleting order: ' . $th->getMessage());
            return response()->json(['error' => 'An error occurred while deleting the order.'], 500);
        }
    }
}
