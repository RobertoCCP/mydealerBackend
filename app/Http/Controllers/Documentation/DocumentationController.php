<?php

namespace App\Http\Controllers\Documentation;

use App\Http\Controllers\Controller;


/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="L5 OpenApi",
 *      description="L5 Swagger My Dealer Documentation",
 * )
 *
 * @OA\Tag( name="Cliente - Autenticación", description="Sesión para el cliente móvil"),
 * @OA\Tag( name="Cliente - Detalle de Productos", description="Muestra los detalles de productos"),
 * @OA\Tag( name="Cliente - Registro de Cliente", description="Permite la gestión de clientes"),
 * @OA\Tag( name="Cliente - Formas de Pago", description="Muestras las formas de pago aceptadas en la app"),
 * @OA\Tag( name="Cliente - Dirección de envío", description="Muestras las direcciones de envio asociadas al cliente"),
 * @OA\Tag( name="Cliente - Pedidos Cliente", description="Devuelve una lista de pedidos filtrados por cliente, fecha y estado"), 
 * @OA\Tag( name="Cliente - Marcas Productos", description="Devuelve Las marcas de productos"), 
 * @OA\Tag( name="Cliente - Categorias Productos", description="Devuelve Las categorias de productos"), 
 * @OA\Tag( name="Cliente - Configuracion Json", description="Devuelve un json con la configuración de la app"),
 * @OA\Tag( name="Cliente - Todos los Productos", description="Busqueda sencilla de productos con paginación"),
 * @OA\Tag( name="Pedidos", description="Realizar un listado (GET) realizando un ordenamiento por fecha más reciente, y que reciba los parámetros de paginación correspondientes "),
 *
 * @OA\Tag( name="Vendedor - Autenticación", description="Sesión para el vendedor móvil"),
 * @OA\Tag( name="Vendedor - Dirección Envío GPS", description="Ubicación de clientes y vendedores GPS"),
 * @OA\Tag( name="Vendedor - Dashboard", description="Muestra los datos principales del home de la app"),
 * @OA\Tag( name="Vendedor - Pedidos", description="Muestra la información de pedidos"),
 * @OA\Tag( name="Vendedor - Vista Cliente", description="Reporte De Clientes"),
 * @OA\Tag( name="Vendedor - Cliente Rutas", description="Rutas de clientes que tiene un vendedor"),
 *
 * @OA\Tag( name="Web - Autenticación", description="Sesión para la sesión web"),
 * @OA\Tag( name="Web - Tipo de Novedad", description="Operaciones CRUD para El Tipo de Novedad"),
 * @OA\Tag( name="Web - Tipo Cliente", description="Reporte de Tipo de Cliente"),
 * @OA\Tag( name="Web - Menu Cabecera", description="Operaciones CRUD para el menu de cabecera"),
 * @OA\Tag( name="Web - Usuarios de Administración", description="Operaciones CRUD para los usuarios de administración"),
 * @OA\Tag( name="Web - Datos Empresa", description="Permite la gestión de datos de la empresa"),
 * @OA\Tag( name="Web - Parámetro", description="Permite el CRUD de los parámetros de la app"),
 * @OA\Tag( name="Web - Tipo Producto", description="Permite el CRUD de los tipos de productos"),
 * @OA\Tag( name="Web - Opciones por perfil", description="Permite Gestionar las opciones de los perfiles"),
 * @OA\Tag( name="Web - Perfil Usuario", description="Permite gestionar los perfiles de usuarios"),
 * @OA\Tag( name="Web - Horario GPS", description="Muestra los días que maneja el horario"),
 * @OA\Tag( name="Web - Reporte Gestión", description="Muestra la actividad de clientes, vendedores y supervisores"),
 * @OA\Tag( name="Web - Reporte Pedido", description="Permite obtener los reportes de pedidos"),
 * @OA\Tag( name="Web - Opciones de Menú", description="Permite el CRUD de opciones menu"),
 * @OA\Tag( name="Web - Vendedor Socios", description="Permite obtener los datos del vendedor"),
 
 */

class DocumentationController extends Controller
{
}
