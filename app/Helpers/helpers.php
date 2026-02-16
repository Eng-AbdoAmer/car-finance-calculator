<?php

// app/Helpers/helpers.php

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

//------1-
// في ملف composer.json تأكد من إضافة هذا الجزء لتحميل ملف المساعدات تلقائياً
//   "autoload": {
//         "psr-4": {
//             "App\\": "app/",
//             "Database\\Factories\\": "database/factories/",
//             "Database\\Seeders\\": "database/seeders/"
//         },
//         "files": [
//             "app/Helpers/helpers.php"// أضيف هذا السطر لتحميل ملف المساعدات
//         ]
//     },
//------2-
// composer dump-autoload // نفذ هذا الأمر بعد تعديل composer.json لتحميل المساعدات الجديدة



if (!function_exists('apiResponse')) {
    /**
     * استجابة API موحدة
     */
    function apiResponse($success = true, $message = '', $data = null, $errors = [], $status = 200, $headers = [])
    {
        $response = [
            'success' => $success,
            'message' => $message,
        ];

        if (!is_null($data)) {
            $response['data'] = $data;
        }

        if (!empty($errors)) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $status, $headers);
    }
}

if (!function_exists('successResponse')) {
    /**
     * استجابة نجاح مبسطة
     */
    function successResponse($data = null, $message = 'تم بنجاح', $status = 200)
    {
        return apiResponse(true, $message, $data, [], $status);
    }
}

if (!function_exists('errorResponse')) {
    /**
     * استجابة خطأ مبسطة
     */
    function errorResponse($message = 'حدث خطأ ما', $status = 400, $errors = null)
    {
        return apiResponse(false, $message, null, $errors ?? [], $status);
    }
}

if (!function_exists('validationErrorResponse')) {
    /**
     * استجابة خطأ تحقق
     */
    function validationErrorResponse($validator)
    {
        if ($validator instanceof \Illuminate\Contracts\Validation\Validator) {
            $errors = $validator->errors();
        } else {
            $errors = $validator;
        }

        return errorResponse('البيانات المدخلة غير صحيحة', 422, $errors);
    }
}

if (!function_exists('formatDate')) {
    /**
     * تنسيق التاريخ
     */
    function formatDate($date, string $format = 'Y-m-d'): ?string
    {
        if (!$date) return null;
        return Carbon::parse($date)->format($format);
    }
}

if (!function_exists('formatDateTime')) {
    /**
     * تنسيق التاريخ والوقت
     */
    function formatDateTime($date): ?string
    {
        return $date ? Carbon::parse($date)->toDateTimeString() : null;
    }
}

if (!function_exists('uploadFile')) {
    /**
     * رفع ملف واحد
     */
    function uploadFile(UploadedFile $file, string $folder = 'uploads', string $disk = 'public', array $options = [])
    {
        try {
            $fileName = isset($options['name']) 
                ? $options['name'] . '.' . $file->getClientOriginalExtension()
                : Str::random(40) . '.' . $file->getClientOriginalExtension();

            $path = $file->storeAs($folder, $fileName, $disk);

            if (isset($options['visibility'])) {
                Storage::disk($disk)->setVisibility($path, $options['visibility']);
            }

            return $path;
        } catch (\Exception $e) {
            report($e);
            return false;
        }
    }
}

if (!function_exists('uploadMultipleFiles')) {
    /**
     * رفع عدة ملفات
     */
    function uploadMultipleFiles(array $files, string $folder = 'uploads', string $disk = 'public', array $options = []): array
    {
        $paths = [];
        foreach ($files as $file) {
            if ($file instanceof UploadedFile) {
                $path = uploadFile($file, $folder, $disk, $options);
                if ($path) {
                    $paths[] = $path;
                }
            }
        }
        return $paths;
    }
}

if (!function_exists('deleteFile')) {
    /**
     * حذف ملف
     */
    function deleteFile(?string $path, string $disk = 'public'): bool
    {
        if (!$path) return false;
        return Storage::disk($disk)->exists($path) && Storage::disk($disk)->delete($path);
    }
}

if (!function_exists('deleteMultipleFiles')) {
    /**
     * حذف عدة ملفات
     */
    function deleteMultipleFiles(array $paths, string $disk = 'public'): bool
    {
        $success = true;
        foreach ($paths as $path) {
            if (!deleteFile($path, $disk)) {
                $success = false;
            }
        }
        return $success;
    }
}

if (!function_exists('applyFilters')) {
    /**
     * تطبيق فلاتر متقدمة على Query Builder
     */
    function applyFilters(Builder $query, array $filters, ?Request $request = null): Builder
    {
        $request = $request ?: request();

        foreach ($filters as $field => $operator) {
            $value = $request->input($field);

            if ($value === null || $value === '') {
                continue;
            }

            switch ($operator) {
                case 'like':
                    $query->where($field, 'LIKE', '%' . $value . '%');
                    break;
                case 'ilike':
                    $query->where($field, 'ILIKE', '%' . $value . '%');
                    break;
                case 'in':
                    $values = is_array($value) ? $value : explode(',', $value);
                    $query->whereIn($field, $values);
                    break;
                case 'not_in':
                    $values = is_array($value) ? $value : explode(',', $value);
                    $query->whereNotIn($field, $values);
                    break;
                case 'between':
                    if (is_array($value) && count($value) === 2) {
                        $query->whereBetween($field, $value);
                    } elseif (str_contains($value, ',')) {
                        $range = explode(',', $value, 2);
                        $query->whereBetween($field, $range);
                    }
                    break;
                case 'not_between':
                    if (is_array($value) && count($value) === 2) {
                        $query->whereNotBetween($field, $value);
                    } elseif (str_contains($value, ',')) {
                        $range = explode(',', $value, 2);
                        $query->whereNotBetween($field, $range);
                    }
                    break;
                case 'null':
                    $query->whereNull($field);
                    break;
                case 'not_null':
                    $query->whereNotNull($field);
                    break;
                case 'date':
                    $query->whereDate($field, '=', $value);
                    break;
                case 'date_range':
                    if (is_array($value) && isset($value['from']) && isset($value['to'])) {
                        $query->whereBetween($field, [$value['from'], $value['to']]);
                    } elseif (str_contains($value, ',')) {
                        $dates = explode(',', $value, 2);
                        $query->whereBetween($field, $dates);
                    }
                    break;
                case 'month':
                    $query->whereMonth($field, $value);
                    break;
                case 'year':
                    $query->whereYear($field, $value);
                    break;
                case 'day':
                    $query->whereDay($field, $value);
                    break;
                case 'time':
                    $query->whereTime($field, '=', $value);
                    break;
                case 'json_contains':
                    $query->whereJsonContains($field, $value);
                    break;
                default:
                    $query->where($field, $operator, $value);
                    break;
            }
        }

        return $query;
    }
}

if (!function_exists('applySorting')) {
    /**
     * تطبيق ترتيب متقدم
     */
    function applySorting(Builder $query, string $defaultSort = 'id', string $defaultDirection = 'desc', array $allowedFields = [], ?Request $request = null): Builder
    {
        $request = $request ?: request();

        $sortBy = $request->input('sort_by', $defaultSort);
        $sortDir = strtolower($request->input('sort_dir', $defaultDirection));

        if (!in_array($sortDir, ['asc', 'desc'])) {
            $sortDir = $defaultDirection;
        }

        if (!empty($allowedFields) && !in_array($sortBy, $allowedFields)) {
            $sortBy = $defaultSort;
        }

        return $query->orderBy($sortBy, $sortDir);
    }
}

if (!function_exists('formatPaginated')) {
    /**
     * تنسيق نتيجة pagination بشكل متوافق مع JSON:API
     */
    function formatPaginated($paginator): array
    {
        return [
            'current_page' => $paginator->currentPage(),
            'data' => $paginator->items(),
            'first_page_url' => $paginator->url(1),
            'from' => $paginator->firstItem(),
            'last_page' => $paginator->lastPage(),
            'last_page_url' => $paginator->url($paginator->lastPage()),
            'links' => [
                'next' => $paginator->nextPageUrl(),
                'prev' => $paginator->previousPageUrl(),
            ],
            'next_page_url' => $paginator->nextPageUrl(),
            'path' => $paginator->path(),
            'per_page' => $paginator->perPage(),
            'prev_page_url' => $paginator->previousPageUrl(),
            'to' => $paginator->lastItem(),
            'total' => $paginator->total(),
        ];
    }
}

if (!function_exists('storeModel')) {
    /**
     * إنشاء سجل مع دعم رفع الملفات
     */
    function storeModel(string $modelClass, array $data = [], array $fileFields = []): ?Model
    {
        $data = empty($data) ? request()->all() : $data;

        foreach ($fileFields as $key => $config) {
            if (is_numeric($key)) {
                $field = $config;
                $options = [];
            } else {
                $field = $key;
                $options = $config;
            }

            if (request()->hasFile($field)) {
                $file = request()->file($field);
                $folder = $options['folder'] ?? 'uploads/' . $field;
                $disk = $options['disk'] ?? 'public';
                $uploadOptions = $options['upload_options'] ?? [];

                $path = uploadFile($file, $folder, $disk, $uploadOptions);
                if ($path) {
                    $data[$field] = $path;
                }
            }
        }

        return $modelClass::create($data);
    }
}

if (!function_exists('updateModel')) {
    /**
     * تحديث سجل مع دعم رفع الملفات وحذف القديمة
     */
    function updateModel(Model $record, array $data = [], array $fileFields = []): bool
    {
        $data = empty($data) ? request()->all() : $data;

        foreach ($fileFields as $key => $config) {
            if (is_numeric($key)) {
                $field = $config;
                $options = [];
            } else {
                $field = $key;
                $options = $config;
            }

            if (request()->hasFile($field)) {
                // حذف الملف القديم
                if ($record->$field) {
                    $oldDisk = $options['old_disk'] ?? 'public';
                    deleteFile($record->$field, $oldDisk);
                }

                $file = request()->file($field);
                $folder = $options['folder'] ?? 'uploads/' . $field;
                $disk = $options['disk'] ?? 'public';
                $uploadOptions = $options['upload_options'] ?? [];

                $path = uploadFile($file, $folder, $disk, $uploadOptions);
                if ($path) {
                    $data[$field] = $path;
                }
            } elseif (isset($options['delete_if_empty']) && $options['delete_if_empty'] && empty($data[$field])) {
                if ($record->$field) {
                    $oldDisk = $options['old_disk'] ?? 'public';
                    deleteFile($record->$field, $oldDisk);
                    $data[$field] = null;
                }
            }
        }

        return $record->update($data);
    }
}

if (!function_exists('deleteModel')) {
    /**
     * حذف سجل مع حذف الملفات المرتبطة
     */
    function deleteModel(Model $record, array $fileFields = [], string $disk = 'public')
    {
        foreach ($fileFields as $field) {
            if ($record->$field) {
                deleteFile($record->$field, $disk);
            }
        }

        return $record->delete();
    }
}

if (!function_exists('generateUniqueSlug')) {
    /**
     * توليد slug فريد
     */
    function generateUniqueSlug(string $title, string $modelClass, string $field = 'slug', ?int $ignoreId = null, string $separator = '-'): string
    {
        $slug = Str::slug($title, $separator);
        $originalSlug = $slug;
        $count = 1;

        while (true) {
            $query = $modelClass::where($field, $slug);
            if ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            }
            if (!$query->exists()) {
                break;
            }
            $slug = $originalSlug . $separator . $count++;
        }

        return $slug;
    }
}

if (!function_exists('authId')) {
    /**
     * الحصول على ID المستخدم الحالي
     */
    function authId(?string $guard = null)
    {
        return auth($guard)->id();
    }
}

if (!function_exists('randomString')) {
    /**
     * توليد سلسلة عشوائية
     */
    function randomString(int $length = 10, bool $numbers = true, bool $letters = true, bool $symbols = false): string
    {
        $characters = '';
        if ($numbers) $characters .= '0123456789';
        if ($letters) $characters .= 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        if ($symbols) $characters .= '!@#$%^&*()-_=+';

        if (empty($characters)) {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        }

        return substr(str_shuffle(str_repeat($characters, $length)), 0, $length);
    }
}

if (!function_exists('resizeImage')) {
    /**
     * تغيير حجم صورة باستخدام Intervention (إن وجد)
     * 
     * @param string $path مسار الصورة داخل القرص العام (public)
     * @param int $width العرض الجديد
     * @param int|null $height الارتفاع الجديد (اختياري، إذا لم يحدد يتم الحفاظ على نسبة العرض)
     * @param string|null $destinationPath مسار الحفظ الجديد (اختياري، افتراضياً يضيف "_resized" قبل الامتداد)
     * @param array $options خيارات إضافية مثل ['fit' => true] لقص الصورة لتناسب الأبعاد
     * @return string|false مسار الصورة الجديدة أو false في حالة الفشل أو عدم وجود الحزمة
     */
    function resizeImage(string $path, int $width, ?int $height = null, ?string $destinationPath = null, array $options = [])
    {
        // اسم الكلاس الأساسي من Intervention
        $imageManagerClass = 'Intervention\\Image\\ImageManager';

        // التحقق من وجود الحزمة قبل محاولة استخدامها
        if (!class_exists($imageManagerClass)) {
            return false;
        }

        try {
            // إنشاء مدير الصور باستخدام الـ driver المناسب (يمكن تغييره عبر options)
            $driver = $options['driver'] ?? 'gd';
            $manager = new $imageManagerClass(['driver' => $driver]);

            // الحصول على المسار الكامل للصورة
            $fullPath = Storage::disk('public')->path($path);
            $image = $manager->make($fullPath);

            if (isset($options['fit']) && $options['fit']) {
                $image->fit($width, $height, function ($constraint) {
                    $constraint->upsize();
                });
            } else {
                $image->resize($width, $height, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            }

            if (!$destinationPath) {
                $info = pathinfo($path);
                $destinationPath = $info['dirname'] . '/' . $info['filename'] . '_resized.' . $info['extension'];
            }

            $fullDest = Storage::disk('public')->path($destinationPath);
            $image->save($fullDest);

            return $destinationPath;
        } catch (\Exception $e) {
            report($e);
            return false;
        }
    }
}

if (!function_exists('validateRequest')) {
    /**
     * تبسيط عملية التحقق وإرجاع الأخطاء بشكل موحد
     */
    function validateRequest(array $rules, array $messages = [], array $customAttributes = [])
    {
        $validator = validator(request()->all(), $rules, $messages, $customAttributes);

        if ($validator->fails()) {
            return validationErrorResponse($validator);
        }

        return $validator;
    }
}

if (!function_exists('toCamelCase')) {
    /**
     * تحويل مصفوفة إلى camelCase keys
     */
    function toCamelCase(array $array): array
    {
        $result = [];
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $value = toCamelCase($value);
            }
            $result[Str::camel($key)] = $value;
        }
        return $result;
    }
}

if (!function_exists('toSnakeCase')) {
    /**
     * تحويل مصفوفة إلى snake_case keys
     */
    function toSnakeCase(array $array): array
    {
        $result = [];
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $value = toSnakeCase($value);
            }
            $result[Str::snake($key)] = $value;
        }
        return $result;
    }
}

if (!function_exists('isBase64Image')) {
    /**
     * التحقق مما إذا كانت السلسلة صورة base64 صالحة
     */
    function isBase64Image(string $string): bool
    {
        if (!preg_match('/^data:image\/(\w+);base64,/', $string)) {
            return false;
        }

        $data = substr($string, strpos($string, ',') + 1);
        $decoded = base64_decode($data, true);
        if ($decoded === false) {
            return false;
        }

        $imageInfo = getimagesizefromstring($decoded);
        return $imageInfo !== false;
    }
}

if (!function_exists('extractEmails')) {
    /**
     * استخراج عناوين البريد الإلكتروني من نص
     */
    function extractEmails(string $text): array
    {
        preg_match_all('/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/', $text, $matches);
        return $matches[0] ?? [];
    }
}