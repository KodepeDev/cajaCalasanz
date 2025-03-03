<?php

use App\Models\Customer;
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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('full_name')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->nullable();
            $table->string('document_type')->nullable();
            $table->string('document')->nullable();;
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->boolean('is_ative')->default(true);
            $table->boolean('is_tutor')->default(false);

            $table->unsignedBigInteger('student_tutor_id')->nullable();
            $table->foreign('student_tutor_id')->references('id')->on('student_tutors');

            $table->timestamps();
        });

        $customer = Customer::create([
            'full_name' => 'Clientes/Proveedores Varios',
            'first_name' => 'Clientes/Proveedores',
            'last_name' => 'Varios',
            'email' => 'ejemplo@example.com',
            'document_type' => '0',
            'document' => '99999999',
            'is_ative' => true,
            'is_tutor' => false,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
};
