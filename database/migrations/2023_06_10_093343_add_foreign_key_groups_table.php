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
        if (Schema::hasTable('groups')) {
            if (Schema::hasColumn('groups', 'user_id')) {
                Schema::table('groups', function(Blueprint $table){
                    $table->foreign('user_id')
                            ->references('id')
                            ->on('users');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('groups')) {
            if (Schema::hasColumn('groups', 'user_id')) {
                Schema::table('groups', function(Blueprint $table){
                    $table->dropForeign('groups_user_id_foreign');
                });
            }
        }
    }
};
