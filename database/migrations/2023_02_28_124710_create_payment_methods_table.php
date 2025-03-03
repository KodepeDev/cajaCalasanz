<?php

use App\Models\PaymentMethod;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->nullable();
            $table->timestamps();
        });

        PaymentMethod::create([
            'name' => 'Efectivo',
            'code' => 'EFEC',
        ]);
        PaymentMethod::create([
            'name' => 'Transferencia Bancaria',
            'code' => 'TRAN',
        ]);
        PaymentMethod::create([
            'name' => 'Tarjeta de Debito',
            'code' => 'TDEB',
        ]);
        PaymentMethod::create([
            'name' => 'Tarjeta de Crédito',
            'code' => 'TCRE',
        ]);
        PaymentMethod::create([
            'name' => 'Pagos Móviles',
            'code' => 'PMOV',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_methods');
    }
};
