
     <?php

        use Illuminate\Database\Migrations\Migration;
        use Illuminate\Database\Schema\Blueprint;
        use Illuminate\Support\Facades\Schema;

        return new class extends Migration
        {
            public function up(): void
            {
                Schema::create('games', function (Blueprint $table) {
                    $table->id();
                    $table->string('name'); // الاسم (replaces name_ar)
                    $table->string('email'); // البريد الإلكتروني (replaces name_en)
                    $table->string('country'); // قائمة الدولة
                    $table->string('main_category'); // الباقة
                    $table->string('image')->nullable(); // صورة اللعبة (automatic based on main_category)
                    $table->string('sub_category'); // التصنيف الفرعي
                    $table->date('registration_date'); // تاريخ التسجيل (automatic)
                    $table->date('expiry_date'); // تاريخ الانتهاء (automatic based on sub_category)
                    $table->string('username'); // اسم المستخدم
                    $table->string('password'); // كلمة المرور
                    $table->json('dns_servers')->nullable(); // بيانات سيرفر DNS (replaces platforms)
                    $table->string('activation_code', 10)->unique(); // رقم التفعيل (10 digits)
                    $table->enum('status', ['active', 'inactive', 'expired', 'canceled', 'pending_dns'])->default('active'); // الحالة
                    $table->timestamps();
                });
            }

            public function down(): void
            {
                Schema::dropIfExists('games');
            }
        };
