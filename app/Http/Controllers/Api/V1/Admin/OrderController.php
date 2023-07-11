<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;

/**
 * @group Admin endpoints
 */
class OrderController extends Controller
{
    /**
     * GET Orders
     *
     * Returns paginated list of orders.
     * 
     * @authenticated
     *
     * @queryParam page integer Page number. Example: 1
     *
     * @response {"data":{"id":"01h3hkhxrh15atksjr11hrck0d","user_id":"01h3hkhxrh15atksjr11hrck0d","order_annuled":0,"delivered":0,"total_amount":50,"paid":0,"delivered_by":"01h3hkhxrh15atksjr11hrck0d"}, ...}
     */
    public function index()
    {
        $orders = Order::withTrashed()
            ->latest()
            ->paginate();

        return OrderResource::collection($orders);
    }

    /**
     * POST Order
     *
     * Creates a new Order record.
     *
     * @authenticated
     *
     * @response {"data":{"id":"01h3hkhxrh15atksjr11hrck0d","user_id":"01h3hkhxrh15atksjr11hrck0d","order_annuled":0,"delivered":0,"total_amount":50,"paid":0,"delivered_by":"01h3hkhxrh15atksjr11hrck0d"}, ...}
     */
    public function store(StoreOrderRequest $request)
    {
        $order = Order::create($request->validated());

        return new OrderResource($order);
    }

    /**
     * GET Order
     *
     * Returns an Order record.
     *
     * @authenticated
     *
     * @response {"data":{"id":"01h3hkhxrh15atksjr11hrck0d","user_id":"01h3hkhxrh15atksjr11hrck0d","order_annuled":0,"delivered":0,"total_amount":50,"paid":0,"delivered_by":"01h3hkhxrh15atksjr11hrck0d"}, ...}
     * @response 404 {"message":"Record not found."}
     */
    public function show(Order $order)
    {
        return new OrderResource($order);
    }

    /**
     * PUT Order
     *
     * Updates Order record.
     *
     * @authenticated
     *
     * @response {"data":{"id":"01h3hkhxrh15atksjr11hrck0d","user_id":"01h3hkhxrh15atksjr11hrck0d","order_annuled":0,"delivered":0,"total_amount":50,"paid":0,"delivered_by":"01h3hkhxrh15atksjr11hrck0d"}, ...}
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        $order->update($request->validated());

        return new OrderResource($order);
    }

    /**
     * DELETE Order
     *
     * Deletes Order record.
     * 
     * @authenticated
     *
     * @response 204 {}
     */
    public function destroy(Order $order)
    {
        $order->delete();

        return response()->noContent();
    }
}
