<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreConstructionRequest;
use App\Http\Resources\ConstructionResource;
use App\Models\Construction;

class ConstructionController extends Controller
{
    public function index()
    {
        $constructions = Construction::all();
        return ConstructionResource::collection($constructions);
    }

    public function store(StoreConstructionRequest $request)
    {
        $construction = Construction::create($request->validated());
        return new ConstructionResource($construction);
    }

    public function show(Construction $construction)
    {
        return new ConstructionResource($construction);   
    }

    public function update(StoreConstructionRequest $request, Construction $construction)
    {
        $construction->update($request->validated());
        return new ConstructionResource($construction);
    }

    public function destroy(Construction $construction)
    {
        $construction->delete();
        return response(null, 204);
    }
}
