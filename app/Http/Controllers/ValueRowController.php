<?php

namespace App\Http\Controllers;
use App\Models\ValueRow;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ValueRowController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Crear el registro sin validaciones
            $data = $request->all();
            foreach ($data as $item) {
                Value::create([
                    'field_id' => $item['field_id'],
                    'value' => $item['value'] ?? '',
                    'daily_sheet_id' => $item['daily_sheet_id'],
                    'daily_id' => $item['daily_id'],
                    'row' => $item['row']
                ]);
            }

            return response()->json($data, 201);
        } catch (\Exception $e) {
            // Registrar el error en el log
            Log::error('Error creating value: ' . $e->getMessage());

            return response()->json(['error' => 'Error creating value'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateValues(Request $request)
{
    try {
       
        $data = $request->all();
       
        if (!is_array($data) || !isset($data[0]['field_id'])) {
            return response()->json(['error' => 'Invalid data format'], 400);
        }

        $updatedValues = [];

        
        foreach ($data as $item) {
            
            $value = Value::find($item['id']);
            Log::warning($item);
            if ($value) {
                // Actualizar los campos del registro
                $value->update([
                    'value' => $item['value'] ?? $value->value,
                    'daily_sheet_id' => $item['daily_sheet_id'] ?? $value->daily_sheet_id,
                    'daily_id' => $item['daily_id'] ?? $value->daily_id,
                    'row' => $item['row'] ?? $value->row,
                ]);

                // $updatedValues[] = $value->fresh();
            } else {
                Log::warning('Registro ' . $value);
            }
        }

        return response()->json($value, 200);
    } catch (\Exception $e) {
        // Registrar el error en el log
        Log::error('Error updating values: ' . $e->getMessage());

        return response()->json(['error' => 'Error updating values'], 500);
    }
}



    /**
     * Remove the specified resource from storage.
     */
    // public function destroy(string $id)
    // {
    //     try {
    //         $value = Value::find($id);
            
    //         if ($value) {
    //             $value->delete();
    //             return response()->json(['message' => 'Fila eliminada correctamente'], 200);
    //         } else {
    //             return response()->json(['error' => 'Error fila'], 404);
    //         }
    //     } catch (\Exception $e) {
    //         Log::error('Error al borrar fila: ' . $e->getMessage());
    //         return response()->json(['error' => 'Error deleting value'], 500);
    //     }
    // }

    public function deleteValues(Request $request)
    {
        $row = $request->input('row');
        $daily_id = $request->input('daily_id');
        $daily_sheet_id = $request->input('daily_sheet_id');

        $deletedRows = Value::where('row', $row)
            ->where('daily_id', $daily_id)
            ->where('daily_sheet_id', $daily_sheet_id)
            ->delete();

        if ($deletedRows) {
            return response()->json(['message' => 'Fila eliminada exitosamente'], 200);
        } else {
            return response()->json(['message' => 'Error'], 404);
        }
    }
}
