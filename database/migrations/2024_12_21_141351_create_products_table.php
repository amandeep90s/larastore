<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title', 2000);
            $table->string('slug', 2000);
            $table->longText('description');
            $table->foreignId('department_id')->index()->constrained('departments')->onDelete('cascade');
            $table->foreignId('category_id')->index()->constrained('categories')->onDelete('cascade');
            $table->decimal('price', 20, 4);
            $table->string('status')->index();
            $table->integer('quantity')->nullable();
            $table->foreignIdFor(User::class, 'created_by')->index();
            $table->foreignIdFor(User::class, 'updated_by')->nullable()->index();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
