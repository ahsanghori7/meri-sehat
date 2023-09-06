    <div class="col-md-6 px-0 mb-3">
        <label for="status" class="d-block">Status</label>
        @error('status')<div class="validation-error"> {{ $message }}</div> @enderror
        <div class="custom__radio mb-3">
            <div class="d-flex box p-0 justify-content-between form-check">
                <div class="w-100">
                    <input class="form-check-input" name="status" type="radio"
                           {{ (isset($result) && $result->status == 1) ? 'checked' : '' }} value="1" id="enable">
                    <label class="form-check-label" for="enable">
                        Enable
                    </label>
                </div>
                <div class="w-100">
                    <input class="form-check-input" type="radio" name="status" value="0"
                           {{ (isset($result) && $result->status == 0) ? 'checked' : '' }} id="disable">
                    <label class="form-check-label" for="disable">
                        Disable
                    </label>
                </div>

            </div>
        </div>
    </div>
