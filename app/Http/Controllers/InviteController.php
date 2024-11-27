<?php

namespace App\Http\Controllers;

use App\Http\Requests\Invite\StoreInviteRequestV1;
use App\Http\Requests\Invite\UpdateInviteRequestV1;
use App\Http\Resources\InviteResource;
use App\Models\Invite;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Response;

class InviteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index_v1(): AnonymousResourceCollection
    {
        return InviteResource::collection(Invite::paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store_v1(StoreInviteRequestV1 $request): InviteResource
    {
        return new InviteResource(Invite::create($request->validated()));
    }

    /**
     * Display the specified resource.
     */
    public function show_v1(Invite $invite): InviteResource
    {
        return new InviteResource($invite);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update_v1(UpdateInviteRequestV1 $request, Invite $invite): InviteResource
    {
        $invite->update($request->validated());

        return new InviteResource($invite);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy_v1(Invite $invite): HttpResponse
    {
        return $invite->delete() ?
            Response::noContent() :
            Response::noContent(409);
    }
}
