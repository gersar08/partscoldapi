<?php

namespace App\Http\Controllers;

use App\Models\Inventarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class InventariosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $inventario = Inventarios::all();
        return response()->json($inventario, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            '*.product_name' => 'required|string',
            '*.codigo_producto' => 'required|string',
            '*.descripcion' => 'required|string',
            '*.cantidad_stock' => 'required|integer',
            '*.img_product' => 'nullable|string',
            '*.precio_producto' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $createdInventories = [];

        foreach ($request->all() as $InventarioData) {
            $inventario = Inventarios::create($InventarioData);
            $createdInventories[] = $inventario;
        }

        return response()->json($createdInventories, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Inventarios  $inventario
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $terminoBusqueda = $request->query('termino');
        $campos = ['product_name', 'codigo_producto'];

        $query = Inventarios::query();

        foreach ($campos as $campo) {
            $query->orWhere($campo, 'LIKE', "%{$terminoBusqueda}%");
        }

        $resultados = $query->get();

        return response()->json($resultados);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Inventarios  $inventario
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            '*.id' => 'required|exists:inventarios,id',
            '*.product_name' => 'required|string',
            '*.codigo_producto' => 'required|string',
            '*.descripcion' => 'required|string',
            '*.cantidad_stock' => 'required|integer',
            '*.img_product' => 'nullable|string',
            '*.precio_producto' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        foreach ($request->all() as $InventarioData) {
            $inventario = Inventarios::findOrFail($InventarioData['id']);
            $inventario->update($InventarioData);
        }

        return response()->json(['message' => 'Inventories updated successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Inventarios  $inventario
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $id)
    {
        $inventario = Inventarios::findOrFail($id);
        $inventario->delete();
        return response()->json(['message' => 'Eliminado con éxito']);
    }
}

