<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CarTrimSeeder extends Seeder
{
    public function run(): void
    {
        // تعطيل فحص المفاتيح الخارجية مؤقتاً
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('car_trims')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        // إعادة تعيين الـ Auto Increment
        DB::statement('ALTER TABLE car_trims AUTO_INCREMENT = 1');

        $data = [
            // تويوتا - الفئات الشائعة في السعودية
            'تويوتا' => [
                'كامري' => ['LE', 'SE', 'SE Plus', 'XLE', 'XSE', 'TRD', 'Hybrid'],
                'كورولا' => ['L', 'LE', 'XLI', 'GLI', 'SE', 'XSE', 'Hybrid', 'GR'],
                'كورولا كروس' => ['XLI', 'GLI', 'Hybrid'],
                'لاند كروزر' => ['GX', 'GXR', 'VX', 'VXR', 'VXS', 'GR Sport'],
                'برادو' => ['TX', 'TXL', 'VX', 'VXR', 'VXS'],
                'هايلكس' => ['GL', 'GL2', 'SR', 'SR5', 'Adventure', 'GR Sport'],
                'فورتشنر' => ['GX2', 'VX1', 'VX2', 'Legender'],
                'راڤ4' => ['LE', 'XLE', 'Adventure', 'Limited', 'Hybrid'],
                'يارس' => ['Y', 'Y Plus', 'SE'],
                'يارس كروس' => ['XLI', 'GLI'],
                'افالون' => ['XLE', 'Limited'],
                'سيكويا' => ['SR5', 'Limited', 'Platinum', 'TRD Pro'],
                'سي-أتش-أر' => ['LE', 'XLE', 'Limited'],
                'هايلاندر' => ['L', 'LE', 'XLE', 'Limited', 'Platinum'],
                'بريوس' => ['L', 'LE', 'XLE'],
                'تندرا' => ['SR', 'SR5', 'Limited', 'TRD Pro'],
                'كراون' => ['Royal Saloon', 'Athlete', 'Majesta'],
            ],
            
            // لكزس
            'لكزس' => [
                'ES' => ['250', '300h', '350', 'F Sport', 'Platinum'],
                'IS' => ['300', '350', 'F Sport'],
                'LS' => ['500', '500h', 'Executive'],
                'RX' => ['350', '350h', '450h+', '500h', 'F Sport'],
                'NX' => ['250', '350', '350h', '450h+', 'F Sport'],
                'GX' => ['460', 'Premium', 'Luxury'],
                'LX' => ['600', 'F Sport', 'VIP'],
                'UX' => ['200', '250h'],
                'LC' => ['500', '500h'],
            ],
            
            // نيسان
            'نيسان' => [
                'صني' => ['Base', 'SV', 'SL', 'Premium'],
                'التيما' => ['S', 'SV', 'SR', 'SL'],
                'ماكسيما' => ['SV', 'SR', 'Platinum'],
                'باترول' => ['XE', 'SE', 'LE', 'Platinum', 'Super Safari'],
                'اكستيرا' => ['S', 'SV', 'Titanium', 'Platinum'],
                'كيكس' => ['S', 'SV', 'SL'],
                'اكس تريل' => ['S', 'SV', 'SL'],
                'باثفايندر' => ['S', 'SV', 'SL', 'Platinum'],
                'نافارا' => ['SE', 'LE', 'Pro-4X'],
                'Z' => ['Sport', 'Performance'],
                'باترول سفاري' => ['Standard', 'Super Safari'],
                'أرمادا' => ['SL', 'Platinum'],
                'فرونتير' => ['S', 'SV', 'Pro-4X'],
            ],
            
            // هيونداي
            'هيونداي' => [
                'سوناتا' => ['SE', 'SEL', 'Limited', 'N Line'],
                'اكسنت' => ['Base', 'Active', 'Style', 'Prestige'],
                'توسان' => ['SEL', 'Limited', 'Calligraphy', 'N Line'],
                'كريتا' => ['Comfort', 'Smart', 'Mid 2Tone', 'Ultimate'],
                'النترا' => ['Standard', 'Sport', 'Hybrid', 'N Line'],
                'سانتا في' => ['GL Smart', 'Limited', 'Calligraphy'],
                'بالي ساد' => ['Comfort', 'Smart', 'Smart Plus', 'Calligraphy'],
                'ايونيك 5' => ['Standard EV'],
                'ستاريا' => ['Van', 'Premium', 'Luxury'],
                'كونا' => ['SE', 'SEL', 'Limited', 'N Line'],
                'فلويد' => ['SEL', 'Limited', 'Calligraphy'],
            ],
            
            // كيا
            'كيا' => [
                'سبورتيج' => ['LX', 'EX', 'SX', 'GT-Line', 'X-Line'],
                'سيراتو' => ['FE', 'LXS', 'GT-Line', 'GT'],
                'سورينتو' => ['LX', 'SX', 'EX', 'SX-Prestige'],
                'كارينز' => ['LX', 'EX', 'SX'],
                'بيكانتو' => ['LX', 'EX', 'SX'],
                'ستينجر' => ['GT-Line', 'GT'],
                'سول' => ['EV', 'GT-Line'],
                'سينغ' => ['LX', 'EX', 'SX'],
                'موهافي' => ['LX', 'EX', 'SX'],
                'سيد' => ['LX', 'EX', 'SX'],
            ],
            
            // مرسيدس-بنز
            'مرسيدس-بنز' => [
                'فئة C' => ['C 200', 'C 300', 'AMG C 43', 'AMG C 63'],
                'فئة E' => ['E 200', 'E 300', 'E 400', 'AMG E 53'],
                'فئة S' => ['S 450', 'S 500', 'S 580', 'Maybach'],
                'GLC' => ['GLC 300', 'AMG GLC 43', 'AMG GLC 63'],
                'GLE' => ['GLE 350', 'GLE 450', 'AMG GLE 53', 'AMG GLE 63'],
                'فئة A' => ['A 200', 'A 250', 'AMG A 35', 'AMG A 45'],
                'فئة CLA' => ['CLA 200', 'CLA 250', 'AMG CLA 35', 'AMG CLA 45'],
            ],
            
            // بي إم دبليو
            'بي إم دبليو' => [
                'سيريز 3' => ['320i', '330i', 'M340i', 'M3'],
                'سيريز 5' => ['530i', '540i', 'M550i', 'M5'],
                'سيريز 7' => ['740i', '750i', 'M760i', 'Alpina'],
                'X5' => ['xDrive40i', 'M50i', 'X5 M'],
                'X6' => ['xDrive40i', 'M50i', 'X6 M'],
                'X3' => ['xDrive30i', 'M40i'],
                'X7' => ['xDrive40i', 'M50i'],
            ],
            
            // فورد
            'فورد' => [
                'فيوجن' => ['S', 'SE', 'SEL', 'Titanium'],
                'ايدج' => ['SE', 'SEL', 'Titanium', 'ST'],
                'توروس' => ['SE', 'SEL', 'Limited', 'SHO'],
                'رينجر' => ['XL', 'XLT', 'Lariat', 'Raptor'],
                'موستانج' => ['EcoBoost', 'GT', 'Shelby GT500'],
                'تريتوري' => ['Ambiente', 'Trend', 'Titanium'],
                'اكسبلورر' => ['XLT', 'Limited', 'ST', 'Platinum'],
            ],
            
            // شيفروليه
            'شيفروليه' => [
                'ماليبو' => ['L', 'LS', 'LT', 'Premier'],
                'تاهو' => ['LS', 'LT', 'RST', 'Premier'],
                'كمارو' => ['LS', 'LT', 'SS', 'ZL1'],
                'كابتيفا' => ['LS', 'LT', 'Premier'],
                'سوبربان' => ['LS', 'LT', 'Premier'],
                'ترافيرس' => ['LS', 'LT', 'Premier'],
            ],
            
            // تسلا
            'تسلا' => [
                'موديل 3' => ['Standard Range', 'Long Range', 'Performance'],
                'موديل S' => ['Long Range', 'Plaid'],
                'موديل X' => ['Long Range', 'Plaid'],
                'موديل Y' => ['Long Range', 'Performance'],
            ],
            
            // إضافة الماركات الأخرى بفئات أساسية
            'هوندا' => [
                'سيفيك' => ['LX', 'EX', 'Touring'],
                'أكورد' => ['LX', 'EX', 'Touring'],
                'سي-أر-ڤي' => ['LX', 'EX', 'Touring'],
            ],
            
            'ميتسوبيشي' => [
                'لانسر' => ['ES', 'SE', 'GT'],
                'باجيرو' => ['GLX', 'GLS', 'Exceed'],
            ],
            
            'سوزوكي' => [
                'فيتارا' => ['GL', 'GLX', 'S'],
                'سويفت' => ['GL', 'GLX'],
            ],
        ];

        $totalAdded = 0;
        
        foreach ($data as $brandName => $types) {
            $brand = DB::table('car_brands')->where('name', $brandName)->first();
            
            if (!$brand) {
                $this->command->warn("⚠️  لم يتم العثور على العلامة التجارية: {$brandName}");
                continue;
            }
            
            foreach ($types as $typeName => $trims) {
                $type = DB::table('car_types')
                    ->where('name', $typeName)
                    ->where('car_brand_id', $brand->id)
                    ->first();
                
                if (!$type) {
                    $this->command->warn("⚠️  لم يتم العثور على النوع: {$typeName} للعلامة: {$brandName}");
                    continue;
                }
                
                foreach ($trims as $trimName) {
                    DB::table('car_trims')->insert([
                        'name' => $trimName,
                        'car_type_id' => $type->id,
                        'car_brand_id' => $brand->id,
                        'code' => $this->generateUniqueCode($brand->id, $type->id, $trimName),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    $totalAdded++;
                }
                $this->command->info("➕ تم إضافة " . count($trims) . " فئة لـ {$brandName} - {$typeName}");
            }
        }

        $count = DB::table('car_trims')->count();
        $this->command->info("✅ تم إضافة {$totalAdded} فئة جديدة");
        $this->command->info("📊 العدد الإجمالي للفئات: {$count}");
    }

    private function generateUniqueCode($brandId, $typeId, $trimName): string
    {
        // إنشاء كود فريد باستخدام brand_id, type_id و trim name
        $brandCode = str_pad($brandId, 3, '0', STR_PAD_LEFT);
        $typeCode = str_pad($typeId, 3, '0', STR_PAD_LEFT);
        $trimCode = Str::slug($trimName, '_');
        
        return "B{$brandCode}T{$typeCode}_{$trimCode}";
    }
}