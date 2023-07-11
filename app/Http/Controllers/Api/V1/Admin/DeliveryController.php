<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDeliveryRequest;
use App\Http\Requests\UpdateDeliveryRequest;
use App\Http\Resources\DeliveryResource;
use App\Models\Delivery;

/**
 * @group Admin endpoints
 */
class DeliveryController extends Controller
{
    /**
     * GET Deliveries
     *
     * Returns paginated list of deliveries.
     * 
     * @authenticated
     *
     * @queryParam page integer Page number. Example: 1
     *
     * @response {"data":{"id":"01h3hkhxrh15atksjr11hrck0d","order_id":"01h3hkhxrh15atksjr11hrck0d","user_id":"01h3hkhxrh15atksjr11hrck0d","delivered":1,"time_delivered":"2007-03-15 16:07:24"}, ...}
     */
    public function index()
    {
        $deliveries = Delivery::withTrashed()
            ->latest()
            ->paginate();

        return DeliveryResource::collection($deliveries);
    }

    /**
     * POST Delivery
     *
     * Creates a new Delivery record.
     *
     * @authenticated
     *
     * @response {"data":{"id":"01h3hkhxrh15atksjr11hrck0d","order_id":"01h3hkhxrh15atksjr11hrck0d","user_id":"01h3hkhxrh15atksjr11hrck0d","delivered":1,"time_delivered":"2007-03-15 16:07:24"}, ...}
     * @response 422 {"message":"The order_id field is required.","errors":{"order_id":["The order_id field is required."]}, ...}
     */
    public function store(StoreDeliveryRequest $request)
    {
        $delivery = Delivery::create($request->validated());

        return new DeliveryResource($delivery);
    }

    /**
     * GET Delivery
     *
     * Returns a Delivery record.
     *
     * @authenticated
     *
     * @response {"data":{"id":"01h3hkhxrh15atksjr11hrck0d","order_id":"01h3hkhxrh15atksjr11hrck0d","user_id":"01h3hkhxrh15atksjr11hrck0d","delivered":1,"time_delivered":"2007-03-15 16:07:24"}, ...}
     * @response 404 {"message":"Record not found."}
     */
    public function show(Delivery $delivery)
    {
        return new DeliveryResource($delivery);
    }

    /**
     * PUT Delivery
     *
     * Updates Delivery record.
     *
     * @authenticated
     *
     * @response {"data":{"id":"01h3hkhxrh15atksjr11hrck0d","order_id":"01h3hkhxrh15atksjr11hrck0d","user_id":"01h3hkhxrh15atksjr11hrck0d","delivered":1,"time_delivered":"2007-03-15 16:07:24"}, ...}
     */
    public function update(UpdateDeliveryRequest $request, Delivery $delivery)
    {
        $delivery->update($request->validated());

        return new DeliveryResource($delivery);
    }

    /**
     * DELETE Delivery
     *
     * Deletes Delivery record.
     * 
     * @authenticated
     *
     * @response 204 {}
     */
    public function destroy(Delivery $delivery)
    {
        $delivery->delete();

        return response()->noContent();
    }
}
