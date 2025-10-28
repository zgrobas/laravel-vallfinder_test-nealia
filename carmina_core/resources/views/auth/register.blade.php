@extends('layouts.app')

@section('content')
<div class="container pt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><b>Registro de usuario</b></div>

                <div class="card-body">
                    <form method="POST" action="{{ route('save_usuario') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">Nombre</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                    name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">Correo Electrónico</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                    name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="rol" class="col-md-4 col-form-label text-md-end">Rol</label>
                            <div class="col-md-6">
                                <select name="rol" id="rol" class="form-select" required>
                                    <option disabled selected>Selecciona un rol</option>
                                    <option value="1">Usuario regular</option>
                                    <option value="2">Administrador</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="empresas" class="col-md-4 col-form-label text-md-end">Tipos de establecimientos</label>
                            <div class="col-md-6">
                                <div class="list-group">
                                    @foreach ($tiposClientes as $tipoCliente)
                                    <label class="list-group-item {{ ($tipoCliente->tipocliente == 'HORECA' || $tipoCliente->tipocliente == 'Industrial') ? 'oa-visible' : null  }}">
                                        <input class="form-check-input me-1" name="empresa[]" type="checkbox" value="{{ str_replace(' ','-',$tipoCliente->tipocliente) }}">
                                        {{ $tipoCliente->tipocliente }}
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4 d-flex gap-3">
                                <div id="guardar" class="btn btn-primary">Guardar</div>
                                <a href="{{ route('home') }}" class="btn btn-outline-secondary">Cancelar</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div id="liveToast" class="toast align-items-center border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex"  >
            <div class="toast-body" id="toastBody">

            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>
@endsection
@push('css')
<style>
    #loading-page {
        left: 0;
        top: 0;
        z-index: 1000;
        width: 100%;
        height: 100%;
        background-color: #dfebef73;
    }

    #loading-page>div {
        position: absolute;
        top: 50%;
        left: 50%;
        height: 5em;
        width: 5em;
    }
    .list-group {
        max-height: 325px;
        overflow-y: auto;
    }
</style>
@endpush
@push('js')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script>

        $(document).ready(function(){
            $('#loading-page').hide();
            let errors = "{{ Session::get('error') }}";
            let success = "{{ Session::get('success') }}";
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
            $('#rol').on('change', function(e){
                if($('#rol').val() == '1'){
                    $('.oa-visible>input[type="checkbox"]:checked').each(function(){
                        $(this).prop('checked', false);
                    });
                    $('.oa-visible').hide();
                }else{
                    $('.oa-visible').show();
                }
            });
        });
        $('#guardar').click(function(){
            let message = "";
            $('#toastBody').empty();
            if($('#name').val() == ""){
                message += '<span style="font-weight:bold">El nombre al usuario no ha sido asignado.</span><br>';
            }
            if($('#email').val() == ""){
                message += '<span style="font-weight:bold">El correo electrónico del usuario no ha sido asignado.</span><br>';
            }
            if($('#rol').val() == null){
                message += '<span style="font-weight:bold">El rol no ha sido seleccionado.</span><br>';
            }
            if($('input[type="checkbox"]:checked').length == 0){
                message += '<span style="font-weight:bold">No se han asignado empresas.</span>'
            }
            $('#toastBody').html(message);
            $('#liveToast').addClass('text-black bg-warning');
            if(message !== "")
                $('#liveToast').toast('show');
            else
                $('form').submit();
        });
    </script>
@endpush
