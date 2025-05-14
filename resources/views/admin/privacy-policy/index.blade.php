@extends('layouts.adminLayout')

@section('title', 'سياسة الخصوصية')

@section('content')
<div style="padding: 16px; min-height: 100vh; padding-top: 80px;">
    <div style="max-width: 960px; margin: 0 auto; background-color: #2c2c38; padding: 24px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); border-radius: 8px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <h2 style="font-size: 1.25rem; font-weight: 600; color: white;">سياسة الخصوصية 📜</h2>
            <!--<a href="{{ route('privacy-policy.create', ['lang' => $lang]) }}" style="padding: 8px 16px; background-color: #2563eb; color: white; border-radius: 8px; text-decoration: none;">-->
            <!--    إنشاء سياسة خصوصية-->
            <!--</a>-->
        </div>

        @if (session('success'))
        <div style="background-color: #48bb78; color: white; padding: 12px; border-radius: 8px; margin-bottom: 16px; text-align: right;">
            {{ session('success') }}
        </div>
        @endif

        <div style="overflow-x: auto;">
            <table style="min-width: 100%; background-color: #21212d; border-radius: 8px; overflow: hidden;">
                <thead style="background-color: #2c2c38; color: white;">
                    <tr>
                        <th style="padding: 12px 16px; text-align: right;">#</th>
                        <th style="padding: 12px 16px; text-align: right;">المحتوى (عربي)</th>
                        <th style="padding: 12px 16px; text-align: right;">المحتوى (إنجليزي)</th>
                        <th style="padding: 12px 16px; text-align: right;">التحكم</th>
                    </tr>
                </thead>
                <tbody style="color: white;">
                    @if ($policy)
                    <tr style="background-color: #21212d; border-bottom: 1px solid #2c2c38; cursor: pointer;">
                        <td style="padding: 12px 16px; text-align: right;">1</td>
                        <td style="padding: 12px 16px; text-align: right;">
                            <div style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ Str::limit($policy->content_ar, 50) }}</div>
                        </td>
                        <td style="padding: 12px 16px; text-align: right;">
                            <div style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ Str::limit($policy->content_en, 50) }}</div>
                        </td>
                        <td style="padding: 12px 16px; text-align: right;">
                            <a href="{{ route('privacy-policy.edit', ['lang' => $lang, 'privacyPolicy' => $policy->id]) }}" style="padding: 8px 12px; background-color: #f59e0b; color: white; border-radius: 8px; text-decoration: none;">
                                تعديل
                            </a>
                        </td>
                    </tr>
                    @else
                    <tr>
                        <td colspan="4" style="padding: 24px; text-align: center; color: #9ca3af;">لا توجد سياسة خصوصية حتى الآن</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
