<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arrLanguages = [
            [
                'locale' => 'en',
                'name' => 'English'
            ],
            [
                'locale' => 'es',
                'name' => 'Spanish'
            ],
            [
                'locale' => 'zh',
                'name' => 'Chinese'
            ],
            [
                'locale' => 'de',
                'name' => 'German'
            ],
            [
                'locale' => 'fr',
                'name' => 'French'
            ],
            [
                'locale' => 'pt',
                'name' => 'Portuguese'
            ],
            [
                'locale' => 'it',
                'name' => 'Italian'
            ],
        ];
        foreach($arrLanguages as $arrLanguage)
        {
            Language::create([
                'locale' => $arrLanguage['locale'],
                'name' => $arrLanguage['name']
            ]);
        }
    }
}
