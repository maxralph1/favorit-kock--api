<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserImageRequest;
use App\Http\Requests\UpdateUserImageRequest;
use App\Http\Resources\UserImageResource;
use App\Models\UserImage;

/**
 * @group Admin endpoints
 */
class UserImageController extends Controller
{
    /**
     * GET User Images
     *
     * Returns paginated list of user images.
     * 
     * @authenticated
     *
     * @queryParam page integer Page number. Example: 1
     *
     * @response {"data":{"id":"01h3hkhxrh15atksjr11hrck0d","image_url":"https://via.placeholder.com/133x422.png/00dd55?tex...","user_id":"01h3hkhxrh15atksjr11hrck0d"}, ...}
     */
    public function index()
    {
        $userImages = UserImage::withTrashed()
            ->latest()
            ->paginate();

        return UserImageResource::collection($userImages);
    }

    /**
     * POST User Image
     *
     * Creates a new User Image record.
     *
     * @authenticated
     *
     * @response {"data":{"id":"01h3hkhxrh15atksjr11hrck0d","image_url":"https://via.placeholder.com/133x422.png/00dd55?tex...","user_id":"01h3hkhxrh15atksjr11hrck0d"}, ...}
     */
    public function store(StoreUserImageRequest $request)
    {
        $userImage = UserImage::create($request->validated());

        return new UserImageResource($userImage);
    }

    /**
     * GET User Image
     *
     * Returns a User Image record.
     *
     * @authenticated
     *
     * @response {"data":{"id":"01h3hkhxrh15atksjr11hrck0d","image_url":"https://via.placeholder.com/133x422.png/00dd55?tex...","user_id":"01h3hkhxrh15atksjr11hrck0d"}, ...}
     * @response 404 {"message":"Record not found."}
     */
    public function show(UserImage $userImage)
    {
        return new UserImageResource($userImage);
    }

    /**
     * PUT User Image
     *
     * Updates User Image record.
     *
     * @authenticated
     *
     * @response {"data":{"id":"01h3hkhxrh15atksjr11hrck0d","image_url":"https://via.placeholder.com/133x422.png/00dd55?tex...","user_id":"01h3hkhxrh15atksjr11hrck0d"}, ...}
     */
    public function update(UpdateUserImageRequest $request, UserImage $userImage)
    {
        $userImage->update($request->validated());

        return new UserImageResource($userImage);
    }

    /**
     * DELETE User Image
     *
     * Deletes User Image record.
     * 
     * @authenticated
     *
     * @response 204 {}
     */
    public function destroy(UserImage $userImage)
    {
        $userImage->delete();

        return response()->noContent();
    }
}
