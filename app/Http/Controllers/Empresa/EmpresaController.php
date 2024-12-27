<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Traits\HttpResponses;
use App\Http\Requests\Empresa\StoreEmpresaRequest;
use App\Models\Empresa;
use App\Http\Resources\Empresa\EmpresaResource;

class EmpresaController extends Controller
{

    use HttpResponses;

    /**
     * @OA\Get(
     * path="/api/datos/empresa",
     * tags={"Web - Datos Empresa"},
     * summary="Buscar datos de empresa",
     * description="Devuelve una lista con todos los datos y url de la empresa",
     * @OA\Response(
     *         response=200,
     *         description="successful operation",     
     *     ),
     * @OA\Response(
     *     response=500,
     *     description="Database error"
     * ),
     * )  
     */  
    public function index()
    {
        try {                        
            return $this->success(EmpresaResource::collection(Empresa::all()));                    
        } catch (\Exception $e) {            
            return $this->error('Error al obtener datos de empresa.', $e->getMessage(), 500);
        }
    }

    /**
     * @OA\Post(
     * path="/api/datos/empresa",
     * tags={"Web - Datos Empresa"},
     * summary="Añadir datos de empresa",
     * description="Devuelve la empresa con sus datos creados",
     * @OA\RequestBody(
     *     description="Datos de la empresa a crear",
     *     required=true,
     *     @OA\MediaType(
     *         mediaType="multipart/form-data",
     *         @OA\Schema(
     *             type="object",
     *             @OA\Property(property="nombre", type="string", maxLength=50, description="Nombre de la empresa"),
     *             @OA\Property(property="direccion", type="string", maxLength=255, description="Dirección de la empresa"),
     *             @OA\Property(property="telefono", type="string", maxLength=16, description="Teléfono de la empresa"),
     *             @OA\Property(property="pais", type="string", maxLength=60, description="País de la empresa"),
     *             @OA\Property(property="provincia", type="string", maxLength=60, description="Provincia de la empresa"),
     *             @OA\Property(property="ciudad", type="string", maxLength=60, description="Ciudad de la empresa"),
     *             @OA\Property(property="nombretienda", type="string", maxLength=50, description="Nombre de la tienda"),
     *             @OA\Property(property="emailsoporte", type="string", format="email", maxLength=100, description="Correo electrónico de soporte"),
     *             @OA\Property(property="emailorden", type="string", format="email", maxLength=100, description="Correo electrónico para órdenes"),
     *             @OA\Property(property="usuarioadmin", type="string", maxLength=8, description="Usuario administrador"),
     *             @OA\Property(property="claveadmin", type="string", maxLength=40, description="Clave del administrador"),
     *             @OA\Property(property="mensaje", type="string", maxLength=200, description="Mensaje de la empresa"),
     *             @OA\Property(property="cargo1", type="string", maxLength=50, description="Cargo del primer contacto"),
     *             @OA\Property(property="nombre1", type="string", maxLength=60, description="Nombre del primer contacto"),
     *             @OA\Property(property="email1", type="string", format="email", maxLength=100, description="Correo electrónico del primer contacto"),
     *             @OA\Property(property="foto1", type="string", format="binary", description="Foto del primer contacto"),
     *             @OA\Property(property="cargo2", type="string", maxLength=50, description="Cargo del segundo contacto"),
     *             @OA\Property(property="nombre2", type="string", maxLength=60, description="Nombre del segundo contacto"),
     *             @OA\Property(property="email2", type="string", format="email", maxLength=100, description="Correo electrónico del segundo contacto"),
     *             @OA\Property(property="foto2", type="string", format="binary", description="Foto del segundo contacto"),
     *             @OA\Property(property="cargo3", type="string", maxLength=50, description="Cargo del tercer contacto"),
     *             @OA\Property(property="nombre3", type="string", maxLength=60, description="Nombre del tercer contacto"),
     *             @OA\Property(property="email3", type="string", format="email", maxLength=100, description="Correo electrónico del tercer contacto"),
     *             @OA\Property(property="foto3", type="string", format="binary", description="Foto del tercer contacto"),
     *             @OA\Property(property="cargo4", type="string", maxLength=50, description="Cargo del cuarto contacto"),
     *             @OA\Property(property="nombre4", type="string", maxLength=60, description="Nombre del cuarto contacto"),
     *             @OA\Property(property="email4", type="string", format="email", maxLength=100, description="Correo electrónico del cuarto contacto"),
     *             @OA\Property(property="foto4", type="string", format="binary", description="Foto del cuarto contacto"),
     *             @OA\Property(property="fax", type="string", maxLength=15, description="Fax de la empresa"),
     *             @OA\Property(property="logo", type="string", format="binary", description="Logo de la empresa"),
     *             @OA\Property(property="logo_cabecera", type="string", format="binary", description="Logo de la cabecera de la empresa")
     *         )
     *     )
     * ),
     * @OA\Response(
     *     response=200,
     *     description="successful operation"
     * ),
     * @OA\Response(
     *     response=400,
     *     description="Bad request"
     * ),
     * @OA\Response(
     *     response=409,
     *     description="Dato duplicado"
     * ),
     * @OA\Response(
     *     response=500,
     *     description="Database error"
     * ),
     * )
     */
    public function store(StoreEmpresaRequest $request)
    {
        $validatedData = $request->validated();

        if (isset($validatedData['mensaje'])) {
            $validatedData['mensaje'] = base64_encode($validatedData['mensaje']);
        }

        $empresa = Empresa::create($validatedData);   
        $empresa = $this-> empresaFiles($request, $empresa);                             
        $empresa -> save();
        return $this->success(new EmpresaResource($empresa));
    }

    
    /**
     * @OA\Get(
     * path="/api/datos/empresa/{codempresa}",
     * tags={"Web - Datos Empresa"},
     * summary="Buscar datos de empresa específico",
     * description="Devuelve los datos de empresa por el codempresa",
     * 
     * @OA\Parameter(
     *      description="Necesita el codempresa",
     *      in="path",
     *      name="codempresa",
     *      required=true,
     * ),
     * 
     * @OA\Response(
     *         response=200,
     *         description="successful operation",     
     * ),
     * @OA\Response(
     *     response=404,
     *     description="Dato no encontrado"
     * ),
     * @OA\Response(
     *     response=500,
     *     description="Database error"
     * ),
     * )
     */
    public function show(string $codempresa)
    {
        try {                                            
            $empresa = Empresa::find($codempresa);
            if (!$empresa) {                     
                return $this->error("No data", "Datos no encontrado", 404);    
            }                                 
            return $this->success(new EmpresaResource($empresa));                
        } catch (\Exception $e) {            
            return $this->error('Error al obtener los datos de empresa.', $e->getMessage(), 500);
        }
    }

    /**
 * @OA\Put(
 * path="/api/datos/empresa",
 * tags={"Web - Datos Empresa"},
 * summary="Actualizar datos de empresa",
 * description="Actualiza y devuelve los datos de la empresa",
 * @OA\RequestBody(
 *         description="Datos de la empresa a actualizar",
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 type="object",
 *                 required={"metod_"},
 *                 @OA\Property(property="metod_", type="string", description="Método de la solicitud, debe ser PUT"),
 *                 @OA\Property(property="codempresa", type="integer", description="Código de la empresa"),
 *                 @OA\Property(property="nombre", type="string", maxLength=50, description="Nombre de la empresa"),
 *                 @OA\Property(property="direccion", type="string", maxLength=255, description="Dirección de la empresa"),
 *                 @OA\Property(property="telefono", type="string", maxLength=16, description="Teléfono de la empresa"),
 *                 @OA\Property(property="pais", type="string", maxLength=60, description="País de la empresa"),
 *                 @OA\Property(property="provincia", type="string", maxLength=60, description="Provincia de la empresa"),
 *                 @OA\Property(property="ciudad", type="string", maxLength=60, description="Ciudad de la empresa"),
 *                 @OA\Property(property="nombretienda", type="string", maxLength=50, description="Nombre de la tienda"),
 *                 @OA\Property(property="emailsoporte", type="string", format="email", maxLength=100, description="Correo electrónico de soporte"),
 *                 @OA\Property(property="emailorden", type="string", format="email", maxLength=100, description="Correo electrónico para órdenes"),
 *                 @OA\Property(property="usuarioadmin", type="string", maxLength=8, description="Usuario administrador"),
 *                 @OA\Property(property="claveadmin", type="string", maxLength=40, description="Clave del administrador"),
 *                 @OA\Property(property="mensaje", type="string", maxLength=200, description="Mensaje de la empresa"),
 *                 @OA\Property(property="cargo1", type="string", maxLength=50, description="Cargo del primer contacto"),
 *                 @OA\Property(property="nombre1", type="string", maxLength=60, description="Nombre del primer contacto"),
 *                 @OA\Property(property="email1", type="string", format="email", maxLength=100, description="Correo electrónico del primer contacto"),
 *                 @OA\Property(property="foto1", type="string", format="binary", description="Foto del primer contacto"),
 *                 @OA\Property(property="cargo2", type="string", maxLength=50, description="Cargo del segundo contacto"),
 *                 @OA\Property(property="nombre2", type="string", maxLength=60, description="Nombre del segundo contacto"),
 *                 @OA\Property(property="email2", type="string", format="email", maxLength=100, description="Correo electrónico del segundo contacto"),
 *                 @OA\Property(property="foto2", type="string", format="binary", description="Foto del segundo contacto"),
 *                 @OA\Property(property="cargo3", type="string", maxLength=50, description="Cargo del tercer contacto"),
 *                 @OA\Property(property="nombre3", type="string", maxLength=60, description="Nombre del tercer contacto"),
 *                 @OA\Property(property="email3", type="string", format="email", maxLength=100, description="Correo electrónico del tercer contacto"),
 *                 @OA\Property(property="foto3", type="string", format="binary", description="Foto del tercer contacto"),
 *                 @OA\Property(property="cargo4", type="string", maxLength=50, description="Cargo del cuarto contacto"),
 *                 @OA\Property(property="nombre4", type="string", maxLength=60, description="Nombre del cuarto contacto"),
 *                 @OA\Property(property="email4", type="string", format="email", maxLength=100, description="Correo electrónico del cuarto contacto"),
 *                 @OA\Property(property="foto4", type="string", format="binary", description="Foto del cuarto contacto"),
 *                 @OA\Property(property="fax", type="string", maxLength=15, description="Fax de la empresa"),
 *                 @OA\Property(property="logo", type="string", format="binary", description="Logo de la empresa"),
 *                 @OA\Property(property="logo_cabecera", type="string", format="binary", description="Logo de la cabecera de la empresa")
 *             )
 *         )
 * ),
 * @OA\Response(
 *     response=200,
 *     description="Operación exitosa",
 * ),
 * @OA\Response(
 *     response=400,
 *     description="Solicitud incorrecta"
 * ),
 * @OA\Response(
 *     response=409,
 *     description="Dato duplicado"
 * ),
 * @OA\Response(
 *     response=500,
 *     description="Error de base de datos"
 * )
 * )
 */
    public function update(StoreEmpresaRequest $request, string $codempresa)
    {    
        try {            
            $empresa = Empresa::find($codempresa);
            if (!$empresa) {                
                return $this->error("No data", "Empresa no encontrado", 404);    
            }            
            if (isset($request['mensaje'])) {
                $empresa->mensaje = base64_encode($request['mensaje']);
            }                     
            $empresa = $this-> empresaFiles($request, $empresa);   
            $empresa -> nombre = $request['nombre'] ?? $empresa -> nombre;
            $empresa->direccion = $request['direccion'] ?? $empresa->direccion;
            $empresa->telefono = $request['telefono'] ?? $empresa->telefono;
            $empresa->pais = $request['pais'] ?? $empresa->pais;
            $empresa->provincia = $request['provincia'] ?? $empresa->provincia;
            $empresa->ciudad = $request['ciudad'] ?? $empresa->ciudad;
            $empresa->nombretienda = $request['nombretienda'] ?? $empresa->nombretienda;
            $empresa->emailsoporte = $request['emailsoporte'] ?? $empresa->emailsoporte;
            $empresa->emailorden = $request['emailorden'] ?? $empresa->emailorden;
            $empresa->usuarioadmin = $request['usuarioadmin'] ?? $empresa->usuarioadmin;
            $empresa->claveadmin = $request['claveadmin'] ?? $empresa->claveadmin;            
            $empresa->cargo1 = $request['cargo1'] ?? $empresa->cargo1;
            $empresa->nombre1 = $request['nombre1'] ?? $empresa->nombre1;
            $empresa->email1 = $request['email1'] ?? $empresa->email1;
            $empresa->cargo2 = $request['cargo2'] ?? $empresa->cargo2;
            $empresa->nombre2 = $request['nombre2'] ?? $empresa->nombre2;
            $empresa->email2 = $request['email2'] ?? $empresa->email2;
            $empresa->cargo3 = $request['cargo3'] ?? $empresa->cargo3;
            $empresa->nombre3 = $request['nombre3'] ?? $empresa->nombre3;
            $empresa->email3 = $request['email3'] ?? $empresa->email3;
            $empresa->cargo4 = $request['cargo4'] ?? $empresa->cargo4;
            $empresa->nombre4 = $request['nombre4'] ?? $empresa->nombre4;
            $empresa->email4 = $request['email4'] ?? $empresa->email4;
            $empresa->fax = $request['fax'] ?? $empresa->fax;
            $empresa -> save();         
            return $this->success(new EmpresaResource($empresa));                        
        } catch (\Exception $e) {            
            return $this->error('Error al obtener los tipos de novedad.', $e->getMessage(), 500);
        }    
    }


    /**
     * @OA\Delete(
     * path="/api/datos/empresa/{codempresa}",
     * tags={"Web - Datos Empresa"},
     * summary="Eliminar una empresa específica",
     * description="Devuelve un estado 204 si se eliminó",
     * 
     * @OA\Parameter(
     *      description="Necesita el codempresa",
     *      in="path",
     *      name="codempresa",
     *      required=true,
     * ),
     * 
     * @OA\Response(
     *         response=204,     
     *     description="Eliminado correctamente"
     * ),
     * @OA\Response(
     *     response=404,
     *     description="Dato no encontrado"
     * ),
     * @OA\Response(
     *     response=500,
     *     description="Database error"
     * ),
     * )    
     */
    public function destroy(string $codempresa)
    {
        try {            
            $empresa = Empresa::find($codempresa);
            if (!$empresa) {                
                return $this->error("No data", "Datos de empresa no encontrado", 404);    
            }
            $this->deleteEmpresaImages($empresa);
            $empresa->delete();
            return $this->success(null,"Deleted", 204);                        
        } catch (\Exception $e) {            
            return $this->error('Error al obtener los datos de empresa', $e->getMessage(), 500);
        }
    }    

    private function empresaFiles(StoreEmpresaRequest $request, Empresa $empresa):Empresa{
        if ($request->hasFile('foto1')) {
            $empresa->foto1 = $request->file('foto1')->storeAs('public/Empresa', 'foto1_' . $empresa->codempresa . '.' . $request->file('foto1')->getClientOriginalExtension());
        }
        if ($request->hasFile('foto2')) {
            $empresa->foto2 = $request->file('foto2')->storeAs('public/Empresa', 'foto2_' . $empresa->codempresa . '.' . $request->file('foto2')->getClientOriginalExtension());    
        }
        if ($request->hasFile('foto3')) {
            $empresa->foto3 = $request->file('foto3')->storeAs('public/Empresa', 'foto3_' . $empresa->codempresa . '.' . $request->file('foto3')->getClientOriginalExtension());
        }
        if ($request->hasFile('foto4')) {
            $empresa->foto4 = $request->file('foto4')->storeAs('public/Empresa', 'foto4_' . $empresa->codempresa . '.' . $request->file('foto4')->getClientOriginalExtension());
        }
        if ($request->hasFile('fotopiecob')) {
            $empresa->fotopiecob = $request->file('fotopiecob')->storeAs('public/Empresa', 'fotopiecob_' . $empresa->codempresa . '.' . $request->file('fotopiecob')->getClientOriginalExtension());
        }
        if ($request->hasFile('logo')) {               
            $empresa->logo = $request->file('logo')->storeAs('public/Empresa', 'logo_' . $empresa->codempresa . '.' . $request->file('logo')->getClientOriginalExtension());
        }
        if ($request->hasFile('logo_cabecera')) {                        
            $empresa->logo_cabecera = $request->file('logo_cabecera')->storeAs('public/Empresa', 'logo_cabecera_' . $empresa->codempresa . '.' . $request->file('logo_cabecera')->getClientOriginalExtension());
        }
        return $empresa;
    }

    private function deleteEmpresaImages(Empresa $empresa)
{
    // Validar y eliminar cada archivo
    $this->deleteFileIfExists($empresa->foto1);
    $this->deleteFileIfExists($empresa->foto2);
    $this->deleteFileIfExists($empresa->foto3);
    $this->deleteFileIfExists($empresa->foto4);
    $this->deleteFileIfExists($empresa->fotopiecob);
    $this->deleteFileIfExists($empresa->logo);
    $this->deleteFileIfExists($empresa->logo_cabecera);
}

private function deleteFileIfExists($filePath)
{
    if ($filePath && Storage::exists($filePath)) {
        Storage::delete($filePath);
    }
}
}
