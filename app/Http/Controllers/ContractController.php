<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreContractRequest;
use Spatie\Permission\Models\Role;
use App\Models\Contract;



class ContractController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $perPage = $request->get('per_page', 10); // Obtener el número de registros por página desde la solicitud, o usar 10 como valor predeterminado
    $contracts = Contract::with([
        'company:id,name,rut_number,rut_verifier',
        'users' => function ($query) {
            $query->withPivot('role_id')
                ->leftJoin('roles', 'contract_user.role_id', '=', 'roles.id')
                ->select('users.id', 'users.name', 'roles.name as role_name', 'contract_user.contract_id');
        }
    ])->paginate($perPage);

    // Transformar los contratos en el formato deseado antes de devolver la respuesta
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
            // Crear el contrato
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
    
            // Asignar revisores
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
    // Recuperar el contrato por su ID junto con sus relaciones
    $contract = Contract::with([
        'company:id,name,rut_number,rut_verifier',
        'users' => function ($query) {
            $query->withPivot('role_id')
                ->leftJoin('roles', 'contract_user.role_id', '=', 'roles.id')
                ->select('users.id', 'users.name', 'roles.name as role_name', 'contract_user.contract_id');
        }
    ])->findOrFail($id);

    // Formatear los datos del contrato
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
    
            // Actualizar usuarios asociados (roles)
            $this->syncRoles($contract, 'revisor_pyc', $validatedData['revisorPYC'] ?? []);
            $this->syncRoles($contract, 'revisor_cc', $validatedData['revisorCC'] ?? []);
            $this->syncRoles($contract, 'revisor_otra_area', $validatedData['revisorOtraArea'] ?? []);
            $this->syncRoles($contract, 'admin_terreno', $validatedData['adminDeTerreno'] ?? []);
            $this->syncRoles($contract, 'encargado_contratista', $validatedData['encargadoContratista'] ?? []);
            $this->syncRoles($contract, 'visualizador', $validatedData['visualizador'] ?? []);
    
            DB::commit();
    
            return response()->json(['message' => 'Contrato actualizado con éxito'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error al actualizar el contrato', 'error' => $e->getMessage()], 500);
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
