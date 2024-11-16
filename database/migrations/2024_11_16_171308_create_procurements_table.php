<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProcurementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('procurements', function (Blueprint $table) {
            $table->id('procurement_id');
            $table->foreignId('media_id')->nullable()->constrained('media');
            $table->date('procurement_date');
            $table->string('procurement_type');
            $table->string('supplier_name');
            $table->decimal('procurement_cost', 10, 2);
            $table->string('payment_status');
            $table->timestamps(); // This creates both created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('procurements');
    }
}
