<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RolPermisos;
use App\Models\EmpleadoRoles;
use App\Models\Rol;
use App\Models\Permiso;
use App\Respuestas\Respuestas;
use Illuminate\Support\Facades\Validator;

class RolesController extends Controller
{
    public function consultarRolesPermisos()
    {
        $rolesPermisos = RolPermisos::join('roles', 'roles.id', '=', 'roles_permisos.idRol')
            ->join('permisos', 'permisos.id', '=', 'roles_permisos.idPermiso')
            ->select(
                'roles_permisos.*',
                'roles.nombre AS nombreRol',
                'permisos.nombre AS nombrePermiso'
            )
            ->get();

        $roles = Rol::all();
        $permisos = Permiso::all();

        $respuesta = [
            "rolesPermisos" => $rolesPermisos,
            "roles" => $roles,
            "permisos" => $permisos,
        ];

        return response()->json(
            Respuestas::respuesta200(
                'Consulta exitosa.',
                $respuesta
            )
        );
    }

    public function guardarRol(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required',
        ]);

        if ($validator->fails())
            return response()->json(Respuestas::respuesta400($validator->errors()), 400);

        Rol::create($request->all());

        return $this->consultarRolesPermisos();
    }

    public function actualizarRol(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'nombre' => 'nullable',
        ]);

        if ($validator->fails())
            return response()->json(Respuestas::respuesta400($validator->errors()), 400);

        $datosActualizado = [
            'id' => $request->id,
            'nombre' => $request->nombre
        ];

        $datosActualizado = array_filter($datosActualizado);

        Rol::where('id', $request->id)->update($datosActualizado);

        return $this->consultarRolesPermisos();
    }

    public function eliminarRol($id)
    {
        if (!isset($id))
            return response()->json(Respuestas::respuesta400('No se envio el id del Rol'), 400);

        $rol = Rol::find($id);
        RolPermisos::where('idRol', $id)->delete();
        EmpleadoRoles::where('idRol', $id)->delete();

        if (!$rol)
            return response()->json(Respuestas::respuesta400('No se encontro el rol'), 400);

        $rol->delete();

        return $this->consultarRolesPermisos();
    }

    public function guardarRolPermisos(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'permisos' => 'required|array',
        ]);

        if ($validator->fails())
            return response()->json(Respuestas::respuesta400($validator->errors()), 400);

        $permisos = $request->permisos;

        foreach ($permisos as $permiso) {
            $datosEntrada = [
                'idRol' => $permiso['idRol'],
                'idPermiso' => $permiso['id'],
            ];

            RolPermisos::create($datosEntrada);
        }

        return $this->consultarRolesPermisos();
    }

    public function eliminarRolPermisos(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'permisos' => 'required|array',
        ]);

        if ($validator->fails())
            return response()->json(Respuestas::respuesta400($validator->errors()), 400);

        $permisos = $request->permisos;

        foreach ($permisos as $permiso) {
            $permisoEliminar = RolPermisos::find($permiso['id']);

            if (isset($permisoEliminar)) $permisoEliminar->delete();
        }

        return $this->consultarRolesPermisos();
    }
}
