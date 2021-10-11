<div class="row mb-3">
<div class="col-8 col-md-3 order-1 order-md-0">
    <input type="hidden" name="choice_no[]" value="{{$inputs['i']}}">
    <input type="text" class="form-control" name="choice[]" value="{{$inputs['name']}}" placeholder="{{ translate('Choice Title') }}" readonly>
</div>
<div class="col-12 col-md-7 col-xl-8 order-3 order-md-0 mt-2 mt-md-0">
    @if($inputs['name'] == 'Unit')
    <input type="text" class="form-control mb-3 tagsInput" name="{{'choice_options_'.$inputs['i']}}[]" onchange="update_sku()" placeholder="{{ translate('Add Unit') }}" data-role="tagsinput" required>
    <script>$('.tagsInput').tagsinput('items');</script>
    @else
    <select name="{{'choice_options_'.$inputs['i']}}[]" onchange="update_sku()" class="form-control selectpicker" multiple>
    @foreach($attr as $attribute)
    <option value="{{ $attribute }}">{{ $attribute }}</option>
    @endforeach
    </select>
    @endif
</div>
<div class="col-4 col-xl-1 col-md-2 order-2 order-md-0 text-right">
    <button type="button" onclick="delete_row(this)" class="btn btn-link btn-icon text-danger"><i class="fa fa-trash-o"></i></button>
</div>
</div>