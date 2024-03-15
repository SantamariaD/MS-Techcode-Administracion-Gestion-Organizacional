<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Puestos;
use App\Models\Empleado;
use App\Respuestas\Respuestas;
use Illuminate\Support\Facades\Validator;

class PuestosController extends Controller
{

    public function crearPuesto(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'id_area' => 'required',
            'puesto' => 'required',
            'descripcion' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(Respuestas::respuesta400($validator->errors()));
        }

        Puestos::create($request->all());
        $puestos = Puestos::join(
            'areas',
            'areas.id',
            '=',
            'puestos.id_area'
        )
            ->select('puestos.*', 'areas.area')
            ->orderBy('id_area', 'asc')
            ->get();

        return response()->json(Respuestas::respuesta200('Puesto creado.', $puestos), 201);
    }

    public function actualizarPuesto(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'id_area' => 'nullable',
            'puesto' => 'nullable',
            'descripcion' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json(Respuestas::respuesta400($validator->errors()), 400);
        }

        $datosActualizado = [
            'id_area' => $request->id_area,
            'puesto' => $request->puesto,
            'descripcion' => $request->descripcion
        ];

        $datosActualizado = array_filter($datosActualizado);

        Puestos::where('id', $request->id)
            ->update($datosActualizado);

        $puestos = Puestos::join(
            'areas',
            'areas.id',
            '=',
            'puestos.id_area'
        )
            ->select('puestos.*', 'areas.area')
            ->orderBy('id_area', 'asc')
            ->get();

        return response()->json(Respuestas::respuesta200('Puesto actualizado correctamente.', $puestos));
    }


    public function consultarPuestos()
    {
        $puestos = Puestos::join(
            'areas',
            'areas.id',
            '=',
            'puestos.id_area'
        )
            ->select('puestos.*', 'areas.area')
            ->orderBy('id_area', 'asc')
            ->get();

        return response()->json(
            Respuestas::respuesta200(
                'Consulta exitosa.',
                $puestos
            )
        );
    }

    public function consultarPuestosPorArea($id_area)
    {

        $puestos = Puestos::where('id_area', $id_area)->get();

        if (count($documentos) < 1) {
            return response()->json(Respuestas::respuesta400('No hay puestos relacionados a esta área.'));
        }

        return response()->json(
            Respuestas::respuesta200(
                'Consulta exitosa.',
                $puestos
            )
        );
    }

    public function eliminarPuesto($id)
    {
        $puesto = Puestos::find($id);

        if (!$puesto)
            return response()->json(Respuestas::respuesta404('Puesto no encontrada'));

        Empleado::where('area_id', $id)->update(['area_id' => 1]);

        $puesto->delete();
        $puestos = Puestos::join(
            'areas',
            'areas.id',
            '=',
            'puestos.id_area'
        )
            ->select('puestos.*', 'areas.area')
            ->orderBy('id_area', 'asc')
            ->get();

        return response()->json(
            Respuestas::respuesta200(
                'Se elimino correctamente el puesto.',
                $puestos
            )
        );
    }
}
