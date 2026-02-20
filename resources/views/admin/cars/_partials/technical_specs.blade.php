<div class="card mb-4">
    <div class="card-header bg-light">
        <h6 class="mb-0"><i class="fas fa-cogs me-2"></i>المواصفات الفنية</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label for="transmission_type_id">ناقل الحركة <span class="text-danger">*</span></label>
                    <select name="transmission_type_id" id="transmission_type_id"
                        class="form-control select2 @error('transmission_type_id') is-invalid @enderror" required>
                        <option value=""></option>
                        @foreach ($transmissions as $trans)
                            <option value="{{ $trans->id }}"
                                {{ old('transmission_type_id', $car->transmission_type_id ?? '') == $trans->id ? 'selected' : '' }}>
                                {{ $trans->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('transmission_type_id')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label for="fuel_type_id">نوع الوقود <span class="text-danger">*</span></label>
                    <select name="fuel_type_id" id="fuel_type_id"
                        class="form-control select2 @error('fuel_type_id') is-invalid @enderror" required>
                        <option value=""></option>
                        @foreach ($fuelTypes as $fuel)
                            <option value="{{ $fuel->id }}"
                                {{ old('fuel_type_id', $car->fuel_type_id ?? '') == $fuel->id ? 'selected' : '' }}>
                                {{ $fuel->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('fuel_type_id')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label for="drive_type_id">نوع الدفع</label>
                    <select name="drive_type_id" id="drive_type_id"
                        class="form-control select2 @error('drive_type_id') is-invalid @enderror">
                        <option value=""></option>
                        @foreach ($driveTypes as $drive)
                            <option value="{{ $drive->id }}"
                                {{ old('drive_type_id', $car->drive_type_id ?? '') == $drive->id ? 'selected' : '' }}>
                                {{ $drive->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('drive_type_id')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3">
                <div class="form-group mb-3">
                    <label for="engine_capacity">سعة المحرك (CC)</label>
                    <input type="number" name="engine_capacity" id="engine_capacity"
                        class="form-control @error('engine_capacity') is-invalid @enderror"
                        value="{{ old('engine_capacity', $car->engine_capacity ?? '') }}" min="0">
                    @error('engine_capacity')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group mb-3">
                    <label for="horse_power">القوة الحصانية (HP)</label>
                    <input type="number" name="horse_power" id="horse_power"
                        class="form-control @error('horse_power') is-invalid @enderror"
                        value="{{ old('horse_power', $car->horse_power ?? '') }}" min="0">
                    @error('horse_power')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group mb-3">
                    <label for="cylinders">عدد الأسطوانات</label>
                    <input type="number" name="cylinders" id="cylinders"
                        class="form-control @error('cylinders') is-invalid @enderror"
                        value="{{ old('cylinders', $car->cylinders ?? 4) }}" min="0">
                    @error('cylinders')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group mb-3">
                    <label for="doors">عدد الأبواب</label>
                    <input type="number" name="doors" id="doors"
                        class="form-control @error('doors') is-invalid @enderror"
                        value="{{ old('doors', $car->doors ?? 4) }}" min="2" max="10">
                    @error('doors')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group mb-3">
                    <label for="seats">عدد المقاعد</label>
                    <input type="number" name="seats" id="seats"
                        class="form-control @error('seats') is-invalid @enderror"
                        value="{{ old('seats', $car->seats ?? 5) }}" min="2" max="20">
                    @error('seats')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        {{-- <div class="row">
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label for="manufacturing_year">سنة التصنيع</label>
                    <input type="number" name="manufacturing_year" id="manufacturing_year"
                        class="form-control @error('manufacturing_year') is-invalid @enderror"
                        value="{{ old('manufacturing_year', $car->manufacturing_year ?? '') }}" min="1900"
                        max="{{ date('Y') + 1 }}">
                    @error('manufacturing_year')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div> --}}
    </div>
</div>
