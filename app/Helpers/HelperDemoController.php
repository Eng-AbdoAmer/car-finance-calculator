<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * هذا الكنترولر يشرح استخدام جميع الدوال المساعدة (Helpers) بشكل مبسط
 * كل دالة مرفقة مع مثال عملي وتعليق يوضح فائدتها
 */
class HelperDemoController extends Controller
{
    /**
     * ====================
     * دوال الاستجابات (Responses)
     * ====================
     */

    /**
     * successResponse: إرجاع استجابة نجاح
     * الاستخدام: successResponse($data, $message, $status)
     */
    public function testSuccess()
    {
        $data = ['name' => ' Ahmed', 'age' => 25];
        return successResponse($data, 'تم جلب البيانات بنجاح');
    }

    /**
     * errorResponse: إرجاع استجابة خطأ
     * الاستخدام: errorResponse($message, $status, $errors)
     */
    public function testError()
    {
        return errorResponse('حدث خطأ في المعالجة', 400);
    }

    /**
     * validationErrorResponse: إرجاع أخطاء التحقق (Validation)
     * الاستخدام: validationErrorResponse($validator)
     */
    public function testValidationError(Request $request)
    {
        $validator = validator($request->all(), [
            'email' => 'required|email'
        ]);

        if ($validator->fails()) {
            return validationErrorResponse($validator); // ترجع الأخطاء بشكل موحد
        }

        return successResponse(null, 'البيانات صحيحة');
    }

    /**
     * ====================
     * دوال التاريخ (Date Helpers)
     * ====================
     */

    /**
     * formatDate: تنسيق التاريخ (افتراضي Y-m-d)
     * formatDateTime: تنسيق التاريخ والوقت (Y-m-d H:i:s)
     */
    public function testDate()
    {
        $now = now();
        $formattedDate = formatDate($now, 'd/m/Y'); // 14/02/2026
        $formattedDateTime = formatDateTime($now); // 2026-02-14 15:30:00

        return successResponse([
            'original' => $now,
            'formatted_date' => $formattedDate,
            'formatted_datetime' => $formattedDateTime
        ]);
    }

    /**
     * ====================
     * دوال رفع الملفات (File Upload Helpers)
     * ====================
     */

    /**
     * uploadFile: رفع ملف واحد
     */
    public function testUploadFile(Request $request)
    {
        $request->validate(['file' => 'required|image']);

        $path = uploadFile($request->file('file'), 'uploads/test', 'public');

        if ($path) {
            return successResponse(['path' => $path], 'تم رفع الملف');
        }

        return errorResponse('فشل الرفع');
    }

    /**
     * uploadMultipleFiles: رفع عدة ملفات
     */
    public function testUploadMultiple(Request $request)
    {
        $request->validate([
            'files' => 'required|array',
            'files.*' => 'image'
        ]);

        $paths = uploadMultipleFiles($request->file('files'), 'uploads/gallery', 'public');

        return successResponse(['paths' => $paths], 'تم رفع ' . count($paths) . ' ملف');
    }

    /**
     * deleteFile: حذف ملف
     */
    public function testDeleteFile(Request $request)
    {
        $path = $request->input('path'); // مثال: uploads/test/image.jpg

        if (deleteFile($path, 'public')) {
            return successResponse(null, 'تم الحذف');
        }

        return errorResponse('الملف غير موجود');
    }

    /**
     * ====================
     * دوال الفلترة والترتيب (Filtering & Sorting)
     * ====================
     */

    /**
     * applyFilters: تطبيق فلاتر على Query Builder
     * applySorting: تطبيق ترتيب
     * 
     * مثال: GET /posts?title=laravel&status=published&sort_by=created_at&sort_dir=desc
     */
    public function testFilters(Request $request)
    {
      //  $query = Post::query();

        // تعريف الفلاتر المسموح بها ونوع كل فلتر
        $filters = [
            'title' => 'like',
            'status' => '=',
            'category_id' => 'in',
            'created_at' => 'date_range'
        ];

        // تطبيق الفلاتر (تستخدم request الحالي)
       // $query = applyFilters($query, $filters, $request);

        // تطبيق الترتيب (الحقول المسموحة: id, title, created_at)
      //  $query = applySorting($query, 'id', 'desc', ['id', 'title', 'created_at'], $request);

       // $posts = $query->paginate(10);

        // return successResponse([
        //     'posts' => $posts->items(),
        //     'pagination' => formatPaginated($posts)
        // ]);
    }

    /**
     * formatPaginated: تنسيق البيانات المقسمة (Pagination)
     * تستخدم مع أي كائن paginator
     */
    public function testPagination()
    {
       // $posts = Post::paginate(5);
       // $formatted = formatPaginated($posts);

       // return successResponse($formatted);
    }

    /**
     * ====================
     * دوال التعامل مع الموديلات (CRUD with files)
     * ====================
     */

    /**
     * storeModel: إنشاء سجل مع رفع ملفات (اختياري)
     * المثال: إنشاء مقال مع صورة
     */
    public function testStoreModel(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'content' => 'required',
            'image' => 'nullable|image'
        ]);

        // تعريف حقول الملفات مع خيارات (اختياري)
        $fileFields = [
            'image' => [
                'folder' => 'posts/images',
                'disk' => 'public'
            ]
        ];

        // البيانات (يمكن أخذها من request أو تمريرها يدوياً)
        $data = $request->only(['title', 'content']);

        // إضافة slug فريد (اختياري)
      //  $data['slug'] = generateUniqueSlug($request->title, Post::class);

        // إنشاء السجل
       // $post = storeModel(Post::class, $data, $fileFields);

       // return successResponse($post, 'تم الإنشاء', 201);
    }

    /**
     * updateModel: تحديث سجل مع رفع ملف جديد وحذف القديم
     */
    public function testUpdateModel(Request $request, $id)
    {
        // $post = Post::find($id);
        // if (!$post) {
        //     return errorResponse('غير موجود', 404);
        // }

        $request->validate([
            'title' => 'sometimes|string',
            'content' => 'sometimes',
            'image' => 'nullable|image'
        ]);

        $fileFields = [
            'image' => [
                'folder' => 'posts/images',
                'disk' => 'public',
                'old_disk' => 'public', // القرص الذي فيه الصورة القديمة
                'delete_if_empty' => true // إذا أرسلت image = null فسيحذف الصورة
            ]
        ];

        $data = $request->only(['title', 'content']);

        // if ($request->has('title') && $request->title != $post->title) {
        //     $data['slug'] = generateUniqueSlug($request->title, Post::class, 'slug', $id);
        // }

        // $updated = updateModel($post, $data, $fileFields);

        // return successResponse($post, 'تم التحديث');
    }

    /**
     * deleteModel: حذف سجل مع حذف ملفاته
     */
    public function testDeleteModel($id)
    {
       // $post = Post::find($id);
        // if (!$post) {
        //     return errorResponse('غير موجود', 404);
        // }

        // حذف المقال والصورة المرتبطة (حقل image)
       // deleteModel($post, ['image']);

        return successResponse(null, 'تم الحذف');
    }

    /**
     * generateUniqueSlug: توليد slug فريد
     */
    public function testGenerateSlug(Request $request)
    {
        $title = $request->input('title', 'Example Title');
       // $slug = generateUniqueSlug($title, Post::class, 'slug');

       // return successResponse(['slug' => $slug]);
    }

    /**
     * ====================
     * دوال متنوعة أخرى
     * ====================
     */

    /**
     * authId: إرجاع ID المستخدم الحالي
     */
    public function testAuthId()
    {
        $userId = authId(); // أو authId('api') إذا كان guard معين
        return successResponse(['user_id' => $userId]);
    }

    /**
     * randomString: توليد سلسلة عشوائية
     */
    public function testRandomString()
    {
        $random = randomString(12, true, true, false); // طول 12، أرقام وحروف فقط
        return successResponse(['random' => $random]);
    }

    /**
     * resizeImage: تغيير حجم صورة (يتطلب Intervention)
     */
    public function testResizeImage(Request $request)
    {
        $path = $request->input('path'); // مسار الصورة الأصلية في public disk
        $width = $request->input('width', 300);

        $newPath = resizeImage($path, $width, null, null, ['fit' => true]);

        if ($newPath) {
            return successResponse(['resized' => $newPath]);
        }

        return errorResponse('فشل التغيير أو الحزمة غير مثبتة');
    }

    /**
     * isBase64Image: التحقق من صورة base64
     */
    public function testBase64Image(Request $request)
    {
        $base64 = $request->input('base64');
        if (isBase64Image($base64)) {
            return successResponse(null, 'صورة صالحة');
        }
        return errorResponse('صورة غير صالحة');
    }

    /**
     * extractEmails: استخراج الإيميلات من نص
     */
    public function testExtractEmails(Request $request)
    {
        $text = $request->input('text', 'Contact us at info@example.com or support@test.com');
        $emails = extractEmails($text);

        return successResponse(['emails' => $emails]);
    }

    /**
     * validateRequest: تبسيط عملية التحقق
     */
    public function testValidateRequest(Request $request)
    {
        // validateRequest ترجع إما Validator أو JsonResponse إذا فشل
        $validator = validateRequest([
            'name' => 'required|string|max:255',
            'age' => 'required|integer|min:18'
        ]);

        // إذا كان الخطأ JsonResponse نرجعه
        if ($validator instanceof \Illuminate\Http\JsonResponse) {
            return $validator;
        }

        // إذا وصلنا هنا فالبيانات صحيحة
        return successResponse($request->only(['name', 'age']));
    }

    /**
     * toCamelCase / toSnakeCase: تحويل أسماء المفاتيح في المصفوفات
     */
    public function testCaseConversion()
    {
        $data = [
            'first_name' => 'Ahmed',
            'last_name' => 'Ali',
            'user_email' => 'ahmed@test.com'
        ];

        $camel = toCamelCase($data); // ['firstName' => 'Ahmed', ...]
        $snake = toSnakeCase($camel); // يعود كما كان

        return successResponse([
            'original' => $data,
            'camel' => $camel,
            'snake' => $snake
        ]);
    }
}