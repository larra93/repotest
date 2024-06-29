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
    public function index()
    {
        $contracts = Contract::with([
            'users' => function($query) {
                $query->withPivot('role_id')
                      ->leftJoin('roles', 'contract_user.role_id', '=', 'roles.id')
                      ->select('users.*', 'roles.name as role_name');
            }
        ])->get();

        $contracts->transform(function($contract) {
            $contract->encargadoContratista = $contract->users->where('role_name', 'encargado_contratista');
            $contract->adminDeContrato = $contract->users->where('role_name', 'admin_contrato');
            $contract->revisorPYC = $contract->users->where('role_name', 'revisor_pyc');
            $contract->revisorCC = $contract->users->where('role_name', 'revisor_cc');
            $contract->revisorOtraArea = $contract->users->where('role_name', 'revisor_otra_area');
            unset($contract->users);
            return $contract;
        });

        return response()->json($contracts, 200);
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
                'CC' => $validatedData['CC'],
                'is_revisor_pyc_required' => $validatedData['is_revisor_pyc_required'] ?? false,
                'is_revisor_cc_required' => $validatedData['is_revisor_cc_required'] ?? false,
                'is_revisor_other_area_required' => $validatedData['is_revisor_other_area_required'] ?? false,
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
    
            if (isset($validatedData['adminDeContrato'])) {
                $roleId = Role::where('name', 'admin_contrato')->first()->id;
                foreach ($validatedData['adminDeContrato'] as $userId) {
                    $contract->users()->attach($userId, ['role_id' => $roleId]);
                }
            }
            if (isset($validatedData['encargadoContratista'])) {
                $roleId = Role::where('name', 'encargado_contratista')->first()->id;
                foreach ($validatedData['encargadoContratista'] as $userId) {
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
