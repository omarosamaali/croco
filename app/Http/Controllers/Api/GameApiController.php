<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;

class GameApiController extends Controller
{
    /**
     * التحقق من صلاحية رمز التفعيل وإرجاع بيانات الاشتراك
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkActivationCode(Request $request)
    {
        // التحقق من صحة المدخلات
        $validator = Validator::make($request->all(), [
            'activation_code' => 'required|string|size:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'بيانات غير صالحة',
                'errors' => $validator->errors()
            ], 422);
        }

        // البحث عن الاشتراك بواسطة رمز التفعيل
        $game = Subscriber::where('activation_code', $request->activation_code)
            ->with(['mainCategory:id,name_ar,name_en,image', 'subCategory:id,name_ar,name_en,duration'])
            ->first();

        if (!$game) {
            return response()->json([
                'status' => false,
                'message' => 'رمز التفعيل غير صالح'
            ], 404);
        }

        // التحقق من أن الاشتراك فعال وغير منتهي
        if ($game->status !== 'active') {
            return response()->json([
                'status' => false,
                'message' => 'الاشتراك غير فعال حاليًا',
                'subscription_status' => $game->status
            ], 403);
        }

        // التحقق من تاريخ انتهاء الاشتراك
        $today = Carbon::now()->startOfDay();
        $expiryDate = Carbon::parse($game->expiry_date)->startOfDay();

        if ($today->gt($expiryDate)) {
            // تحديث حالة الاشتراك إلى منتهي
            $game->update(['status' => 'expired']);

            return response()->json([
                'status' => false,
                'message' => 'الاشتراك منتهي الصلاحية',
                'subscription_status' => 'expired',
                'expiry_date' => $game->expiry_date
            ], 403);
        }

        // معالجة بيانات DNS
        $dnsServers = json_decode($game->dns_link, true) ?? [];
        $dnsInfo = [];

        foreach ($dnsServers as $server) {
            if (!empty($server['username']) || !empty($server['url'])) {
                $dnsInfo[] = [
                    'username' => $server['username'] ?? '',
                    'url' => $server['url'] ?? ''
                ];
            }
        }

        // إرجاع البيانات المطلوبة
        return response()->json([
            'status' => true,
            'message' => 'تم التحقق من رمز التفعيل بنجاح',
            'subscription_data' => [
                'dns_username' => $game->dns_username,
                'password' => $game->dns_password, 
                'dns_link' => $game->dns_link,
                'name' => $game->name,
                'package_image' => $game->mainCategory->image ?? '',
                'package_name' => $game->mainCategory->name_ar ?? '',
                'package_type' => $game->subCategory->name_ar ?? '',
                // 'registration_date' => $game->registration_date,
                'expiry_date' => $game->dns_expiry_date,
                // 'remaining_days' => $today->diffInDays($expiryDate)
            ]
        ], 200);
    }

    /**
     * استرجاع كلمة المرور الأصلية
     * ملاحظة: هذه الطريقة ليست آمنة، لأن كلمات المرور يجب أن تكون مشفرة دائمًا
     * يجب تنفيذ آلية أكثر أمانًا لإدارة كلمات المرور
     *
     * @param Subscriber $game
     * @return string
     */
    private function getOriginalPassword(Subscriber $game)
    {
        // هذه مجرد وظيفة وهمية للتوضيح
        // في الإنتاج الفعلي، يجب عليك معالجة كلمات المرور بشكل آمن
        // قد تحتاج إلى تعديل نموذج البيانات لتخزين كلمات المرور بشكل غير مشفر للاستخدام في API
        // أو إيجاد آلية آمنة أخرى

        // بما أن كلمة المرور مشفرة في قاعدة البيانات، لا يمكننا استرجاعها بشكل مباشر
        // يمكن أن تفكر في استخدام خدمة أخرى لإدارة كلمات المرور أو تخزينها بطريقة يمكن استرجاعها

        // قم بتنفيذ منطق مناسب هنا
        // كبديل مؤقت، نفترض أننا نستخدم كلمة المرور المشفرة:
        return "••••••••"; // استبدل هذا بالمنطق المناسب لاسترجاع كلمة المرور
    }

    /**
     * تحديث حالة الاشتراك
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateSubscriptionStatus(Request $request)
    {
        // التحقق من صحة المدخلات
        $validator = Validator::make($request->all(), [
            'activation_code' => 'required|string|size:10',
            'status' => 'required|string|in:active,inactive,expired,canceled,pending_dns',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'بيانات غير صالحة',
                'errors' => $validator->errors()
            ], 422);
        }

        // البحث عن الاشتراك بواسطة رمز التفعيل
        $game = Subscriber::where('activation_code', $request->activation_code)->first();

        if (!$game) {
            return response()->json([
                'status' => false,
                'message' => 'رمز التفعيل غير صالح'
            ], 404);
        }

        // تحديث حالة الاشتراك
        $game->update(['status' => $request->status]);

        return response()->json([
            'status' => true,
            'message' => 'تم تحديث حالة الاشتراك بنجاح',
            'subscription_status' => $request->status
        ], 200);
    }

    /**
     * الحصول على معلومات الاشتراك
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSubscriptionInfo(Request $request)
    {
        // التحقق من صحة المدخلات
        $validator = Validator::make($request->all(), [
            'activation_code' => 'required|string|size:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'بيانات غير صالحة',
                'errors' => $validator->errors()
            ], 422);
        }

        // البحث عن الاشتراك بواسطة رمز التفعيل
        $game = Subscriber::where('activation_code', $request->activation_code)
            ->with(['mainCategory:id,name_ar,name_en,image', 'subCategory:id,name_ar,name_en,duration'])
            ->first();

        if (!$game) {
            return response()->json([
                'status' => false,
                'message' => 'رمز التفعيل غير صالح'
            ], 404);
        }

        // حساب الأيام المتبقية
        $today = Carbon::now()->startOfDay();
        $expiryDate = Carbon::parse($game->expiry_date)->startOfDay();
        $remainingDays = $today->gt($expiryDate) ? 0 : $today->diffInDays($expiryDate);

        // إرجاع معلومات الاشتراك
        return response()->json([
            'status' => true,
            'subscription_info' => [
                'name' => $game->name,
                'email' => $game->email,
                'country' => $game->country,
                'package_name' => $game->mainCategory->name_ar ?? '',
                'package_type' => $game->subCategory->name_ar ?? '',
                'registration_date' => $game->registration_date,
                'expiry_date' => $game->expiry_date,
                'status' => $game->status,
                'remaining_days' => $remainingDays
            ]
        ], 200);
    }
}
