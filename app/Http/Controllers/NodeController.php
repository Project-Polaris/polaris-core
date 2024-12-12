<?php

namespace App\Http\Controllers;

use App\Http\Requests\Node\StoreNodeRequestV1;
use App\Http\Requests\Node\UpdateNodeRequestV1;
use App\Http\Resources\NodeResource;
use App\Models\Node;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Response;

class NodeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index_v1() : AnonymousResourceCollection
    {
        return NodeResource::collection(Node::paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store_v1(StoreNodeRequestV1 $request) : NodeResource
    {
        return new NodeResource(Node::create($request->validated()));
    }

    /**
     * Display the specified resource.
     */
    public function show_v1(Node $node) : NodeResource
    {
        return new NodeResource($node);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update_v1(UpdateNodeRequestV1 $request, Node $node) : NodeResource
    {
        $node->update($request->validated());

        return new NodeResource($node);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy_v1(Node $node) : HttpResponse
    {
        return $node->delete() ?
            Response::noContent() :
            Response::noContent(409);
    }
}
