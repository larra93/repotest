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

public function copyValuesRow(Request $request)
{
    $request->validate([
        'selectedDaily' => 'required|exists:dailys,id',
        'idDaily' => 'required|exists:dailys,id',
    ]);

    $selectedDaily = $request->input('selectedDaily');
    $idDaily = $request->input('idDaily');

    try {
        $valuesRows = ValuesRow::where('daily_id', $selectedDaily)->get();
        
        foreach ($valuesRows as $row) {
            ValuesRow::create([
                'col_1' => $row->col_1,
                'col_2' => $row->col_2,
                'col_3' => $row->col_3,
                'col_4' => $row->col_4,
                'col_5' => $row->col_5,
                'col_6' => $row->col_6,
                'col_7' => $row->col_7,
                'col_8' => $row->col_8,
                'col_9' => $row->col_9,
                'col_10' => $row->col_10,
                'col_11' => $row->col_11,
                'col_12' => $row->col_12,
                'col_13' => $row->col_13,
                'col_14' => $row->col_14,
                'col_15' => $row->col_15,
                'col_16' => $row->col_16,
                'col_17' => $row->col_17,
                'col_18' => $row->col_18,
                'col_19' => $row->col_19,
                'col_20' => $row->col_20,
                'col_21' => $row->col_21,
                'col_22' => $row->col_22,
                'col_23' => $row->col_23,
                'col_24' => $row->col_24,
                'col_25' => $row->col_25,
                'daily_id' => $idDaily,
                'daily_sheet_id' => $row->daily_sheet_id,
            ]);
        }

        Log::info('Values copiadas correctamente', [
            'selectedDaily' => $selectedDaily,
            'idDaily' => $idDaily,
            'valuesRows' => $valuesRows->toArray()
        ]);

        return response()->json(['message' => 'Values copiadas correctamente']);
    } catch (Exception $e) {
        Log::error('Error copying values', [
            'error' => $e->getMessage(),
            'selectedDaily' => $selectedDaily,
            'idDaily' => $idDaily
        ]);

        return response()->json(['message' => 'Error copying values'], 500);
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
