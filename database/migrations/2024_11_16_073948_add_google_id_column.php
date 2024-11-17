<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function($table) {
            $table->string('google_id')->nullable();
        });

        if (Schema::hasColumn('users', 'status')){
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
