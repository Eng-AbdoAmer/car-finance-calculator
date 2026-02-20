{{-- admin.cars._partials.basic_specs --}}
<div class="row">
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="model_year">سنة الموديل <span class="text-danger">*</span></label>
            <select name="model_year" id="model_year" class="form-control @error('model_year') is-invalid @enderror"
                required>
                <option value="">اختر السنة</option>
                @foreach ($years as $year)
                    <option value="{{ $year }}"
                        {{ old('model_year', $car->model_year ?? '') == $year ? 'selected' : '' }}>
                        {{ $year }}
                    </option>
                @endforeach
            </select>
            @error('model_year')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    {{-- <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="manufacturing_year">سنة الصنع</label>
            <input type="number" name="manufacturing_year" id="manufacturing_year"
                class="form-control @error('manufacturing_year') is-invalid @enderror"
                value="{{ old('manufacturing_year', $car->manufacturing_year ?? '') }}" min="1900">
            @error('manufacturing_year')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div> --}}
    <div class="col-md-4">
        <div class="form-group mb-3">
            <label for="color">اللون <span class="text-danger">*</span></label>
            <input type="text" name="color" id="color"
                class="form-control @error('color') is-invalid @enderror" value="{{ old('color', $car->color ?? '') }}"
                required>
            @error('color')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group mb-3">
            <label for="condition">حالة السيارة <span class="text-danger">*</span></label>
            <select name="condition" id="condition" class="form-control @error('condition') is-invalid @enderror"
                required>
                <option value=""></option>
                @foreach ($conditions as $key => $label)
                    <option value="{{ $key }}"
                        {{ old('condition', $car->condition ?? '') == $key ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            @error('condition')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group mb-3">
            <label for="mileage">الممشي (كم) <span class="text-danger">*</span></label>
            <input type="number" name="mileage" id="mileage"
                class="form-control @error('mileage') is-invalid @enderror"
                value="{{ old('mileage', $car->mileage ?? 0) }}" min="0" required>
            @error('mileage')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group mb-3">
            <label for="engine_capacity">سعة المحرك (CC)</label>
            <input type="number" name="engine_capacity" id="engine_capacity"
                class="form-control @error('engine_capacity') is-invalid @enderror"
                value="{{ old('engine_capacity', $car->engine_capacity ?? '') }}" min="0">
            @error('engine_capacity')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group mb-3">
            <label for="horse_power">القوة الحصانية</label>
            <input type="number" name="horse_power" id="horse_power"
                class="form-control @error('horse_power') is-invalid @enderror"
                value="{{ old('horse_power', $car->horse_power ?? '') }}" min="0">
            @error('horse_power')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group mb-3">
            <label for="cylinders">عدد الاسطوانات</label>
            <input type="number" name="cylinders" id="cylinders"
                class="form-control @error('cylinders') is-invalid @enderror"
                value="{{ old('cylinders', $car->cylinders ?? '') }}" min="0">
            @error('cylinders')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>
