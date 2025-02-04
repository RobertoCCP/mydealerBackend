<?php

namespace App\Http\Controllers\Configuracion;

use App\Http\Controllers\Controller;
use App\Models\HorarioGPS\GpsHorario;
use Illuminate\Http\Request;

class GpsHorarioController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/gpshorarios",
     *     tags={"Web - Horario GPS"},
     *     summary="Obtener todos los horarios GPS",
     *     @OA\Response(
     *         response=200,
     *         description="Operación exitosa",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="nombredia", type="string"),
     *                 @OA\Property(property="horaini", type="string", format="time"),
     *                 @OA\Property(property="horafin", type="string", format="time"),
     *                 @OA\Property(property="frecuenciatoma", type="integer")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error del servidor"
     *     )
     * )
     */
    public function index()
    {
        $gpsHorario = GpsHorario::all();
        return response()->json($gpsHorario);
    }

    /**
     * @OA\Get(
     *     path="/api/gpshorarios/{id}",
     *     tags={"Web - Horario GPS"},
     *     summary="Obtener un horario GPS por ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Operación exitosa",
     *         @OA\JsonContent(
     *             @OA\Property(property="nombredia", type="string"),
     *             @OA\Property(property="horaini", type="string", format="time"),
     *             @OA\Property(property="horafin", type="string", format="time"),
     *             @OA\Property(property="frecuenciatoma", type="integer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Horario GPS no encontrado"
     *     )
     * )
     */
    public function show($id)
    {
        return GpsHorario::findOrFail($id);
    }

    /**
     * @OA\Put(
     *     path="/api/gpshorarios/{id}",
     *     tags={"Web - Horario GPS"},
     *     summary="Actualizar un horario GPS por ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nombredia", "frecuenciatoma"},
     *             @OA\Property(property="nombredia", type="string"),
     *             @OA\Property(property="horaini", type="string", format="time"),
     *             @OA\Property(property="horafin", type="string", format="time"),
     *             @OA\Property(property="frecuenciatoma", type="integer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Operación exitosa",
     *         @OA\JsonContent(
     *             @OA\Property(property="nombredia", type="string"),
     *             @OA\Property(property="horaini", type="string", format="time"),
     *             @OA\Property(property="horafin", type="string", format="time"),
     *             @OA\Property(property="frecuenciatoma", type="integer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Horario GPS no encontrado"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nombredia' => 'required|string|max:15',
            'horaini' => 'nullable|date_format:H:i:s',
            'horafin' => 'nullable|date_format:H:i:s',
            'frecuenciatoma' => 'required|integer',
        ]);

        $gpshorario = GpsHorario::findOrFail($id);
        $gpshorario->update($validatedData);

        return response()->json($gpshorario, 200);
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nombredia' => 'required|string|max:15',
            'horaini' => 'nullable|date_format:H:i:s',
            'horafin' => 'nullable|date_format:H:i:s',
            'frecuenciatoma' => 'required|integer',
        ]);

        $gpshorario = GpsHorario::create($validatedData);

        return response()->json($gpshorario, 201);
    }

}
