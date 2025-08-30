<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('shield', function (Blueprint $table) {
            $youthRoleId = Role::firstOrCreate(['name' => 'youth'])->id;
            $executiveRoleId = Role::firstOrCreate(['name' => 'executive'])->id;
        
            User::where('role', 'USER')->chunkById(100, function ($users) use ($youthRoleId) {
                foreach ($users as $user) {
                    $user->syncRoles([$youthRoleId]);
                }
            });
        
            User::where('role', 'ADMIN')->chunkById(100, function ($users) use ($executiveRoleId) {
                foreach ($users as $user) {
                    $user->syncRoles([$executiveRoleId]);
                }
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shield', function (Blueprint $table) {
            
        });
    }
};
