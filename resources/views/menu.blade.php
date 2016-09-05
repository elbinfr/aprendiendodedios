@if (is_login())
    {!! dibujar_foto() !!}
@else
    <a href="{!! route('ingresar') !!}" class="btn btn-sm btn-theme-primary navbar-btn navbar-right hidden-sm hidden-xs">Ingresar</a>
@endif

<ul class="nav navbar-nav navbar-right">
    <li @if(Session::get('menu-item') == 'Inicio') class="active" @endif ><a href="{{ url('/') }}" >Inicio</a></li>

    <li class="dropdown @if(Session::get('menu-item') == 'Biblia') active @endif">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Biblia <b class="caret"></b></a>
        <ul class="dropdown-menu">
            <li><a href="{{ url('biblia/busqueda-pasaje') }}" >Búsqueda de Pasaje</a></li>
            <li><a href="{{ url('biblia/busqueda-palabra') }}" >Búsqueda por Palabra</a></li>
            <li><a href="{{ url('biblia/plan-lectura') }}" >Planes de Lectura</a></li>
        </ul>
    </li>

    <li class="dropdown @if(Session::get('menu-item') == '&Aacute;rea Espiritual') active @endif">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Área Espiritual <b class="caret"></b></a>
        <ul class="dropdown-menu">
            <li><a href="{{ url('area-espiritual/todos-los-articulos') }}" >Art&iacute;culos Bíblicos</a></li>
        </ul>
    </li>

    <li @if(Session::get('menu-item') == 'Contacto') class="active" @endif ><a href="{{ route('contacto') }}" >Contacto</a></li>    

    @if(is_login())
        @if(es_superadmin())
            <li class="dropdown @if(Session::get('menu-item') == 'Administración') active @endif ">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Administraci&oacute;n<b class="caret"></b></a>
              <ul class="dropdown-menu">
                  <li><a href="{{ route('administracion.perfil.index') }}" >Perfiles de Usuario</a></li>
              </ul>
            </li>
        @endif        

        <li class="dropdown @if(Session::get('menu-item') == 'Mi Cuenta') active @endif">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Mi Cuenta<b class="caret"></b></a>
            <ul class="dropdown-menu">
                <li><a href="{{ url('cuenta/mi-perfil') }}" >Perfil</a></li>
                <li><a href="{{ route('salir') }}" >Cerrar Sesión</a></li>
            </ul>
        </li>
    @endif

</ul>
