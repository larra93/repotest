<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        return response()->json(['message' => 'LLego'], 201);
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
    public function store(Request $request)
    {
        // $validatedData = $request->validated();

        $contract = Contract::create([
            'name_contract' => $request->input('contractName'),
            'NSAP' => $request->input('NSAP'),
            'DEN' => $request->input('DEN'),
            'project' => $request->input('project'),
            'API' => $request->input('API'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'id_company' => $request->input('id_company'),
            'created_by' => $request->input('created_by'),
            'CC' => $request->input('cc'),
            'is_revisor_pyc_required' => $request->input('is_revisor_pyc_required') ?? false,
            'is_revisor_cc_required' => $request->input('is_revisor_cc_required') ?? false,
            'is_revisor_other_area_required' => $request->input('is_revisor_other_area_required') ?? false,
        ]);
    
        // Asignar revisores
        if ($request->has('revisor_pyc')) {
            $roleId = Role::where('name', 'revisor_pyc')->first()->id;
            foreach ($request->input('revisor_pyc') as $userId) {
                $contract->users()->attach($userId, ['role_id' => $roleId]);
            }
        }
    
        if ($request->has('revisor_cc')) {
            $roleId = Role::where('name', 'revisor_cc')->first()->id;
            foreach ($request->input('revisor_cc') as $userId) {
                $contract->users()->attach($userId, ['role_id' => $roleId]);
            }
        }
    
        if ($request->has('revisor_other_area')) {
            $roleId = Role::where('name', 'revisor_otra_area')->first()->id;
            foreach ($request->input('revisor_other_area') as $userId) {
                $contract->users()->attach($userId, ['role_id' => $roleId]);
            }
        }
    
        if ($request->has('admin_contract')) {
            $roleId = Role::where('name', 'admin_contrato')->first()->id;
            foreach ($request->input('admin_contract') as $userId) {
                $contract->users()->attach($userId, ['role_id' => $roleId]);
            }
        }

        // Crear el contrato
        // $contract = Contract::create([
        //     'name_contract' => $validatedData['name_contract'],
        //     'NSAP' => $validatedData['NSAP'],
        //     'DEN' => $validatedData['DEN'],
        //     'project' => $validatedData['project'],
        //     'API' => $validatedData['API'],
        //     'start_date' => $validatedData['start_date'],
        //     'end_date' => $validatedData['end_date'],
        //     'id_company' => $validatedData['id_company'],
        //     'created_by' => $validatedData['created_by'],
        //     'cc' => $validatedData['cc'],
        //     'is_revisor_pyc_required' => $validatedData['is_revisor_pyc_required'] ?? false,
        //     'is_revisor_cc_required' => $validatedData['is_revisor_cc_required'] ?? false,
        //     'is_revisor_other_area_required' => $validatedData['is_revisor_other_area_required'] ?? false,
        // ]);

        // // Asignar revisores
        // if (isset($validatedData['revisor_pyc'])) {
        //     $roleId = Role::where('name', 'revisor_pyc')->first()->id;
        //     foreach ($validatedData['revisor_pyc'] as $userId) {
        //         $contract->users()->attach($userId, ['role_id' => $roleId]);
        //     }
        // }

        // if (isset($validatedData['revisor_cc'])) {
        //     $roleId = Role::where('name', 'revisor_cc')->first()->id;
        //     foreach ($validatedData['revisor_cc'] as $userId) {
        //         $contract->users()->attach($userId, ['role_id' => $roleId]);
        //     }
        // }

        // if (isset($validatedData['revisor_other_area'])) {
        //     $roleId = Role::where('name', 'revisor_otra_area')->first()->id;
        //     foreach ($validatedData['revisor_other_area'] as $userId) {
        //         $contract->users()->attach($userId, ['role_id' => $roleId]);
        //     }
        // }

        // if (isset($validatedData['admin_contract'])) { 
        //     $roleId = Role::where('name', 'admin_contrato')->first()->id;
        //     foreach ($validatedData['admin_contract'] as $userId) {
        //         $contract->users()->attach($userId, ['role_id' => $roleId]);
        //     }
        
        return response()->json(['message' => 'Contrato creado con éxito'], 201);
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
