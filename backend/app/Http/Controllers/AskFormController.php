<?php

namespace App\Http\Controllers;

use App\Models\AskForm;
use Illuminate\Http\Request;

class AskFormController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Obtengo el último registro de la tabla
        $askForm = AskForm::latest()->first();
        $askForm->makeHidden(['user_id']);
        return response()->json($askForm);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $askForm = AskForm::create([
        'ask_one' => $request->input('ask_one'),
            'ask_two' => $request->input('ask_two'),
            'ask_three' => $request->input('ask_three'),
            'ask_four' => $request->input('ask_four'),
            'user_id' => auth()->user()->id
        ]);

        return response()->json($askForm);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $askForm = AskForm::find($id);

        if (!$askForm) {
            return response()->json(['error' => 'Registro no encontrado'], 404);
        }

        if (auth()->user()->role !== 'admin') {
            return response()->json(['error' => 'No tienes permiso para esta opción'], 403);
        }

        $askForm->update([
            'ask_one' => $request->input('ask_one'),
            'ask_two' => $request->input('ask_two'),
            'ask_three' => $request->input('ask_four'),
            'ask_four' => $request->input('ask_four')
        ]);

        return response()->json($askForm);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $askForm = AskForm::find($id);
        if (!$askForm) {
            return response()->json(['error' => 'Registro no encontrado'], 404);
        }
        if (auth()->user()->role !== 'admin') {
            return response()->json(['error' => 'No tienes permiso para esta acción'], 403);
        }
        $askForm->delete();
        return response()->json(['message' => 'Registro eliminado con exito'], 403);
    }
}
