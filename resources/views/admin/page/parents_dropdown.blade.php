@foreach ($parents as $parent)
    <option value="{{ $parent->id }}">{{ $parent->name ?? ($parent->title ?? '') }}</option>
@endforeach
