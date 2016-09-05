<!-- MENSAJE DE ERROR ++++++++++++++++++++++++++++++++++++++++++++ -->
@if ($errors->has())
    <div class="alert alert-danger alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- MENSAJE DE EXITO +++++++++++++++++++++++++++++++++++++++++++ -->
@if(Session::has('msg_success'))
    <div class="alert alert-success alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <strong>{{ Session::get('msg_success') }}</strong>
    </div>
@endif