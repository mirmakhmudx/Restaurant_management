<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('bills', function (Blueprint $table) {
            $table->boolean('is_split')->default(false)->after('grand_total');
            $table->integer('split_count')->default(1)->after('is_split');
            $table->integer('split_index')->default(1)->after('split_count');
            $table->unsignedBigInteger('parent_bill_id')->nullable()->after('split_index');
            $table->foreign('parent_bill_id')->references('id')->on('bills')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('bills', function (Blueprint $table) {
            $table->dropForeign(['parent_bill_id']);
            $table->dropColumn(['is_split','split_count','split_index','parent_bill_id']);
        });
    }
};
