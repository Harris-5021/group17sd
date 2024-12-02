<?php

// database/migrations/xxxx_xx_xx_xxxxxx_create_vendors_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorsTable extends Migration
{
    public function up()
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('name');  // Vendor name
            $table->string('address')->nullable();  // Optional address
            $table->string('contact')->nullable();  // Optional contact info
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('vendors');
    }
}
