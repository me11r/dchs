<?php

use Illuminate\Database\Seeder;
use Barryvdh\TranslationManager\Models\Translation;

class TranslationSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        app()->setLocale('ru');

        $groups = [
            'auth' => [
                'logInSystem' => 'Вход в систему',
                'email' => 'Электронная почта',
                'password' => 'Пароль',
                'remember' => 'Запомнить вход',
                'logIn' => 'Вход'
            ],
            'site' => [
                'main_report' => 'Сведения о чрезвычайных ситуациях природного и техногенного характера произошедших на территории  ДЧС города Алматы по форме ЧС-1',
            ]
        ];

        foreach ($groups as $group => $words) {
            foreach ($words as $key => $text) {
                $word = Translation::where('key', $key)->where('group', $group)->first();
                if (!$word) {
                    $phrase = new Translation();
                    $phrase->status = 0;
                    $phrase->group = $group;
                    $phrase->key = $key;
                    $phrase->locale = 'ru';
                    $phrase->value = $text;
                    $phrase->save();
                }
            }
        }
    }
}
