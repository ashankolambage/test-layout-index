<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Services\ConcessionService;
use App\Services\OrderService;
use Inertia\Inertia;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected ConcessionService $concessionService;
    protected OrderService $orderService;

    public function __construct(ConcessionService $concessionService, OrderService $orderService)
    {
        $this->concessionService = $concessionService;
        $this->orderService = $orderService;
    }

    public function index()
    {
        return Inertia::render('Orders/Index');
    }

    public function kitchenIndex()
    {
        return Inertia::render('Kitchen/Index');
    }

    public function fetchOrders(Request $request)
    {
        return $this->orderService->fetchOrders($request);
    }

    public function create()
    {
        $concessions = $this->concessionService->getAllConcessions();
        return Inertia::render('Orders/Create', ['concessions' => $concessions]);
    }

    public function store(StoreOrderRequest $request)
    {
        return $this->orderService->storeOrder($request);
    }

    public function update(Request $request, $id)
    {
        return $this->orderService->updateOrderStatus($request, $id);
    }

    public function view($id)
    {
        $order = $this->orderService->viewOrder($id);
        return Inertia::render('Orders/View', ['order' => $order]);
    }

    public function kitchenOrderview($id)
    {
        $order = $this->orderService->viewOrder($id);
        return Inertia::render('Kitchen/View', ['order' => $order]);
    }

    public function destroy($id)
    {
        return $this->orderService->deleteOrder($id);
    }
}
