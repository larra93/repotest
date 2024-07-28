<?php

namespace App\Http\Controllers;
use App\Models\ValuesRow;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ValuesRowController extends Controller
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
        $out = new \Symfony\Component\Console\Output\ConsoleOutput();
        $out->writeln('hola');
        try {
            $out->writeln('Raw request content: ' . $request->getContent());

            // Log all request data
            $data = $request->all();
            $out->writeln('Request data: ' . json_encode($data));
            

            // Crear el registro sin validaciones
            $valueRow = ValuesRow::create($data);

            return response()->json($data, 201);
        } catch (\Exception $e) {
            // Registrar el error en el log
            Log::error('Error creating value: ' . $e->getMessage());
            $out->writeln($e->getMessage());

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

        if (!is_array($data) || !isset($data['id'])) {
            return response()->json(['error' => 'Invalid data format'], 400);
        }

        $value = ValuesRow::find($data['id']);
        if ($value) {
            $value->update($data);
            return response()->json($data, 201);
        } else {
            return response()->json(['error' => 'Error updating value'], 404);
        }
    } catch (\Exception $e) {
        Log::error('Error updating value: ' . $e->getMessage());
        return response()->json(['error' => 'Error updating value'], 500);
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


        $deletedRows = ValuesRow::where('id', $row)->delete();
        
        if ($deletedRows) {
            return response()->json(['message' => 'Fila eliminada exitosamente'], 200);
        } else {
            return response()->json(['message' => 'Error'], 404);
        }
    }
}
