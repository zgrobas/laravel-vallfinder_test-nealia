@extends('layouts.app')

@section('content')
    <div class="container pt-5">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <b>Registro de centros</b>
                        <div class="d-flex align-items-center">
                            <a href="{{ route('indexLocation') }}" class="btn btn-secondary">Volver</a>
                        </div>
                    </div>
                    {!! Form::model($location, ['route' => ['location.update', $location->id]]) !!}
                    <div class="card-body">
                        <div class="row">
                            @include('location.fields')
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12 d-flex justify-content-end gap-3">
                                {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
                                <a href="{{ route('indexLocation') }}" class="btn btn-outline-secondary">Cancelar</a>
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
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

@push('js')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        let errors = "{{ Session::get('error') }}";

        $(document).ready(function() {
            $('#loading-page').hide();

            if (errors !== "") {
                $('#toastBody').html(errors);
                $('#liveToast').addClass('text-white bg-danger');
                $('#liveToast').toast('show');
            }

            $('#address').on('blur', function() {
                let address = $(this).val();
                if (address.trim() === '') return;
                let apiKey = 'AIzaSyBsZphygAUL0KNMKoBxVQM3w1s60KVHC78';
                let url =
                    `https://maps.googleapis.com/maps/api/geocode/json?address=${encodeURIComponent(address)}&key=${apiKey}`;

                $.get(url, function(data) {
                    if (data.status === 'OK') {
                        let location = data.results[0].geometry.location;
                        $('#latitude').val(location.lat);
                        $('#longitude').val(location.lng);
                        showAlert('success',
                            'Datos de latitud y longitud optenidos desde Google Maps');
                    } else {
                        showAlert('error', 'No se pudo encontrar la dirección en Google Maps');
                    }
                });

            });
        });

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
    </script>
@endpush
