<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\StoreUserRequestV1;
use App\Http\Requests\User\UpdateUserRequestV1;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Response;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index_v1() : AnonymousResourceCollection
    {
        return UserResource::collection(User::paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store_v1(StoreUserRequestV1 $request): UserResource
    {
        return new UserResource(User::create($request->validated()));
    }

    /**
     * Display the specified resource.
     */
    public function show_v1(User $user): UserResource
    {
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update_v1(UpdateUserRequestV1 $request, User $user): UserResource
    {
        $user->update($request->validated());

        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy_v1(User $user): HttpResponse
    {
        return $user->delete() ?
            Response::noContent() :
            Response::noContent(409);
    }
}
