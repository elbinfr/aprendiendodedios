<!-- Topic Header -->
<div class="topic">
    <div class="container">
        <div class="row">
            <div class="col-sm-4">
                <h3>{{ Session::get('menu-item') }}</h3>
            </div>
            <div class="col-sm-8">
                <ol class="breadcrumb pull-right hidden-xs">
                    <li> {{ Session::get('menu-item') }} </li>
                    @if(Session::has('menu-subitem'))
                        <li class="active">{{ Session::get('menu-subitem') }}</li>
                    @endif
                </ol>
            </div>
        </div>
    </div>
</div>
