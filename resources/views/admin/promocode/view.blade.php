@extends('layouts.admin.app')
@section('page_header')
    All {{ Str::plural($module_name) }}
@endsection
@section('content')
    <div class="container my-3">
        <div class="card p-3">
            <div class="row">
                <div class="col-md-12">
                    <div class="box-header with-border">
                        <div class="d-flex justify-content-start">
                            <a href="{{ route($folder_name . '-add') }}"
                                class="btn-success btn-sm btn mb-2 cursor-pointer">
                                <i class=" icon-add"></i>
                                Add New
                            </a>
                        </div>
                    </div>
                    <form method="post" action="{{ route($folder_name . '-view') }} ">
                        @csrf
                        <table id="" class="table table-bordered table-hover" style="width:100%;">
                            <thead>
                                <tr>
                                    <th width=" 10px">#</th>
                                    <th class="">Code</th>
                                    <th class="">No of consultation</th>
                                    <th class="">No of sehat scan</th>
                                    <th class="">Status</th>
                                    <th class="">Expire</th>
                                    <th width="150px">Action</th>
                                </tr>
                            </thead>
                            <tbody class="">
                                @foreach ($result as $key => $result)
                                    <tr>
                                        <td>
                                            <input type="hidden" name="sequence[]" value="{{ $result->id }}">
                                            {{ $key + 1 }}
                                        </td>
                                        <td> {{ $result->code }}</td>
                                        <td> {{ $result->no_of_consultation }}</td>
                                        <td> {{ $result->no_of_sehat_scan }}</td>
                                        <td>
                                            @if ($result->status)
                                                <span class="badge p-2 badge-success">Enable</span>
                                            @else
                                                <span class="badge p-2 badge-danger">Disable</span>
                                            @endif
                                        </td>
                                        <td> {{ $result->expire_date }}</td>

                                        <td>
                                            <a class="btn-primary btn-sm btn cursor-pointer"
                                                href="{{ route($folder_name . '-edit', ['id' => $result->e_id]) }}"> <i
                                                    class="icon-edit"></i> </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        {{-- <button type="submit" class="btn btn-sm mb-2 btn-success">
                            Update sequence
                        </button> --}}
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $(".stastus_toggle").click(function() {
                // $(this).toggleClass('bg-green-400')
                // $(this).toggleClass('bg-red-400')
                // if ($(this).attr('checked')) {
                //     alert("checked");
                //     $(this).removeAttr('checked')
                // } else {
                //     alert("NOT checked");
                //     $(this).attr('checked', 'checked');
                // }
                    console.log('test');
                // var id = $(this).data('id')
                // var val = $(this).data('val')
                // Swal.fire({
                //     title: 'Toggle Status?',
                //     icon: 'warning',
                //     showCancelButton: true,
                //     confirmButtonColor: '#3085d6',
                //     cancelButtonColor: '#d33',
                //     confirmButtonText: 'Yes',
                //     cancelButtonText: 'No'
                // }).then((result) => {
                //     if (result.isConfirmed) {
                //         val = !val ? 1 : 0;
                //         toggleStatus(id, val);
                //     } else {

                //         $(this).toggleClass('bg-green-400')
                //         $(this).toggleClass('bg-red-400')
                //     }
                // })

            })

            function toggleStatus(id, val) {
                console.log(id);
                $.ajax({
                    url: "{{ route($folder_name . '-toggle_status') }}",
                    type: "post",
                    data: {
                        id,
                        val,
                        '_token': '{{ csrf_token() }}'
                    },
                });
            }
        });
    </script>
@endpush
