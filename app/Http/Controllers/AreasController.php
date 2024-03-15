<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Area;
use App\Models\Puestos;
use App\Models\Empleado;
use App\Respuestas\Respuestas;
use Illuminate\Support\Facades\Validator;

class AreasController extends Controller
{

    public function crearAreas(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'area' => 'required',
            'descripcion' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(Respuestas::respuesta400($validator->errors()), 400);
        }

        Area::create($request->all());
        $areas = Area::all();

        return response()->json(Respuestas::respuesta200('Área creada.', $areas), 201);
    }

    public function actualizarArea(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'area' => 'nullable',
            'descripcion' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json(Respuestas::respuesta400($validator->errors()), 400);
        }

        $datosActualizado = [
            'id' => $request->id,
            'area' => $request->area,
            'descripcion' => $request->descripcion
        ];

        $datosActualizado = array_filter($datosActualizado);

        Area::where('id', $request->id)
            ->update($datosActualizado);

        $areas = Area::all();

        return response()->json(Respuestas::respuesta200('área actualizada correctamente.', $areas));
    }



    public function consultarAreas()
    {
        $areas = Area::all();

        return response()->json(
            Respuestas::respuesta200(
                'Consulta exitosa.',
                $areas
            )
        );
    }

    public function eliminarArea($id)
    {
        $area = Area::find($id);

        if (!$area)
            return response()->json(Respuestas::respuesta404('Área no encontrada'));

        Puestos::where('id_area', $id)->update(['id_area' => 1]);
        Empleado::where('area_id', $id)->update(['area_id' => 1]);

        $area->delete();
        $areas = Area::all();

        return response()->json(Respuestas::respuesta200('Área eliminada', $areas));
    }
}
