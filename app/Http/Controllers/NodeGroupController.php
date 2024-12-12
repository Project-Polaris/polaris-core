<?php

namespace App\Http\Controllers;

use App\Http\Requests\NodeGroup\StoreNodeGroupRequestV1;
use App\Http\Requests\NodeGroup\UpdateNodeGroupRequestV1;
use App\Http\Resources\NodeGroupResource;
use App\Models\NodeGroup;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Response;

class NodeGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index_v1() : AnonymousResourceCollection
    {
        return NodeGroupResource::collection(NodeGroup::paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store_v1(StoreNodeGroupRequestV1 $request) : NodeGroupResource
    {
        return new NodeGroupResource(NodeGroup::create($request->validated()));
    }

    /**
     * Display the specified resource.
     */
    public function show_v1(NodeGroup $nodeGroup) : NodeGroupResource
    {
        return new NodeGroupResource($nodeGroup);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update_v1(UpdateNodeGroupRequestV1 $request, NodeGroup $nodeGroup) : NodeGroupResource
    {
        $nodeGroup->update($request->validated());

        return new NodeGroupResource($nodeGroup);
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy_v1(NodeGroup $nodeGroup) : HttpResponse
    {
        return $nodeGroup->delete() ?
            Response::noContent() :
            Response::noContent(409);
    }
}
