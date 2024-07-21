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
        Schema::create('listings', function (Blueprint $table) {
            $table->id();
            $table->string("title");
            $table->foreignId("user_id")->constrained()->onDelete('cascade'); // if user got deleted for some reason (id from users table), all listings will be deleted as well (user_id from listings table)
            //$table->foreignId("user_id"); // we can also add foreignId first then we can constrain by the second line
            //$table->foreign("user_id")->references("id")->on("users")->onDelete("cascade"); // second line
            $table->string("tags");
            $table->string("company");
            $table->string("logo")->nullable();
            $table->string("location");
            $table->string("email");
            $table->string("website");
            $table->longText("desc");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('listings');
    }
};
