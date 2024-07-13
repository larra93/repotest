<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Field;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;



class FieldController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

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
    public function store(Request $request, string $id)
    {
        DB::beginTransaction();
        $out = new \Symfony\Component\Console\Output\ConsoleOutput();
        $out->writeln("field request" . $request->input('name'));

        try {
            $field = Field::create([
               'name' => $request->input('name'),
                'description' => $request->input('description'),
                'field_type' => $request->input('field_type'),
                'step' => $request->input('step'),
                'daily_sheet_id' => $id
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al crear la field', 'message' => $e->getMessage()], 500);
        }

        DB::commit();
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
        DB::beginTransaction();

        try {
            $field = Field::findOrFail($id);
            $field->update([
               'name' => $request->input('name'),
                'description' => $request->input('description'),
                'field_type' => $request->input('field_type'),
                'step' => $request->input('step')
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al crear la field', 'message' => $e->getMessage()], 500);
        }
        DB::commit();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
