@php
    if($reference){
        $getReferenceWidget = \App\Models\ReferenceWidget::find($reference->id);
        $selectedLanguage = $getReferenceWidget->page ? $getReferenceWidget->page->lang_id : $getReferenceWidget->article->lang_id;
    }else {
        $selectedLanguage = 1;
    }
@endphp

<div class="col-md-12 px-0 mb-3">
    <label for="reference_heading">Heading</label>
    <input type="text" name="reference_heading" id="reference_heading"
        value="{{ $reference ? $reference->heading : old('reference_heading') }}" class="form-control"
        @if($selectedLanguage != 1) placeholder="سرخی درج کریں" dir="rtl" @else placeholder="Enter Heading" @endif>
    @error('reference_heading')
        <div class="validation-error"> {{ $message }}</div>
    @enderror
</div>

<div class="col-md-12 px-0 mb-3">
    <label for="reference_description">Description</label>
    <textarea name="reference_description" id="reference_description" class="form-control summernote"
        placeholder="Description">{{ $reference ? $reference->description : old('reference_description') }}</textarea>
    @error('reference_description')
        <div class="validation-error"> {{ $message }}</div>
    @enderror
</div>

<div class="col-md-12 px-0 mb-3">
    <label for="reference_redirect_url">Redirection URL</label>
    <input type="text" name="reference_redirect_url" id="reference_redirect_url"
        value="{{ $reference ? $reference->redirect_url : old('reference_redirect_url') }}" class="form-control"
        placeholder="Enter Heading">
    @error('reference_redirect_url')
        <div class="validation-error"> {{ $message }}</div>
    @enderror
</div>

@php
    $getAds = App\Models\AdsWindow::where('status', true)->get();
@endphp

<div class="col-md-12 px-0 mb-3">
    <label for="reference_ad_window_id">Ad Window</label>
    <select class="form-control" name="reference_ad_window_id" id="reference_ad_window_id">
        <option value=""> Select Ads </option>
        @foreach ($getAds as $key => $value)
            <option value="{{ $value->id }}"
                @if ($reference && $reference->ad_window_id == $value->id) selected="selected" @endif>
                {{ $value->name . ' ( ' . $value->dimensions . ' )' }}</option>
        @endforeach
    </select>
    @error('type')
        <div class="validation-error"> {{ $message }}</div>
    @enderror
</div>
