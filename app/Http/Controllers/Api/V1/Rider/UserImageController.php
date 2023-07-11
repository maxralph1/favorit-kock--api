<?php

namespace App\Http\Controllers\Api\V1\Rider;

// use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserImageRequest;
use App\Http\Requests\UpdateUserImageRequest;
use App\Http\Resources\UserImageResource;
use App\Models\UserImage;

/**
 * @group Rider endpoints
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
        $userImage = auth()->user()->userImage()
            ->latest()
            ->paginate();

        return UserImageResource::collection($userImage);
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
        $userImage = auth()->user()->userImage()->create($request->validated());

        // $userImage = UserImage::create($request->validated());

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
        if ($userImage->user_id != auth()->id()) {
            abort(403);
        }

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
        if ($userImage->user_id != auth()->id()) {
            abort(403);
        }

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
        if ($userImage->user_id != auth()->id()) {
            abort(403);
        }

        $userImage->delete();

        return response()->noContent();
    }
}
