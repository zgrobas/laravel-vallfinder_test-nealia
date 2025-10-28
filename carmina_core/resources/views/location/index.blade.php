@extends('layouts.app')

@section('content')
    <div class="container pt-5">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <b>Datos de centros</b>
                        <div class="d-flex align-items-center">
                            <a href="{{ route('createLocation') }}" class="btn btn-outline-secondary">Nuevo Registro</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="locations" class="table table-striped" style="width:100%">
                            <thead>
                                <tr>
                                    <th>id</th>
                                    <th>Nombre</th>
                                    <th>Dirección</th>
                                    <th>Latitud</th>
                                    <th>Longitud</th>
                                    <th>Radio (km)</th>
                                    <th>Tiempo (Seg)</th>
                                    <th>URL</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmación Bootstrap -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    ¿Estás seguro de que deseas eliminar el centro "<span id="centroName">000</span>"?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="btnConfirmDelete">Eliminar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Toast para mostrar mensajes en pantalla -->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div id="liveToast" class="toast align-items-center border-0" role="alert" aria-live="assertive"
            aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body" id="toastBody">
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <link href="{{ asset('build/assets/datatables.min.css') }}" rel="stylesheet">
    <style>
        .vall-tooltip {
            --bs-tooltip-bg: #0a457e;
            --bs-tooltip-color: #fff;
            }
    </style>
@endpush
@push('js')
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> --}}
    <script src="{{ asset('build/assets/datatables.min.js') }}"></script>
    <script>

        let table;
        let locationToDelete = null;
        let errors = "{{ Session::get('error') }}";
        let success = "{{ Session::get('success') }}";
        const modal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));


        $(document).ready(function() {
            $('#loading-page').hide();
            if(errors !== ""){
                $('#toastBody').html(errors);
                $('#liveToast').addClass('text-white bg-danger');
                $('#liveToast').toast('show');
            }
            if(success !== ""){
                $('#toastBody').html(success);
                $('#liveToast').addClass('text-white bg-success');
                $('#liveToast').toast('show');
            }
            $.extend(true, $.fn.dataTable.defaults, {
                language: {
                    url: "{{ asset('json/DataTable-ES.json') }}"
                },
                pageLength: 10,
                lengthMenu: [10, 25, 50, 75, 100],
                fixedHeader: true,
                responsive: {
                    details: {
                        renderer: function(api, rowIdx, columns) {
                            var data = $.map(columns, function(col, i) {
                                return col.hidden ?
                                    '<tr class="responsive-tr" data-dt-row="' + col.rowIndex +
                                    '" data-dt-column="' + col.columnIndex + '">' +
                                    '<td><b>' + col.title + ':' + '</b></td> ' +
                                    '<td>' + col.data + '</td>' +
                                    '</tr>' :
                                    '';
                            }).join('');
                            return data ?
                                $('<table/>').append(data) :
                                false;
                        }
                    }
                }
            });
            $.fn.dataTable.ext.errMode = function(settings, helpPage, message) {
                console.log('errMode', helpPage, message);

                if (settings.jqXHR.status == 403) {
                    showAlert('error', 'Acceso denegado.');
                    window.location.href = "{{ route('login') }}";
                } else {
                    showAlert('error',
                        'No se han cargado los datos correctamente, por favor contacta con el administrador.'
                    );
                }
            };
            table = new DataTable('#locations', configDes);

            $('#locations').on('draw.dt', function () {
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                tooltipTriggerList.forEach(function (tooltipTriggerEl) {
                    new bootstrap.Tooltip(tooltipTriggerEl);
                });
            });
        });

        const configDes = {
            searching: true,
            search: {
                caseInsensitive: true,
                smart: false
            },
            responsive: true,
            processing: true,
            columns: [{
                    data: "id",
                    className: "min-mobile-p",
                    visible: false
                },
                {
                    data: "name",
                    className: "min-mobile-p",
                    visible: true
                },
                {
                    data: "address",
                    orderable: false,
                    visible: true,
                    render: function(data, type, row) {
                        if (!data) return '';
                        const maxLength = 70;
                        const shortText = data.length > maxLength ? data.substring(0, maxLength) + '...' : data;
                        return `<span data-bs-toggle="tooltip" data-bs-custom-class="vall-tooltip" data-bs-placement="top" title="${data.replace(/"/g, '&quot;')}">${shortText}</span>`;
                    }
                },
                {
                    data: "latitude",
                    visible: true,
                    orderable: false,
                },
                {
                    data: "longitude",
                    visible: true,
                    orderable: false,
                },
                {
                    data: "radius",
                    visible: true,
                    className: "min-mobile-p",
                    orderable: false,
                },
                {
                    data: "time",
                    visible: true,
                    className: "min-mobile-p",
                    orderable: false,
                },
                {
                    data: "url",
                    visible: true,
                    orderable: false,
                },
                {
                    data: "actions",
                    className: "min-mobile-p",
                    orderable: false,
                },
            ],
            ajax: {
                "url": "{{ route('getLocations') }}",
                "type": "GET",
                "dataSrc": function(info) {
                    return info;
                }
            },
        };

        function showAlert(type, msg) {
            $('#liveToast').removeClass('text-white text-black bg-danger bg-success');
            $('#toastBody').html(`<p>${msg}</p>`);
                switch (type) {
                    case 'error':
                        $('#liveToast').addClass('text-white bg-danger');
                        break;
                    case 'success':
                        $('#liveToast').addClass('text-white bg-success');
                        break;
                    default:
                        $('#liveToast').addClass('text-black bg-warning');
                        break;
                }
                $('#liveToast').toast('show');
            }

        function deleteLocation(loc, name) {
            locationToDelete = loc;
            $('#centroName').text(name);
            modal.show();
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('btnConfirmDelete').addEventListener('click', function() {
                if (locationToDelete !== null) {
                    confirmDeleteLocation(locationToDelete);
                    // Cierra el modal
                    modal.hide();
                    locationToDelete = null;
                    $('#centroName').text('');
                }
            });
        });

        function confirmDeleteLocation(loc){
            $.ajax({
                url: "/location-settings/delete/" + loc,
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    showAlert('success', 'Centro eliminado correctamente.');
                    table.ajax.reload(null, false); // Recarga la tabla sin reiniciar la paginación
                },
                error: function(xhr) {
                    showAlert('error', 'No se pudo eliminar el centro.');
                }
            });
        }
    </script>
@endpush
