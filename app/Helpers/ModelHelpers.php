<?php

// app/Helpers/ModelHelpers.php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

if (!function_exists('modelAll')) {
    /**
     * جلب جميع السجلات من الموديل مع إمكانية تحميل العلاقات.
     *
     * @param string $model اسم الموديل (مثل: User::class)
     * @param array $with العلاقات المراد تحميلها
     * @param array $columns الأعمدة المطلوبة
     * @return \Illuminate\Database\Eloquent\Collection
     */
    function modelAll($model, $with = [], $columns = ['*'])
    {
        return $model::with($with)->get($columns);
    }
}

if (!function_exists('modelFind')) {
    /**
     * العثور على سجل بواسطة المفتاح الأساسي مع إمكانية تحميل العلاقات.
     *
     * @param string $model
     * @param mixed $id
     * @param array $with
     * @param array $columns
     * @return Model|null
     */
    function modelFind($model, $id, $with = [], $columns = ['*'])
    {
        return $model::with($with)->find($id, $columns);
    }
}

if (!function_exists('modelFindOrFail')) {
    /**
     * العثور على سجل أو رمي خطأ 404.
     *
     * @param string $model
     * @param mixed $id
     * @param array $with
     * @param array $columns
     * @return Model
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    function modelFindOrFail($model, $id, $with = [], $columns = ['*'])
    {
        return $model::with($with)->findOrFail($id, $columns);
    }
}

if (!function_exists('modelFindBy')) {
    /**
     * العثور على أول سجل يطابق شرط معين.
     *
     * @param string $model
     * @param string $field
     * @param mixed $value
     * @param array $with
     * @param array $columns
     * @return Model|null
     */
    function modelFindBy($model, $field, $value, $with = [], $columns = ['*'])
    {
        return $model::with($with)->where($field, $value)->first($columns);
    }
}

if (!function_exists('modelCreate')) {
    /**
     * إنشاء سجل جديد في قاعدة البيانات.
     *
     * @param string $model
     * @param array $data
     * @return Model
     */
    function modelCreate($model, array $data)
    {
        return $model::create($data);
    }
}

if (!function_exists('modelUpdate')) {
    /**
     * تحديث سجل موجود بواسطة المفتاح الأساسي.
     *
     * @param string $model
     * @param mixed $id
     * @param array $data
     * @return bool|Model
     */
    function modelUpdate($model, $id, array $data)
    {
        $record = $model::find($id);
        if ($record) {
            $record->update($data);
            return $record;
        }
        return false;
    }
}

if (!function_exists('modelUpdateOrCreate')) {
    /**
     * تحديث أو إنشاء سجل بناءً على شروط.
     *
     * @param string $model
     * @param array $attributes شروط البحث
     * @param array $values القيم المطلوب تحديثها/إنشاؤها (اختياري)
     * @return Model
     */
    function modelUpdateOrCreate($model, array $attributes, array $values = [])
    {
        return $model::updateOrCreate($attributes, $values);
    }
}

if (!function_exists('modelDelete')) {
    /**
     * حذف سجل بواسطة المفتاح الأساسي.
     *
     * @param string $model
     * @param mixed $id
     * @return bool
     */
    function modelDelete($model, $id)
    {
        $record = $model::find($id);
        return $record ? $record->delete() : false;
    }
}

if (!function_exists('modelDeleteWhere')) {
    /**
     * حذف سجلات تطابق شروط معينة.
     *
     * @param string $model
     * @param array $conditions شروط على شكل [field => value]
     * @return int عدد السجلات المحذوفة
     */
    function modelDeleteWhere($model, array $conditions)
    {
        return $model::where($conditions)->delete();
    }
}

if (!function_exists('modelPaginate')) {
    /**
     * جلب النتائج مقسمة إلى صفحات.
     *
     * @param string $model
     * @param int $perPage عدد النتائج في الصفحة
     * @param array $with
     * @param array $columns
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    function modelPaginate($model, $perPage = 15, $with = [], $columns = ['*'])
    {
        return $model::with($with)->paginate($perPage, $columns);
    }
}

if (!function_exists('modelWhere')) {
    /**
     * جلب سجلات تطابق شروط معينة.
     *
     * @param string $model
     * @param array $conditions شروط [field => value] أو [field, operator, value]
     * @param array $with
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Collection
     */
    function modelWhere($model, array $conditions, $with = [], $columns = ['*'])
    {
        $query = $model::with($with);
        foreach ($conditions as $key => $value) {
            if (is_array($value) && count($value) === 3) {
                // صيغة: [field, operator, value]
                $query->where($value[0], $value[1], $value[2]);
            } else {
                // صيغة: [field => value]
                $query->where($key, $value);
            }
        }
        return $query->get($columns);
    }
}

if (!function_exists('modelFirstWhere')) {
    /**
     * جلب أول سجل يطابق شروط معينة.
     *
     * @param string $model
     * @param array $conditions
     * @param array $with
     * @param array $columns
     * @return Model|null
     */
    function modelFirstWhere($model, array $conditions, $with = [], $columns = ['*'])
    {
        $query = $model::with($with);
        foreach ($conditions as $key => $value) {
            if (is_array($value) && count($value) === 3) {
                $query->where($value[0], $value[1], $value[2]);
            } else {
                $query->where($key, $value);
            }
        }
        return $query->first($columns);
    }
}

if (!function_exists('modelCount')) {
    /**
     * عدد السجلات (مع شروط اختيارية).
     *
     * @param string $model
     * @param array $conditions
     * @return int
     */
    function modelCount($model, array $conditions = [])
    {
        $query = $model::query();
        foreach ($conditions as $key => $value) {
            if (is_array($value) && count($value) === 3) {
                $query->where($value[0], $value[1], $value[2]);
            } else {
                $query->where($key, $value);
            }
        }
        return $query->count();
    }
}

if (!function_exists('modelWith')) {
    /**
     * بدء استعلام مع تحميل علاقات معينة.
     *
     * @param string $model
     * @param array|string $relations
     * @param callable|null $callback
     * @return \Illuminate\Database\Eloquent\Builder
     */
    function modelWith($model, $relations, $callback = null)
    {
        return $model::with($relations, $callback);
    }
}

if (!function_exists('modelLatest')) {
    /**
     * جلب السجلات مرتبة حسب الأحدث.
     *
     * @param string $model
     * @param string $column
     * @param array $with
     * @return \Illuminate\Database\Eloquent\Collection
     */
    function modelLatest($model, $column = 'created_at', $with = [])
    {
        return $model::with($with)->latest($column)->get();
    }
}

if (!function_exists('modelOldest')) {
    /**
     * جلب السجلات مرتبة حسب الأقدم.
     *
     * @param string $model
     * @param string $column
     * @param array $with
     * @return \Illuminate\Database\Eloquent\Collection
     */
    function modelOldest($model, $column = 'created_at', $with = [])
    {
        return $model::with($with)->oldest($column)->get();
    }
}

if (!function_exists('modelPluck')) {
    /**
     * جلب قيمة عمود معين (أو عدة أعمدة) من السجلات.
     *
     * @param string $model
     * @param string $value اسم العمود المطلوب قيمته
     * @param string|null $key اسم عمود المفتاح (اختياري)
     * @param array $conditions شروط اختيارية
     * @return \Illuminate\Support\Collection
     */
    function modelPluck($model, $value, $key = null, array $conditions = [])
    {
        $query = $model::query();
        foreach ($conditions as $field => $val) {
            $query->where($field, $val);
        }
        return $query->pluck($value, $key);
    }
}

if (!function_exists('modelIncrement')) {
    /**
     * زيادة قيمة عمود معين لسجل محدد.
     *
     * @param string $model
     * @param mixed $id
     * @param string $column
     * @param int $amount
     * @return bool
     */
    function modelIncrement($model, $id, $column, $amount = 1)
    {
        return $model::where('id', $id)->increment($column, $amount);
    }
}

if (!function_exists('modelDecrement')) {
    /**
     * إنقاص قيمة عمود معين لسجل محدد.
     *
     * @param string $model
     * @param mixed $id
     * @param string $column
     * @param int $amount
     * @return bool
     */
    function modelDecrement($model, $id, $column, $amount = 1)
    {
        return $model::where('id', $id)->decrement($column, $amount);
    }
}

if (!function_exists('modelExists')) {
    /**
     * التحقق من وجود سجل يطابق شروط معينة.
     *
     * @param string $model
     * @param array $conditions
     * @return bool
     */
    function modelExists($model, array $conditions)
    {
        $query = $model::query();
        foreach ($conditions as $key => $value) {
            $query->where($key, $value);
        }
        return $query->exists();
    }
}

if (!function_exists('modelDoesntExist')) {
    /**
     * التحقق من عدم وجود سجل يطابق شروط معينة.
     *
     * @param string $model
     * @param array $conditions
     * @return bool
     */
    function modelDoesntExist($model, array $conditions)
    {
        return !modelExists($model, $conditions);
    }
}

if (!function_exists('modelChunk')) {
    /**
     * معالجة النتائج على شكل أجزاء (chunks) لتقليل استهلاك الذاكرة.
     *
     * @param string $model
     * @param int $count حجم الجزء
     * @param callable $callback الدالة التي تستقبل كل جزء
     * @param array $conditions شروط اختيارية
     * @return bool
     */
    function modelChunk($model, $count, callable $callback, array $conditions = [])
    {
        $query = $model::query();
        foreach ($conditions as $key => $value) {
            $query->where($key, $value);
        }
        return $query->chunk($count, $callback);
    }
}

if (!function_exists('modelFirstOrCreate')) {
    /**
     * جلب أول سجل يطابق الشروط أو إنشاءه إذا لم يوجد.
     *
     * @param string $model
     * @param array $attributes شروط البحث
     * @param array $values القيم الإضافية عند الإنشاء
     * @return Model
     */
    function modelFirstOrCreate($model, array $attributes, array $values = [])
    {
        return $model::firstOrCreate($attributes, $values);
    }
}

if (!function_exists('modelFirstOrNew')) {
    /**
     * جلب أول سجل يطابق الشروط أو إنشاء كائن جديد (غير محفوظ).
     *
     * @param string $model
     * @param array $attributes
     * @param array $values
     * @return Model
     */
    function modelFirstOrNew($model, array $attributes, array $values = [])
    {
        return $model::firstOrNew($attributes, $values);
    }
}

if (!function_exists('modelTrashed')) {
    /**
     * التعامل مع السجلات المحذوفة (Soft Deletes).
     *
     * @param string $model
     * @param bool $withTrashed يتضمن المحذوفة
     * @param bool $onlyTrashed فقط المحذوفة
     * @return \Illuminate\Database\Eloquent\Builder
     */
    function modelTrashed($model, $withTrashed = false, $onlyTrashed = false)
    {
        $query = $model::query();
        if ($withTrashed) {
            $query->withTrashed();
        } elseif ($onlyTrashed) {
            $query->onlyTrashed();
        }
        return $query;
    }
}

if (!function_exists('modelRestore')) {
    /**
     * استعادة سجل محذوف (Soft Delete).
     *
     * @param string $model
     * @param mixed $id
     * @return bool
     */
    function modelRestore($model, $id)
    {
        $record = $model::withTrashed()->find($id);
        return $record ? $record->restore() : false;
    }
}

if (!function_exists('modelForceDelete')) {
    /**
     * حذف نهائي لسجل (حتى مع Soft Delete).
     *
     * @param string $model
     * @param mixed $id
     * @return bool
     */
    function modelForceDelete($model, $id)
    {
        $record = $model::withTrashed()->find($id);
        return $record ? $record->forceDelete() : false;
    }
}