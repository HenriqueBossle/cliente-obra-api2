<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreConstructionRequest;
use App\Http\Requests\UpdateConstructionRequest;
use App\Http\Resources\ConstructionResource;
use App\Models\Construction;
use Illuminate\Http\Request;

use Barryvdh\DomPDF\Facade\Pdf;

class ConstructionController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(Construction::class, 'construction');
    }

    public function index(Request $request)
    {
    $constructions = Construction::where(
        'user_id',
        $request->user()->id
    )->get();

        // dd($constructions->toArray());


    return ConstructionResource::collection($constructions);
    }

    public function store(StoreConstructionRequest $request, Construction $construction)
    {
        $data = $request->validated();
        // Ensure the date fields are present; log the payload for debugging
        logger()->debug('Construction Store - validated data', $data);
        $data['user_id'] = $request->user()->id;

        $construction = Construction::create($data);
        logger()->debug('Construction Store - created model', $construction->toArray());
        return response()->json($construction, 201);
    }

    public function show(Construction $construction)
    {
        return new ConstructionResource($construction);   
    }

    public function update(UpdateConstructionRequest $request, Construction $construction)
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;

        $construction->update($data);
        return new ConstructionResource($construction);
    }

    public function destroy(Construction $construction)
    {
        $construction->delete();
        return response(null, 204);
    }

    public function generatePdf(Construction $construction)
    {
        $this->authorize('view', $construction);

        $pdf = Pdf::loadView('pdf.construction', [
            'construction' => $construction
        ]);

        return $pdf->download(
            "obra-{$construction->id}.pdf"
        );
    }

    public function generateAllPdf(Request $request)
    {
        $constructions = Construction::where(
            'user_id',
            $request->user()->id
        )->get();

        $pdf = Pdf::loadView('pdf.constructions', [
            'constructions' => $constructions
        ]);

        return $pdf->download('relatorio-obras.pdf');
    }
}
