@extends('master')

@section('topic')
    @include('topic')
@endsection

@section('maincontent')

  <div class="container">
    <div class="row">

      <div class="col-sm-12">
        <h3 class="headline first-child"><span>Perfiles de Usuario</span></h3>

        <div class="col-sm-10">
          {!! Formcustom::linkRouteImg('administracion.perfil.create', 'Nuevo Perfil', 'assets/img/nuevo.png', ['class' =>'btn btn-default']) !!}
          {!! Formcustom::linkImg('administracion/perfil/buscar', 'Buscar', 'assets/img/buscar.png', ['class' => 'btn btn-default']) !!}
          <div class="table-responsive">
            <table id="tabla" cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered table-condensed table-hover" >
              <thead>
            		<tr class="tabla-cabecera">
                        <th>Id</th>
            			<th>Nombre</th>
            			<th>Descripci&oacute;n</th>
                        <th colspan="2">&nbsp;</th>
            		</tr>
              </thead>
              <tbody>
                @if(isset($perfiles))
                  @foreach($perfiles as $perfil)
                    <tr data-id="{{ $perfil->id }}">
                      <td>{{ $perfil->id }}</td>
                      <td>{{ $perfil->nombre }}</td>
                      <td>{{ $perfil->descripcion }}</td>
                      <td><a href="{{ route('administracion.perfil.edit', $perfil) }}" title="Editar"><img src="{{ asset('assets/img/editar.png') }}" alt="" /></a></td>
                      <td><a href="#" class="btn-delete" title="Eliminar"><img src="{{ asset('assets/img/eliminar.png') }}" alt="" /></a></td>
                    </tr>
                  @endforeach
                @endif
              </tbody>
            </table>
            {!! $perfiles->render() !!}
          </div>
        </div>

      </div>
    </div>
  </div>
  {!! Form::open(['route' => ['administracion.perfil.destroy', ':DATO_ID'], 'method' => 'DELETE', 'id' => 'form-delete']) !!}
  {!! Form::close() !!}
@endsection

@section('script')
  <script>
    $(document).ready(function () {
      @if(Session::has('msg_success'))
        swal("", "{{ Session::get('msg_success') }}", "success");
      @endif
    });
  </script>
@endsection