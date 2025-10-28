<!-- name Field -->
<div class="form-group col-sm-12">
    {!! Form::label('name', 'Nombre:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<!-- address Field -->
<div class="form-group col-sm-12">
    {!! Form::label('address', 'Dirección:') !!}
    {{ $edit }}{{ Auth::user()->super_admin }}
    @if (Auth::user()->super_admin == 1)
    {!! Form::text('address',  null , ['class' => 'form-control']) !!}
    @else
    {!! Form::text('address',  null , ['class' => 'form-control', 'disabled'=> $edit ]) !!}
    @endif
</div>

<!-- latitude Field -->
<div class="form-group col-sm-6">
    {!! Form::label('latitude', 'Latitud:') !!}
    @if (Auth::user()->super_admin == 1)
    {!! Form::text('latitude', null, ['class' => 'form-control']) !!}
    @else
    {!! Form::text('latitude', null, ['class' => 'form-control', 'disabled'=> $edit]) !!}
    @endif
</div>

<!-- longitude Field -->
<div class="form-group col-sm-6">
    {!! Form::label('longitude', 'Longitud:') !!}
    @if (Auth::user()->super_admin == 1)
    {!! Form::text('longitude', null, ['class' => 'form-control']) !!}
    @else
    {!! Form::text('longitude', null, ['class' => 'form-control', 'disabled'=> $edit]) !!}
    @endif
</div>

<!-- radius Field -->
<div class="form-group col-sm-6">
    {!! Form::label('radius', 'Radio (Km):') !!}
    {!! Form::text('radius', null, ['class' => 'form-control']) !!}
</div>

<!-- time Field -->
<div class="form-group col-sm-6">
    {!! Form::label('time', 'Intervalo de Tiempo (Seg):') !!}
    {!! Form::text('time', null, ['class' => 'form-control']) !!}
</div>

