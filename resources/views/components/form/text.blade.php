<!-- Form Input -->
<div class="form-group">
    {{ Form::label($name, $label, ['class' => 'control-label']) }}
    {{ Form::text($name, $value, array_merge(['class' => 'form-control'], count($attributes)>0?$attributes:[])) }}
</div>