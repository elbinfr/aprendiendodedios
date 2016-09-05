<div class="col-sm-1"></div>
<div class="form-group">
    {!! Form::label('libro', 'Libro') !!}
    {!! Form::text('libro', null, [
        'class'         => 'text-autocomplete form-control',
        'data-provider' => 'typeahead'
        ]) !!}
    &nbsp;
</div>
<div class="form-group">
    {!! Form::label('capitulo', 'Cap&iacute;tulo') !!}
    {!! Form::text('capitulo', null, [
        'class'     => 'form-control',
        'maxlength' => '3',
        'size'      => '3'
        ]) !!}
    &nbsp;
</div>
<div class="form-group">
    {!! Form::label('verso_ini', 'Versos') !!}
    {!! Form::text('verso_ini', null, [
        'class'     => 'form-control',
        'maxlength' => '3',
        'size'      => '3'
        ]) !!}
</div>
<div class="form-group">
    {!! Form::label('verso_fin', ' - ') !!}
    {!! Form::text('verso_fin', null, [
        'class'     => 'form-control',
        'maxlength' => '3',
        'size'      => '3'
        ]) !!}
</div>