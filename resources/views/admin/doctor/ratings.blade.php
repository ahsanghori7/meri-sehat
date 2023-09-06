@php
    $reviews = App\Models\Review::getReviews($result->doctor_id)
@endphp
{{--<div id="Ratings" class="tabcontent">--}}
    <h3 class="mb-3">Ratings and Reviews Approval</h3>
    @if($reviews && count($reviews) > 0)
        @if($reviews['reviews'] && count($reviews['reviews']) > 0)
            @foreach ($reviews as $key => $review)
                @if($key != 'reviews')
                    <li>{{\Str::headline($key)}} : {{$review}}</li>
                @endif
            @endforeach
            @foreach ($reviews['reviews'] as $review_text_key => $review_text)
                @php
                    $user_img = '<span class="icon icon-user s-48"></span>';
                    if (isset($review_text->user) && $review_text->user->image != '')
                        $user_img = '<img src="'.env('ASSETS_STORAGE'). $review_text->user->image.'" class="" />';
                @endphp
                <div class="card py-2 px-3 mb-3 rounded-xl">
                    <div class="row">
                        <div class="col-md-1">
                            {!! $user_img !!}
                        </div>
                        <div class="col-md-11">
                        <span>{{$review_text->description}}</span>
                            <div class="d-md-flex align-items-center justify-content-between mt-2">
                                <div class="d-md-flex">
                                    <span class="mr-3 mb-0">Verified {{$review_text->user ? $review_text->user->name : ''}} *************</span>
                                    <span class="mb-0">{{$review_text->created_at}}</span>
                                </div>
                                <div class="box-header with-border">
                                    <div class="d-flex justify-content-start">
                                        @if ($review_text->status == 'pending')
                                            <a href="javascript:void(0);" onclick="ratings('approved', '{{ $review_text->id }}')" class="btn-success btn-sm mr-2 btn mb-0 cursor-pointer">
                                                Approve for Publish
                                            </a>
                                            <a href="javascript:void(0);" onclick="ratings('disapproved', '{{ $review_text->id }}')" class="btn-danger btn-sm btn mb-0 cursor-pointer">
                                                Disapproved
                                            </a>
                                        @elseif ($review_text->status == 'approved')
                                            <h5 class="text-success mb-0">
                                                Published
                                            </h5>
                                        @elseif ($review_text->status == 'disapproved')
                                            <h5 class="text-success mb-0">
                                                Rejected
                                            </h5>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    @endif
{{--</div>--}}
<script>
    var doctor_link = '{{$doctor_link}}';
    if (doctor_link != '') {
        $(document).find('.warning').addClass('d-none');
        $(document).find('.warning').removeClass('d-flex');
        $(document).find('.warning').removeClass('justify-content-center');
        $('.doctor_anchor').removeClass('d-none').attr('href', doctor_link);
    } else {
        $(document).find('.warning').removeClass('d-none');
        $(document).find('.warning').addClass('d-flex');
        $(document).find('.warning').addClass('justify-content-center');
        $('.doctor_anchor').addClass('d-none').attr('href', 'javascript:void(0)');
    }
</script>
