<!-- File Form Input -->
<div class="form-group">
    {!! Form::label($name,$label, ['class' => 'control-label']) !!}
    {!! Form::file($name,['class'=>'form-control']) !!}
</div>