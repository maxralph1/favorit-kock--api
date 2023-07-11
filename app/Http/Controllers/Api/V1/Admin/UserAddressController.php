<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserAddressRequest;
use App\Http\Requests\UpdateUserAddressRequest;
use App\Http\Resources\UserAddressResource;
use App\Models\UserAddress;

/**
 * @group Admin endpoints
 */
class UserAddressController extends Controller
{
    /**
     * GET User Addresses
     *
     * Returns paginated list of user addresses.
     * 
     * @authenticated
     *
     * @queryParam page integer Page number. Example: 1
     *
     * @response {"data":{"id":"01h3hkhxrh15atksjr11hrck0d","user_id":"01h3hkhxrh15atksjr11hrck0d","house_number":0,"street":"Thomson Street","city":"New York","post_code":12345,"state":"New York STate","landmark":"On the mango tree","default":1}, ...}
     */
    public function index()
    {
        $userAddresses = UserAddress::withTrashed()
            ->latest()
            ->paginate();

        return UserAddressResource::collection($userAddresses);
    }

    /**
     * POST User Address
     *
     * Creates a new User Address record.
     *
     * @authenticated
     *
     * @response {"data":{"id":"01h3hkhxrh15atksjr11hrck0d","user_id":"01h3hkhxrh15atksjr11hrck0d","house_number":0,"street":"Thomson Street","city":"New York","post_code":12345,"state":"New York STate","landmark":"On the mango tree","default":1}, ...}
     */
    public function store(StoreUserAddressRequest $request)
    {
        $userAddress = UserAddress::create($request->validated());

        return new UserAddressResource($userAddress);
    }

    /**
     * GET User Address
     *
     * Returns an User Address record.
     *
     * @authenticated
     *
     * @response {"data":{"id":"01h3hkhxrh15atksjr11hrck0d","user_id":"01h3hkhxrh15atksjr11hrck0d","house_number":0,"street":"Thomson Street","city":"New York","post_code":12345,"state":"New York STate","landmark":"On the mango tree","default":1}, ...}
     * @response 404 {"message":"Record not found."}
     */
    public function show(UserAddress $userAddress)
    {
        return new UserAddressResource($userAddress);
    }

    /**
     * PUT User Address
     *
     * Updates User Address record.
     *
     * @authenticated
     *
     * @response {"data":{"id":"01h3hkhxrh15atksjr11hrck0d","meal_id":"01h3hkhxrh15atksjr11hrck0d","order_id":"01h3hkhxrh15atksjr11hrck0d","user_id":"01h3hkhxrh15atksjr11hrck0d","amount_due":55,"quantity_ordered":5}, ...}
     */
    public function update(UpdateUserAddressRequest $request, UserAddress $userAddress)
    {
        $userAddress->update($request->validated());

        return new UserAddressResource($userAddress);
    }

    /**
     * DELETE User Address
     *
     * Deletes User Address record.
     * 
     * @authenticated
     *
     * @response 204 {}
     */
    public function destroy(UserAddress $userAddress)
    {
        $userAddress->delete();

        return response()->noContent();
    }
}
