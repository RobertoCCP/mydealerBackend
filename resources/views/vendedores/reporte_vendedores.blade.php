<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Vendedores</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #ffffff;
            color: #333333;
        }
        h1 {
            color: #007bff;
        }
        .table {
            background-color: #ffffff;
            color: #333333;
        }
        .table th {
            background-color: #f8f9fa;
            color: #333333;
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f2f2f2;
        }
        .table-striped tbody tr:nth-of-type(even) {
            background-color: #ffffff;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center mb-4">Reporte de Vendedores</h1>
    <div class="table-responsive">
        <table id="vendedores-table" class="table table-striped table-bordered">
            <thead>
            <tr>
                <th>Código de Vendedor</th>
                <th>Nombre</th>
                <th>MAC o ID Dispositivo</th>
                <th>Latitud GPS</th>
                <th>Longitud GPS</th>
                <th>Fecha-Hora de la Toma GPS</th>
                <th>% Batería</th>
                <th>Versión myDealer</th>
                <th>Ubicación</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

<script>
    const apiUrl = '/api/vendedor/coordenadas/reporte';

    $(document).ready(function () {
        const table = $('#vendedores-table').DataTable({
            processing: true,
            serverSide: false,
            paging: true,
            searching: true,
            ordering: true,
            ajax: {
                url: apiUrl,
                dataSrc: function (json) {
                    if (json.success) {
                        return json.data;
                    } else {
                        console.error('Error al cargar los datos:', json.message);
                        return [];
                    }
                },
                error: function (xhr, error, thrown) {
                    console.error('Error al hacer la solicitud:', error);
                },
            },
            columns: [
                { data: 'codvendedor' },
                { data: 'nombre_vendedor' },
                { data: 'mac' },
                { data: 'latitud' },
                { data: 'longitud' },
                { data: 'fecha' },
                { data: 'bateria', render: function (data) { return data + '%'; } },
                { data: 'version' },
                {
                    data: null,
                    render: function (data, type, row) {
                        return `
                                <button class="btn btn-primary btn-sm" onclick="redirectToMap(${row.latitud}, ${row.longitud},'${row.codvendedor}','${row.fecha}')">
                                    Ver en Mapa
                                </button>
                            `;
                    },
                    orderable: false,
                    searchable: false
                },
            ],
            language: {
                lengthMenu: "Mostrar _MENU_ registros por página",
                zeroRecords: "No se encontraron resultados",
                info: "Mostrando página _PAGE_ de _PAGES_",
                infoEmpty: "No hay registros disponibles",
                infoFiltered: "(filtrado de _MAX_ registros totales)",
                search: "Buscar:",
                paginate: {
                    first: "Primero",
                    last: "Último",
                    next: "Siguiente",
                    previous: "Anterior"
                },
            },
        });
    });
    function redirectToMap(lat, lng,codvendedor,fecha) {
        window.location.href = `/api/vendedores/mapa?lat=${lat}&lng=${lng}&codvendedor=${codvendedor}&fecha=${encodeURIComponent(fecha)}`;
    }
</script>
</body>
</html>
