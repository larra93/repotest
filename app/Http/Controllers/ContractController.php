<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreContractRequest;
use Spatie\Permission\Models\Role;
use App\Models\Contract;
use App\Models\DailySheet;
use App\Models\DailyStructure;
use App\Models\Dailys;
use App\Models\DropdownLists;
use App\Models\Field;
use Carbon\Carbon;
use App\Http\Controllers\Log;




class ContractController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $perPage = $request->get('per_page', 10);
        $contracts = Contract::with([
            'company:id,name,rut_number,rut_verifier',
            'users' => function ($query) {
                $query->withPivot('role_id')
                    ->leftJoin('roles', 'contract_user.role_id', '=', 'roles.id')
                    ->select('users.id', 'users.name', 'roles.name as role_name', 'contract_user.contract_id');
            }
        ])->paginate($perPage);

        $formattedContracts = $contracts->map(function ($contract) {
            return [
                'id' => $contract->id,
                'nombre_contrato' => $contract->name_contract,
                'NSAP' => $contract->NSAP,
                'DEN' => $contract->DEN,
                'proyecto' => $contract->project,
                'API' => $contract->API,
                'id_company' => $contract->id_company,
                'rut_contratista' => $contract->company->rut_number . '-' . $contract->company->rut_verifier,
                'empresa_contratista' => $contract->company->name,
                'encargadoContratista' => $contract->users->where('role_name', 'encargado_contratista')->values(),
                'adminTerreno' => $contract->users->where('role_name', 'admin_terreno')->values(),
                'visualizador' => $contract->users->where('role_name', 'visualizador')->values(),
                'encargadoCodelco' => $contract->users->where('role_name', 'encargado_codelco')->values(),
            ];
        });


        return response()->json([
            'data' => $formattedContracts,
            'total' => $contracts->total(),
            'per_page' => $contracts->perPage(),
            'current_page' => $contracts->currentPage(),
        ], 200);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreContractRequest $request)
    {
        $validatedData = $request->validated();

        DB::beginTransaction();
        try {
            $contract = Contract::create([
                'name_contract' => $validatedData['name_contract'],
                'NSAP' => $validatedData['NSAP'],
                'DEN' => $validatedData['DEN'],
                'project' => $validatedData['project'],
                'API' => $validatedData['API'],
                'start_date' => $validatedData['start_date'],
                'end_date' => $validatedData['end_date'],
                'id_company' => $validatedData['id_company'],
                'created_by' => $validatedData['created_by'],
                'visualizador' => $validatedData['visualizador'],
                'is_revisor_pyc_required' => $validatedData['revisorPYCRequired'] ?? false,
                'is_revisor_cc_required' => $validatedData['revisorCCRequired'] ?? false,
                'is_revisor_other_area_required' => $validatedData['revisorOtraAreaRequired'] ?? false,
            ]);

            if (isset($validatedData['revisorPYC'])) {
                $roleId = Role::where('name', 'revisor_pyc')->first()->id;
                foreach ($validatedData['revisorPYC'] as $userId) {
                    $contract->users()->attach($userId, ['role_id' => $roleId]);
                }
            }

            if (isset($validatedData['revisorCC'])) {
                $roleId = Role::where('name', 'revisor_cc')->first()->id;
                foreach ($validatedData['revisorCC'] as $userId) {
                    $contract->users()->attach($userId, ['role_id' => $roleId]);
                }
            }

            if (isset($validatedData['revisorOtraArea'])) {
                $roleId = Role::where('name', 'revisor_otra_area')->first()->id;
                foreach ($validatedData['revisorOtraArea'] as $userId) {
                    $contract->users()->attach($userId, ['role_id' => $roleId]);
                }
            }

            if (isset($validatedData['adminDeTerreno'])) {
                $roleId = Role::where('name', 'admin_terreno')->first()->id;
                foreach ($validatedData['adminDeTerreno'] as $userId) {
                    $contract->users()->attach($userId, ['role_id' => $roleId]);
                }
            }
            if (isset($validatedData['encargadoContratista'])) {
                $roleId = Role::where('name', 'encargado_contratista')->first()->id;
                foreach ($validatedData['encargadoContratista'] as $userId) {
                    $contract->users()->attach($userId, ['role_id' => $roleId]);
                }
            }
            if (isset($validatedData['visualizador'])) {
                $roleId = Role::where('name', 'visualizador')->first()->id;
                foreach ($validatedData['visualizador'] as $userId) {
                    $contract->users()->attach($userId, ['role_id' => $roleId]);
                }
            }
            if (isset($validatedData['encargadoCodelco'])) {
                $roleId = Role::where('name', 'encargado_codelco')->first()->id;
                foreach ($validatedData['encargadoCodelco'] as $userId) {
                    $contract->users()->attach($userId, ['role_id' => $roleId]);
                }
            }

            $out = new \Symfony\Component\Console\Output\ConsoleOutput();

    //* Start crear estructura de daily
            try {
                $dailyStructure = DailyStructure::create([
                    'contract_id' => $contract->id,
                    'vigente' => true,
                ]);
            } catch (\Exception $e) {
                return response()->json(['error' => 'Error al crear la estructura diaria', 'message' => $e->getMessage()], 500);
            }

    //* Fin estructura de daily

    //* Personal
            $personalSheet = DailySheet::create([
                'name' => 'Personal',
                'step' => '1',
                'daily_structure_id' => $dailyStructure->id,
            ]);
            Field::create([
                'name' => 'RUT',
                'description' => 'Rut del trabajador',
                'field_type' => 'text',
                'step' => '1',
                'required' => "Si",
                'daily_sheet_id' => $personalSheet->id,
            ]);
            Field::create([
                'name' => 'Género',
                'description' => 'Género del trabajador',
                'field_type' => 'list',
                'step' => '3',
                'required' => "Si",
                'daily_sheet_id' => $personalSheet->id,
            ]);
            Field::create([
                'name' => 'Cargo',
                'description' => 'Cargo del trabajador',
                'field_type' => 'list',
                'step' => '4    ',
                'required' => "Si",
                'daily_sheet_id' => $personalSheet->id,
            ]);
            Field::create([
                'name' => 'Categoría',
                'description' => 'Categoría del trabajador (Directo, Indirecto, etc)',
                'field_type' => 'list',
                'step' => '5',
                'required' => "Si",
                'daily_sheet_id' => $personalSheet->id,
            ]);
            Field::create([
                'name' => 'Cuadrilla o Grupo',
                'description' => 'Cuadrilla o Grupo del trabajador',
                'field_type' => 'list',
                'step' => '6',
                'required' => "No",
                'daily_sheet_id' => $personalSheet->id,
            ]);
            Field::create([
                'name' => 'Jornada',
                'description' => 'Jornada del trabajador (10x5, 5x2, etc)',
                'field_type' => 'list',
                'step' => '7',
                'required' => "Si",
                'daily_sheet_id' => $personalSheet->id,
            ]);
            Field::create([
                'name' => 'Turno',
                'description' => 'Turno del trabajador (Diurno, nocturno)',
                'field_type' => 'list',
                'step' => '8',
                'required' => "Si",
                'daily_sheet_id' => $personalSheet->id,
            ]);
            Field::create([
                'name' => 'Estado Personal',
                'description' => 'Estado del trabajador (Trabajando, Licencia, etc)',
                'field_type' => 'list',
                'step' => '9',
                'required' => "Si",
                'daily_sheet_id' => $personalSheet->id,
            ]);
            Field::create([
                'name' => 'Área de Trabajo',
                'description' => 'Área de trabajo donde se desempeñó el trabajador',
                'field_type' => 'list',
                'step' => '10',
                'required' => "Si",
                'daily_sheet_id' => $personalSheet->id,
            ]);
            Field::create([
                'name' => 'HH Trabajadas',
                'description' => 'HH Trabajadas por el trabajador',
                'field_type' => 'integer',
                'step' => '11',
                'required' => "Si",
                'daily_sheet_id' => $personalSheet->id,
            ]);
            Field::create([
                'name' => 'Comentarios EECC',
                'description' => 'Comentarios de la Empresa colaboradora',
                'field_type' => 'text',
                'step' => '12',
                'required' => "No",
                'daily_sheet_id' => $personalSheet->id,
            ]);
            Field::create([
                'name' => 'Comentarios Codelco',
                'description' => 'Comentarios del equipo de Codelco',
                'field_type' => 'text',
                'step' => '13',
                'required' => "No",
                'daily_sheet_id' => $personalSheet->id,
            ]);

    //* Fin Personal            


    //* Maquinarias

            $maquinariaSheet = DailySheet::create([
                'name' => 'Maquinarias',
                'step' => '2',
                'daily_structure_id' => $dailyStructure->id,
            ]);
            Field::create([
                'name' => 'Patente o Identificación',
                'description' => 'Patente o Identificación de la maquinaria',
                'field_type' => 'text',
                'step' => '1',
                'required' => "Si",
                'daily_sheet_id' => $maquinariaSheet->id,
            ]);
            Field::create([
                'name' => 'Tipo de Equipo',
                'description' => 'Tipo de equipo ',
                'field_type' => 'list',
                'step' => '2',
                'required' => "Si",
                'daily_sheet_id' => $maquinariaSheet->id,
            ]);
            Field::create([
                'name' => 'Modelo de Equipo',
                'description' => 'Modelo de equipo',
                'field_type' => 'text',
                'step' => '3',
                'required' => "No",
                'daily_sheet_id' => $maquinariaSheet->id,
            ]);
            Field::create([
                'name' => 'Turno Equipo',
                'description' => 'Turno de la maquinaria (Diurno, Nocturno)',
                'field_type' => 'list',
                'step' => '4',
                'required' => "Si",
                'daily_sheet_id' => $maquinariaSheet->id,
            ]);
            Field::create([
                'name' => 'Operador Presente',
                'description' => 'Estuvo el operador presente de la maquinaria',
                'field_type' => 'list',
                'step' => '5',
                'required' => "Si",
                'daily_sheet_id' => $maquinariaSheet->id,
            ]);
            Field::create([
                'name' => 'Área de Trabajo',
                'description' => 'Área de trabajo donde se desempeñó la maquinaria',
                'field_type' => 'list',
                'step' => '6',
                'required' => "Si",
                'daily_sheet_id' => $maquinariaSheet->id,
            ]);
            Field::create([
                'name' => 'Horas Operativas',
                'description' => 'Horas en que el equipo se encuentra entregada a su(s) operador(es), en condiciones óptimas y cumpliendo su tarea asignada.',
                'field_type' => 'integer',
                'step' => '7',
                'required' => "Si",
                'daily_sheet_id' => $maquinariaSheet->id,
            ]);
            Field::create([
                'name' => 'Horas No Operativas',
                'description' => 'Horas en que el equipo, estando disponible, no es operado.',
                'field_type' => 'integer',
                'step' => '8',
                'required' => "Si",
                'daily_sheet_id' => $maquinariaSheet->id,
            ]);
            Field::create([
                'name' => 'Horas Mantención Programada',
                'description' => 'Horas en que el equipo estuvo en una mantención programada',
                'field_type' => 'integer',
                'step' => '8',
                'required' => "Si",
                'daily_sheet_id' => $maquinariaSheet->id,
            ]);
            Field::create([
                'name' => 'Horas Equipo en Panne',
                'description' => 'Horas en el que el equipo estuvo con falla o en panne.',
                'field_type' => 'integer',
                'step' => '9',
                'required' => "Si",
                'daily_sheet_id' => $maquinariaSheet->id,
            ]);
            Field::create([
                'name' => 'Comentarios EECC',
                'description' => 'Comentarios de la Empresa colaboradora',
                'field_type' => 'text',
                'step' => '10',
                'required' => "No",
                'daily_sheet_id' => $maquinariaSheet->id,
            ]);
            Field::create([
                'name' => 'Comentarios Codelco',
                'description' => 'Comentarios del equipo de Codelco',
                'field_type' => 'text',
                'step' => '11',
                'required' => "No",
                'daily_sheet_id' => $maquinariaSheet->id,
            ]);
    //* Fin Maquinarias

            //* Interferencias   
            $interferenciasSheet = DailySheet::create([
                'name' => 'Interferencias',
                'step' => '3',
                'daily_structure_id' => $dailyStructure->id,
            ]);

            Field::create([
                'name' => 'Categoría',
                'description' => 'Categoria de la interferencia',
                'field_type' => 'list',
                'step' => '1',
                'required' => "Si",
                'daily_sheet_id' => $interferenciasSheet->id,
            ]);
            Field::create([
                'name' => 'Responsable',
                'description' => 'Responsable de la interferencia (EECC, Codelco, Otro)',
                'field_type' => 'list',
                'step' => '3',
                'required' => "Si",
                'daily_sheet_id' => $interferenciasSheet->id,
            ]);
            Field::create([
                'name' => 'Hora Inicio',
                'description' => 'Hora de inicio de la interferencia',
                'field_type' => 'hour',
                'step' => '4',
                'required' => "Si",
                'daily_sheet_id' => $interferenciasSheet->id,
            ]);
            Field::create([
                'name' => 'Hora Fin',
                'description' => 'Hora de fin de la interferencia',
                'field_type' => 'hour',
                'step' => '5',
                'required' => "Si",
                'daily_sheet_id' => $interferenciasSheet->id,
            ]);
            Field::create([
                'name' => 'Cantidad Personal Involucrado',
                'description' => 'Cantidad de personal involucrado en la interferencia',
                'field_type' => 'integer',
                'step' => '6',
                'required' => "Si",
                'daily_sheet_id' => $interferenciasSheet->id,
            ]);
            Field::create([
                'name' => 'HH Totales',
                'description' => 'HH totales de la interferencia',
                'field_type' => 'integer',
                'step' => '7',
                'required' => "Si",
                'daily_sheet_id' => $interferenciasSheet->id,
            ]);
            Field::create([
                'name' => 'HM Totales',
                'description' => 'Horas maquinas totales de la interferencia',
                'field_type' => 'integer',
                'step' => '8',
                'required' => "Si",
                'daily_sheet_id' => $interferenciasSheet->id,
            ]);
            Field::create([
                'name' => 'Descripción',
                'description' => 'Descripción de la interferencia',
                'field_type' => 'text',
                'step' => '9',
                'required' => "Si",
                'daily_sheet_id' => $interferenciasSheet->id,
            ]);
            Field::create([
                'name' => 'Comentarios EECC',
                'description' => 'Comentarios de la Empresa colaboradora',
                'field_type' => 'text',
                'step' => '10',
                'required' => "No",
                'daily_sheet_id' => $interferenciasSheet->id,
            ]);
            Field::create([
                'name' => 'Comentarios Codelco',
                'description' => 'Comentarios del equipo de Codelco',
                'field_type' => 'text',
                'step' => '11',
                'required' => "No",
                'daily_sheet_id' => $interferenciasSheet->id,
            ]);
            //* Fin Interferencias


            //*crear Dailys por cada fecha 
            $start = Carbon::createFromFormat('Y-m-d', $validatedData['start_date']);
            $end = Carbon::createFromFormat('Y-m-d', $validatedData['end_date']);

            while ($start->lte($end)) {
                //  $out->writeln("Hello from dddd" .$start->format('Y-m-d'));
                try {
                    $dailys = Dailys::create([
                        'date' => $start->format('Y-m-d'),
                        'state_id' => 1,
                        'contract_id' => $contract->id,
                        'daily_structure_id' => $dailyStructure->id,
                        'revision' => 0,
                    ]);
                } catch (\Exception $e) {
                    $out->writeln("error" . $e->getMessage());
                    return response()->json(['message' => 'Error al los dailys de los contratos', 'error' => $e->getMessage()], 500);
                }
                $start->addDay();
            }
            //*Fin Dailys por cada fecha 
            $out->writeln("structuresss" . $validatedData['start_date']);
            DB::commit();

            return response()->json(['message' => 'Contrato creado con éxito'], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error al crear el contrato', 'error' => $e->getMessage()], 500);
        }
    }
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $contract = Contract::with([
            'company:id,name,rut_number,rut_verifier',
            'users' => function ($query) {
                $query->withPivot('role_id')
                    ->leftJoin('roles', 'contract_user.role_id', '=', 'roles.id')
                    ->select('users.id', 'users.name', 'roles.name as role_name', 'contract_user.contract_id');
            }
        ])->findOrFail($id);

        $formattedContract = [
            'id' => $contract->id,
            'nombre_contrato' => $contract->name_contract,
            'NSAP' => $contract->NSAP,
            'DEN' => $contract->DEN,
            'proyecto' => $contract->project,
            'API' => $contract->API,
            'revisorPYCRequired' => (bool) $contract->is_revisor_pyc_required,
            'revisorCCRequired' => (bool) $contract->is_revisor_cc_required,
            'revisorOtraAreaRequired' => (bool) $contract->is_revisor_other_area_required,
            'fecha_inicio' => $contract->start_date,
            'fecha_fin' => $contract->end_date,
            'empresa_contratista' => $contract->company->id,
            'encargadoContratista' => $contract->users->where('role_name', 'encargado_contratista')->values(),
            'adminTerreno' => $contract->users->where('role_name', 'admin_terreno')->values(),
            'visualizador' => $contract->users->where('role_name', 'visualizador')->values(),
            'revisorPYC' => $contract->users->where('role_name', 'revisor_pyc')->values(),
            'revisorCC' => $contract->users->where('role_name', 'revisor_cc')->values(),
            'revisorOtraArea' => $contract->users->where('role_name', 'revisor_otra_area')->values(),
            'encargadoCodelco' => $contract->users->where('role_name', 'encargado_codelco')->values(),
        ];

        return response()->json($formattedContract, 200);
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
    public function update(StoreContractRequest $request, string $id)
    {
        $validatedData = $request->validated();
        DB::beginTransaction();

        try {
            $contract = Contract::findOrFail($id);
            $contract->update([
                'name_contract' => $validatedData['name_contract'],
                'NSAP' => $validatedData['NSAP'],
                'DEN' => $validatedData['DEN'],
                'project' => $validatedData['project'],
                'API' => $validatedData['API'],
                'start_date' => $validatedData['start_date'],
                'end_date' => $validatedData['end_date'],
                'id_company' => $validatedData['id_company'],
                'created_by' => $validatedData['created_by'],
                'visualizador' => $validatedData['visualizador'],
                'is_revisor_pyc_required' => $validatedData['revisorPYCRequired'] ?? false,
                'is_revisor_cc_required' => $validatedData['revisorCCRequired'] ?? false,
                'is_revisor_other_area_required' => $validatedData['revisorOtraAreaRequired'] ?? false,
            ]);

            $this->syncRoles($contract, 'revisor_pyc', $validatedData['revisorPYC'] ?? []);
            $this->syncRoles($contract, 'revisor_cc', $validatedData['revisorCC'] ?? []);
            $this->syncRoles($contract, 'revisor_otra_area', $validatedData['revisorOtraArea'] ?? []);
            $this->syncRoles($contract, 'admin_terreno', $validatedData['adminDeTerreno'] ?? []);
            $this->syncRoles($contract, 'encargado_contratista', $validatedData['encargadoContratista'] ?? []);
            $this->syncRoles($contract, 'visualizador', $validatedData['visualizador'] ?? []);
            $this->syncRoles($contract, 'encargado_codelco', $validatedData['encargadoCodelco'] ?? []);

            DB::commit();

            return response()->json(['message' => 'Contrato actualizado con éxito'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error al actualizar el contrato', 'error' => $e->getMessage()], 500);
        }
    }


    // Obtener las dailysheets vigentes y sus fields 
    public function getStructureVigentes($id)
    {
        try {
            $out = new \Symfony\Component\Console\Output\ConsoleOutput();

            $contract = Contract::findOrFail($id);
            // $out->writeln("contract" . $contract);

            $dailyStructure = $contract->dailyStructure()->where('vigente', true)->first();
            // $out->writeln("dailyStructure" . $dailyStructure);

            $dailySheets = $dailyStructure->dailySheets()->orderBy('step')->get();
            //$out->writeln("dailysheets" . $dailySheets);

            $steps = [];
            foreach ($dailySheets as $sheet) {
                $fields = $sheet->fields()->orderBy('step')->get();
                $step = [
                    'idSheet' => $sheet->id,
                    'sheet' => $sheet->name,
                    'fields' => $fields->map(function ($field) {
                        $dropdownLists = DropdownLists::where('field_id', $field->id)->get();
                        $field->dropdown_lists = $dropdownLists;
                        return $field;
                    }),
                    'step' => $sheet->step,
                ];
                $steps[] = $step;
            }
            //$out->writeln("dailysheets" . $dailySheets)
            return response()->json([
                'steps' => $steps,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener estructura del contrato', 'message' => $e->getMessage()], 500);
        }
    }


    // obtener las dailysheets y sus fields de un Daily en particular
    public function getEstructureDaily($id)
    {
        //el parametro id es el id de la daily
        try {
            $out = new \Symfony\Component\Console\Output\ConsoleOutput();

            $Daily = Dailys::findOrFail($id);
          //  $out->writeln("Daily" . $Daily);

            try {
                $dailyStructure = $Daily->dailyStructure()->first();
            } catch (\Exception $e) {
                $out->writeln("dailyStructure" . $e->getMessage());
                \Log::error("Error al obtener la primera estructura diaria: " . $e->getMessage());
                // Aquí puedes decidir cómo manejar el error, por ejemplo, devolver una respuesta o lanzar una excepción personalizada
            }
          //  $out->writeln("dailyStructure" . $dailyStructure);

            $dailySheets = $dailyStructure->dailySheets()->orderBy('step')->get();

            $steps = [];
            foreach ($dailySheets as $sheet) {
                $fields = $sheet->fields()->with('values')->orderBy('step')->get();

                $fields =  $fields->map(function ($field) {
                    $dropdownLists = DropdownLists::where('field_id', $field->id)->get();
                    $field->dropdown_lists = $dropdownLists;
                    return $field;});
              
                $out->writeln("fields" . $fields);


                $step = [
                    'idSheet' => $sheet->id,
                    'sheet' => $sheet->name,
                    'fields' => $fields,
                ];
                $steps[] = $step;
            }

            return response()->json([
                'steps' => $steps,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener estructura del contrato', 'message' => $e->getMessage()], 500);
        }
    }



    private function syncRoles($contract, $roleName, $userIds)
    {
        $roleId = Role::where('name', $roleName)->first()->id;
        $contract->users()->wherePivot('role_id', $roleId)->detach();

        foreach ($userIds as $userId) {
            $contract->users()->attach($userId, ['role_id' => $roleId]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $contract = Contract::findOrFail($id);
            $contract->delete();

            return response()->json(['message' => 'Contrato eliminado exitosamente']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al eliminar el contrato', 'error' => $e->getMessage()], 500);
        }
    }
}
