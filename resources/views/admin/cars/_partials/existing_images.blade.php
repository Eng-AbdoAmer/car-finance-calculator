@if ($car->images->count() > 0)
    <div class="card mb-4">
        <div class="card-header bg-light">
            <h6 class="mb-0"><i class="fas fa-images me-2"></i>الصور الحالية</h6>
        </div>
        <div class="card-body">
            <div class="row">
                @foreach ($car->images as $image)
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                        <div class="card h-100">
                            <img src="{{ asset('storage/' . $image->image_path) }}" class="card-img-top"
                                style="height: 180px; object-fit: cover;" alt="صورة السيارة">
                            <div class="card-body p-2 text-center">
                                @if (!$image->is_main)
                                    <form action="{{ route('admin.cars.set-main-image', [$car->id, $image->id]) }}"
                                        method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-primary">
                                            <i class="fas fa-star"></i> تعيين رئيسية
                                        </button>
                                    </form>
                                @else
                                    <span class="badge bg-success p-2">
                                        <i class="fas fa-check-circle"></i> الصورة الرئيسية
                                    </span>
                                @endif
                                <form action="{{ route('admin.cars.delete-image', [$car->id, $image->id]) }}"
                                    method="POST" style="display:inline;"
                                    onsubmit="return confirm('هل أنت متأكد من حذف هذه الصورة؟');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i> حذف
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif
