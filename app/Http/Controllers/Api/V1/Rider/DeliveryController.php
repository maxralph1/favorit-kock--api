<?php

namespace App\Http\Controllers\Api\V1\Rider;

use App\Http\Controllers\Controller;
use App\Http\Resources\DeliveryResource;
use App\Models\Delivery;
use Illuminate\Http\Request;

/**
 * @group Rider endpoints
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
        $deliveries = auth()->user()->deliveries()
            ->latest()
            ->paginate();

        return DeliveryResource::collection($deliveries);
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
        if ($delivery->user_id != auth()->id()) {
            abort(403);
        }

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
    public function update(Request $request, Delivery $delivery)
    {
        if ($delivery->user_id != auth()->id()) {
            abort(403);
        }

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
        if ($delivery->user_id != auth()->id()) {
            abort(403);
        }

        $delivery->delete();

        return response()->noContent();
    }
}
