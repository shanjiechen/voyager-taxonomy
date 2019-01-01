@if(isset($dataTypeContent->{$row->field}) && !is_null(old($row->field, $dataTypeContent->{$row->field})))
    <?php $selected_value = old($row->field, $dataTypeContent->{$row->field}); ?>
@else
    <?php $selected_value = old($row->field); ?>
@endif
<?php
    $default = (isset($options->default) && !isset($dataTypeContent->{$row->field})) ? $options->default : null;
    $selected_value = $selected_value ? $selected_value : $default;
?>
    <select class="form-control select2" name="{{ $row->field }}">
        @if(isset($options->options))
            @foreach($options->options as $key => $option)
                <option value="{{ $key }}" @if($default == $key && $selected_value === NULL){{ 'selected="selected"' }}@endif @if($selected_value == $key){{ 'selected="selected"' }}@endif>{{ $option }}</option>
            @endforeach
        @endif
        @foreach($termsTree as $term)
            <option value="{{ $term['id'] }}" @if($selected_value == $term['id']) {{ 'selected="selected"' }}@endif >{{ $term['name'] }}</option>
        @endforeach
    </select>
