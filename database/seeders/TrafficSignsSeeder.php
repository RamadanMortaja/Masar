<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class TrafficSignsSeeder extends Seeder
{
    public function run(): void
    {
        // مسار ملف الـ CSV
        $file = storage_path('app/sign.csv'); 
        
        if (!File::exists($file)) {
            $this->command->error("الملف غير موجود في: $file");
            return;
        }

        $handle = fopen($file, "r");
        $firstLine = true;

        while (($data = fgetcsv($handle, 2000, ",")) !== FALSE) {
            if ($firstLine) { $firstLine = false; continue; }

            // تخطي الأسطر الفارغة
            if (empty($data[4])) continue;

            $signNumber = trim($data[4]);

            // تحديد اسم الصورة مع امتدادها الحقيقي من المجلد
            $imageName = $this->getActualImageName($signNumber);

            // التحديث إذا كان الرقم موجوداً أو الإضافة إذا كان جديداً (بدون مسح الجدول)
            DB::table('traffic_signs')->updateOrInsert(
                ['code' => $signNumber],
                [
                    'title'       => $data[0], 
                    'description' => $data[1], 
                    'image'       => $imageName, 
                    'category'    => $this->determineCategory($signNumber),
                    'updated_at'  => now(),
                    'created_at'  => now(),
                ]
            );
        }
        fclose($handle);
        $this->command->info("تم تحديث البيانات بنجاح مع دعم جميع الامتدادات!");
    }

    /**
     * دالة تبحث عن الملف في مجلد all وترجع الاسم مع الامتداد الصحيح
     */
    private function getActualImageName($number) {
        $extensions = ['svg', 'png', 'jpg', 'jpeg', 'webp'];
        
        foreach ($extensions as $ext) {
            // نتحقق من وجود الملف في مجلد public/images/signs/all
            if (File::exists(public_path("images/signs/all/{$number}.{$ext}"))) {
                return "{$number}.{$ext}";
            }
        }
        
        // إذا لم يجد الصورة، نضع الامتداد الافتراضي svg
        return $number . '.svg';
    }

    private function determineCategory($code) {
        $c = (int)$code;
        if ($c >= 1 && $c <= 42)   return 'warning';
        if ($c >= 43 && $c <= 51)  return 'priority';
        if ($c >= 52 && $c <= 84)  return 'prohibition';
        if ($c >= 85 && $c <= 104) return 'mandatory';
        if ($c >= 105 && $c <= 110) return 'info';
        if ($c >= 111 && $c <= 115) return 'road_markings';
        if ($c >= 116) return 'public_transport'; // مثال للقسم الجديد
        return 'info';
    }
}