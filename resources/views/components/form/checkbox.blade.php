<!-- Form Input -->
<div class="form-group checkbox">
{{--    {{ Form::label($name, $label, ['class' => 'control-label']) }}--}}
    <label for="{{ $name }}" class="control-label">
        {{ Form::checkbox($name, $value, $checked, array_merge(['id' => $name], count($attributes)>0?$attributes:[]) ) }}
        {{ $label }}
    </label>

{{--    {{ Form::text($name, $value, array_merge(['class' => 'form-control'], count($attributes)>0?$attributes:[])) }}--}}
</div>