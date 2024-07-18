<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Field;
use App\Models\DailyStructure;
use App\Models\DailySheet;
use App\Models\Dailys;



class DailyStructureController extends Controller
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
    public function store(Request $request, string $id)
    {
        DB::beginTransaction();
        $out = new \Symfony\Component\Console\Output\ConsoleOutput();

        try {
            //modifico la dailystructure vigentes del contrato a 0
            $dailyStructures = DailyStructure::where('contract_id', $id)
                ->where('vigente', 1)
                ->get();
            foreach ($dailyStructures as $dailyStructure) {
                $dailyStructure->vigente = 0;
                $dailyStructure->save();
            }

            // Crear y guardar la nueva DailyStructure
            $newDailyStructure = DailyStructure::create([
                'contract_id' => $id,
                'vigente' => 1, // Asumiendo que la nueva estructura debe ser vigente
            ]);

            // Obtiene todos los datos del cuerpo de la solicitud
            $data = $request->json()->all();

           //creo las dailysheets y los fields
            foreach ($data as $sheetitem) {

                // Verificar que los fields de la hoja sean un array
                if (!is_array($sheetitem['fields'])) {
                    return response()->json(['error' => 'Invalid data format. "fields" must be an array.'], 400);
                }

                $newsheet = DailySheet::create([
                    'name' => $sheetitem['sheet'],
                    'step' => $sheetitem['step'],
                    'daily_structure_id' => $newDailyStructure->id,
                ]);

                foreach ($sheetitem['fields'] as $fielditem) {
                    $out->writeln("entroaca");

                    $field = Field::create([
                        'name' => $fielditem['name'],
                        'description' => $fielditem['description'],
                        'field_type' => $fielditem['field_type'],
                        'step' => $fielditem['step'],
                        'daily_sheet_id' => $newsheet->id
                    ]);
                }
            }
            // cambiar la dailystructure de todos los dailys en estado a la espera contratista
            $dailys = Dailys::where('contract_id', $id)
                ->where('date', '>', date('Y-m-d'))
                ->get();

            foreach ($dailys as $daily) {
                $daily->daily_structure_id = $newDailyStructure->id;
                $daily->save();
            }


            //DB::commit(); // Confirmar la transacción
            return response()->json(['message' => 'Estructura diaria creada y actualizada exitosamente'], 200);
        } catch (\Exception $e) {
            DB::rollBack(); // Revertir la transacción en caso de error
            return response()->json(['error' => 'Error al guardar la estructura diaria', 'message' => $e->getMessage()], 500);
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
