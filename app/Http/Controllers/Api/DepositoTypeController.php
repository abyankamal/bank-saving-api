<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DepositoType;
use Illuminate\Http\Request;

class DepositoTypeController extends Controller
{
    public function index()
    {
        return DepositoType::all();
    }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'yearly_return' => 'required|decimal',
    //     ]);

    //     $depositoType = DepositoType::create($request->all());
    //     return response()->json($depositoType, 201);
    // }

    public function show($id)
    {
        return DepositoType::find($id);
    }

    // public function destroy($id)
    // {
    //     DepositoType::destroy($id);
    //     return response()->json(null, 204);
    // }
}
