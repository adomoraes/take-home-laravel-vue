<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Exame;
use App\Http\Requests\StoreExameRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ExameController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Exame::all());
    }

    public function store(StoreExameRequest $request): JsonResponse
    {
        $exame = Exame::create($request->validated());

        return response()->json($exame, Response::HTTP_CREATED);
    }

    public function show(Exame $exame): JsonResponse
    {
        return response()->json($exame);
    }

    public function update(StoreExameRequest $request, Exame $exame): JsonResponse
    {
        $exame->update($request->validated());

        return response()->json($exame);
    }

    public function destroy(Exame $exame): Response
    {
        $exame->delete();

        return response()->noContent();
    }
}