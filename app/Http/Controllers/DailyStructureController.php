<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Field;
use App\Models\DailyStructure;
use App\Models\DailySheet;
use App\Models\Dailys;
use App\Models\DropdownLists;




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
    public function store(Request $request, string $id, bool $iguales)
    {
        DB::beginTransaction();
        $out = new \Symfony\Component\Console\Output\ConsoleOutput();
        $out->writeln($iguales);
        try {

            // Obtiene todos los datos del cuerpo de la solicitud
            $data = $request->json()->all();

            //obtengo la ultima estructura vigente
            $dailyStructuresVigentes_old = DailyStructure::where('contract_id', $id)
                ->where('vigente', 1)
                ->get();


            // Guardar el resultado en una variable boolean
            $allFieldsMatch = $iguales;
           // $out->writeln( $allFieldsMatch);

            if ($allFieldsMatch) {
                // si las fields son iguales, solo actualizo los dropdowns
                foreach ($data as $sheetitem) {
                    foreach ($sheetitem['fields'] as $fielditem) {
                        $field = Field::where('name', $fielditem['name'])
                            ->whereHas('dailySheet', function ($query) use ($id) {
                                $query->whereHas('dailyStructure', function ($query) use ($id) {
                                    $query->where('contract_id', $id);
                                });
                            })
                            ->first();
                        if ($field) {
                            // Eliminar los dropdowns del field
                            $field->dropdown_lists()->delete();
                            // Crear y guardar los dropdowns nuevos
                            foreach ($fielditem['dropdown_lists'] as $dropdownitem) {
                                $dropdown = DropdownLists::create([
                                    'value' => $dropdownitem['value'],
                                    'field_id' => $field->id
                                ]);
                            }
                        }
                    }

                }


            } else {
                // si las fields no son iguales, creo una nueva estructura diaria y todo nuevo
                foreach ($dailyStructuresVigentes_old as $dailyStructure) {
                    $dailyStructure->vigente = 0;
                    $dailyStructure->save();
                }

                // Crear y guardar la nueva DailyStructure
                $newDailyStructure = DailyStructure::create([
                    'contract_id' => $id,
                    'vigente' => 1, // Asumiendo que la nueva estructura debe ser vigente
                ]);

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

                    // Crear y guardar los fields
                    foreach ($sheetitem['fields'] as $fielditem) {


                        $field = Field::create([
                            'name' => $fielditem['name'],
                            'description' => $fielditem['description'],
                            'field_type' => $fielditem['field_type'],
                            'step' => $fielditem['step'],
                            'daily_sheet_id' => $newsheet->id
                        ]);
                        //por cada field creo los dropdowns
                        foreach ($fielditem['dropdown_lists'] as $dropdownitem) {
                            $dropdown = DropdownLists::create([
                                'id' => $dropdownitem['id'],
                                'value' => $dropdownitem['value'],
                                'field_id' => $field->id
                            ]);
                        }
                        // Buscar todos los fields con el mismo nombre y contrato
                        $fieldsWithSameName = Field::where('name', $fielditem['name'])
                            ->where('field_type', 'list')
                            ->whereHas('dailySheet', function ($query) use ($id) {
                                $query->whereHas('dailyStructure', function ($query) use ($id) {
                                    $query->where('contract_id', $id);
                                });
                            })
                            ->get();



                        // Eliminar los dropdowns de los fields encontrados
                        foreach ($fieldsWithSameName as $fieldWithSameName) {
                            $fieldWithSameName->dropdown_lists()->delete();
                        }


                        // Setear los dropdowns de los fields encontrados
                        foreach ($fieldsWithSameName as $fieldWithSameName) {
                            foreach ($fielditem['dropdown_lists'] as $dropdownitem) {
                                $dropdown = DropdownLists::create([
                                    'id' => $dropdownitem['id'],
                                    'value' => $dropdownitem['value'],
                                    'field_id' => $fieldWithSameName->id
                                ]);
                            }
                        }

                    }
                }
                // cambiar la dailystructure de todos los dailys con fecha mayor a la actual
                $dailys = Dailys::where('contract_id', $id)
                    ->where('date', '>', date('Y-m-d'))
                    ->get();

                foreach ($dailys as $daily) {
                    $daily->daily_structure_id = $newDailyStructure->id;
                    $daily->save();
                }

            }


            DB::commit(); // Confirmar la transacción
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
