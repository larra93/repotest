<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dailys;
use Illuminate\Support\Facades\Hash;


class DailysController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
       // $out = new \Symfony\Component\Console\Output\ConsoleOutput();
        $perPage = $request->get('per_page', 5);
        $dailys = Dailys::join('states', 'dailys.state_id', '=', 'states.id')
    ->select('dailys.*', 'states.name AS state_name')
    ->paginate($perPage);
//    $out->writeln($dailys );
        return response()->json($dailys);
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
        //
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
