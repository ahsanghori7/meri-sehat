<div class="col-md-6 px-0 mb-3">

    <label for="is_web_show" class="d-block">Is Web Show</label>
    @error('is_web_show')<div class="validation-error"> {{ $message }}</div> @enderror
    <div class="custom__radio mb-3">
        <div class="d-flex box p-0 justify-content-between form-check">
            <div class="w-100">
                <input class="form-check-input" name="is_web_show" type="radio"
                    {{ (isset($result) && $result->is_web_show == 1) ? 'checked' : '' }} value="1" id="web_yes">
                <label class="form-check-label" for="web_yes">
                    Yes
                </label>
            </div>
            <div class="w-100">
                <input class="form-check-input" type="radio" name="is_web_show" value="0"
                       {{ (isset($result) && $result->is_web_show == 0) ? 'checked' : '' }} id="web_no">
                <label class="form-check-label" for="web_no">
                    No
                </label>
            </div>

        </div>
    </div>
</div>
