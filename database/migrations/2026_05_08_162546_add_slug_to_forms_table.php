<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Form;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up()
    {
        Schema::table('forms', function (Blueprint $col) {
            $col->string('slug')->nullable()->unique()->after('uuid');
        });

        // Generate slugs for existing forms
        $forms = Form::all();
        foreach ($forms as $form) {
            $baseSlug = Str::slug($form->title);
            $slug = $baseSlug;
            $count = 1;
            
            // Ensure uniqueness
            while (Form::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $count++;
            }
            
            $form->update(['slug' => $slug]);
        }
    }

    public function down()
    {
        Schema::table('forms', function (Blueprint $col) {
            $col->dropColumn('slug');
        });
    }
};
