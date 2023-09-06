<div class="d-flex align-items-center justify-content-between mb-3">
    <h3 class="mb-0">Vital History</h3>
</div>
@if (isset($result->healthScans))
    @foreach($result->healthScans as $healthScans)
    <div class="row">
        <div class="col-md-12 mb-3">
            <span>Test performed on</span>
            <span>{{ date('d/M/Y', strtotime($healthScans->created_at)) }}</span>
        </div>
        <div class="col-md-12">
            <div class="row m-0">
                <div class="col-md-2">
                    <div class="border-2 rounded-lg p-2 d-flex flex-column justify-content-between height-110">
                        <div class="fs-18">Heart Rate</div>
                        <div class="fs-25">{{ $healthScans->heart_rate ?? '-' }} BPM</div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="border-2 rounded-lg p-2 d-flex flex-column justify-content-between height-110">
                        <div class="fs-18">Oxygen Saturation</div>
                        <div class="fs-25">{{ $healthScans->spo2 ?? '-' }} O2</div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="border-2 rounded-lg p-2 d-flex flex-column justify-content-between height-110">
                        <div class="fs-18">Respiration Rate</div>
                        <div class="fs-25">{{ $healthScans->respiratory_rate ?? '-' }}</div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="border-2 rounded-lg p-2 d-flex flex-column justify-content-between height-110">
                        <div class="fs-18">Blood Pressure</div>
                        <div class="fs-25">{{ $healthScans->blood_pressure ?? '-' }}</div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="border-2 rounded-lg p-2 d-flex flex-column justify-content-between height-110">
                        <div class="fs-18">Sympathetic <br class="d-none d-lg-block">Stress Level</div>
                        <div class="fs-25">{{ ($healthScans->stress_level != '' && $healthScans->stress_level <= 2) ? 'Low' : 'High' }}</div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="border-2 rounded-lg p-2 d-flex flex-column justify-content-between height-110">
                        <div class="fs-18">Heart Rate Variability</div>
                        <div class="fs-25">{{ $healthScans->sdnn ?? '-'}}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr class="" />
    @endforeach
@endif
