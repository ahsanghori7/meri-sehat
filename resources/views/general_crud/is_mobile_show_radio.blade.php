<div class="col-md-6 px-0 mb-3">

    <label for="status" class="d-block">Is Mobile Show</label>
    @error('is_mobile_show')<div class="validation-error"> {{ $message }}</div> @enderror
    <div class="custom__radio mb-3">
        <div class="d-flex box p-0 justify-content-between form-check">
            <div class="w-100">
                <input class="form-check-input" name="is_mobile_show" type="radio"
                    {{ (isset($result) && $result->is_mobile_show == 1) ? 'checked' : '' }} value="1" id="yes">
                <label class="form-check-label" for="yes">
                    Yes
                </label>
            </div>
            <div class="w-100">
                <input class="form-check-input" type="radio" name="is_mobile_show" value="0"
                       {{ (isset($result) && $result->is_mobile_show == 0) ? 'checked' : '' }} id="no">
                <label class="form-check-label" for="no">
                    No
                </label>
            </div>

        </div>
    </div>
</div>
