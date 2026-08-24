<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('portofolios', function (Blueprint $table) {
            $table->string('klien')->nullable()->after('title');
            $table->string('industry')->nullable()->after('klien');
            $table->string('layanan')->nullable()->after('industry');
            $table->string('brand')->nullable()->after('layanan');
            $table->text('tantangan')->nullable()->after('brand');
            $table->text('solusi')->nullable()->after('tantangan');
            $table->text('fitur')->nullable()->after('solusi');
        });
    }

    public function down()
    {
        Schema::table('portofolios', function (Blueprint $table) {
            $table->dropColumn([
                'klien',
                'industry',
                'layanan',
                'brand',
                'tantangan',
                'solusi',
                'fitur'
            ]);
        });
    }
};
