<div class="card mb-4">
    <div class="card-header bg-light">
        <h6 class="mb-0"><i class="fas fa-fingerprint me-2"></i>المعلومات الفريدة</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label for="chassis_number">رقم الهيكل</label>
                    <input type="text" name="chassis_number" id="chassis_number"
                        class="form-control @error('chassis_number') is-invalid @enderror"
                        value="{{ old('chassis_number', $car->chassis_number ?? '') }}">
                    @error('chassis_number')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label for="plate_number">رقم اللوحة</label>
                    <input type="text" name="plate_number" id="plate_number"
                        class="form-control @error('plate_number') is-invalid @enderror"
                        value="{{ old('plate_number', $car->plate_number ?? '') }}">
                    @error('plate_number')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>
</div>
