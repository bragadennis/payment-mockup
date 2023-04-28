<?php

use App\Enums\Transactions\Reason;
use App\Enums\Transactions\Status;
use App\Enums\Users\Type;
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
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table->string('fullname')->comment('This will hold the fullname for the user in question.');
            $table->string('email')->unique()->index()->comment("This holds the User's informed e-mail address. This is a unique field.");
            $table->string('cp_number')->unique()->index()->comment("This will hold the user's registration number. This should be paired with the TYPE field to determine wether is a CUSTOMER or SELLER regsitration.");
            $table->enum('type', Type::getValues())->comment("Paired with the CP_NUMBER field tells the type for the users registration.");
            $table->string('password')->comment('Hashed user\'s password');

            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('wallets', function (Blueprint $table) {
            $table->id();

            $table->foreignId('holder_id')->constrained('users')->onDelete('cascade')->comment('References the owner for the wallet.');
            $table->float('balance')->comment('This informs how much is in a current wallet. Will be used to validate transactions.');

            $table->timestamps();
        });


        Schema::create('transactions', function(Blueprint $table) {
            $table->id();

            $table->foreignId('payer_id')->constrained('users')->comment('References the user who is making the payment.');
            $table->foreignId('payee_id')->constrained('users')->comment('Referentes the user who is receiving said payment.');
            $table->float('amount')->comment('Represents the value being transfered.');
            $table->enum('status', Status::getValues())->comment('Holds the status for the transaction at the given moment.');
            $table->enum('reason', Reason::getValues())->nullable()->comment('IN CASE the transaction is denied or not fullfilled, this will inform, by a subset of values, overall ocasions for why the transaction was denied.');
            $table->string('message')->comment('This will hold a more inteligible messagem destined to anyone whom may need to analyse the transaction.');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(['transactions', 'wallets', 'users']);
    }
};
