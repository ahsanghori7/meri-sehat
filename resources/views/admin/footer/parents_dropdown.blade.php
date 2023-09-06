<option value="">Please select</option>
@foreach ($parents as $parent)
    <option value="{{ $parent->id }}" {{ $result->parent_id == $parent->id ? 'selected' : ''}}>{{ $parent->name ?? ($parent->title ?? '') }}</option>
@endforeach
