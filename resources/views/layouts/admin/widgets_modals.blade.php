@php $widgets = App\Models\Widget::all(); @endphp

@push('widget_modals')
    @foreach ($widgets as $widget)
        <div class="modal fade" id="widget-{{ $widget->id }}" tabindex="-1" role="dialog"
            aria-labelledby="widget-{{ $widget->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content"></div>
            </div>
        </div>
    @endforeach
@endpush


@push('widget_scripts')
    <script>
        @foreach ($widgets as $widget)
            function open_module_modal_{{ $widget->id }}(reference_id) {
                var selector = `#widget-{{ $widget->id }}`;
                let url = (
                    "{{ route('widget-' . $widget->key . '-index', ['-reference_id-']) }}"
                ).replace('-reference_id-', reference_id);
                var height = screen.height * 0.8;
                var html = '<iframe id="frame" height=' + height + ' src="' + url + '"> </iframe>'
                $(selector).children().children().html(html);
                $(selector).modal('show');
            }
        @endforeach
    </script>
@endpush
