<?php

namespace App\Http\Controllers;

use App\Services\TranslateService;
use Barryvdh\TranslationManager\Manager as Manager;
use Barryvdh\TranslationManager\Models\Translation;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class TranslateController extends Controller
{
    /** @var \Barryvdh\TranslationManager\Manager  */
    protected $manager;

    /** @var TranslateService  */
    protected $translateService;

    public function __construct(Manager $manager, TranslateService $translateService)
    {
        parent::__construct();
        $this->manager = $manager;
        $this->translateService = $translateService;
    }

    public function getIndex($group = null)
    {
//        $locales = $this->manager->getLocales();
        $locales = [
            'kk',
            'ru',
        ];
        $groups = Translation::groupBy('group');
        $excludedGroups = $this->manager->getConfig('exclude_groups');
        if($excludedGroups){
            $groups->whereNotIn('group', $excludedGroups);
        }

        $groups = $groups
            ->select('group')
            ->orderBy('group')
            ->get()
            ->pluck('group', 'group');

        if ($groups instanceof Collection) {
            $groups = $groups->all();
        }
        $groups = [''=>'Выберите группу'] + $groups->toArray();
        $numChanged = Translation::where('group', $group)->where('status', Translation::STATUS_CHANGED)->get();

        $groupNames = [
            'alert_system_checks' => 'Тех.проверка системы оповещения',
            'auth' => 'Авторизация',
            'call_infos' => 'Информация по звонкам',
            'card101' => 'Карточки 101',
            'card101_norms_psp' => 'Карточки 101: Нормы ПСП',
            'card101_other' => 'Карточки 101: Прочие выезда',
            'card112' => 'Карточки 112',
            'chats' => 'События',
            'common' => 'Общее',
            'daily_report101' => 'Суточные отчеты 101',
            'daily_report112' => 'Суточные отчеты 112',
            'dictionaries' => 'Справочники',
            'formation_records' => 'Сроевые записки',
            'hydrants' => 'Гидранты',
            'import' => 'Импорт',
            'information' => 'Информация',
            'menu' => 'Меню',
            'messenger_permissions' => 'Разрешения мессенджера',
            'pagination' => 'Разбитие по страницам',
            'passwords' => 'Пароли',
            'polygons' => 'Полигоны районов',
            'queued_reports' => 'Очередь отчетов',
            'report_101_staff' => 'Отчет по строевой записке ЛС',
            'report_101_vehicles' => 'Отчет по строевой записке техники',
            'report_siren_speeches' => 'Данные по СРУ',
            'reports_analytics101_daily' => 'Отчеты 101',
            'reports_analytics112_daily' => 'Отчеты 112',
            'reports_spiasr' => 'Аналитика СПиАСР',
            'roadtrips' => 'Путевые листы',
            'salvage' => 'Сумма спасенного имущества',
            'site' => 'Сайт',
            'staff' => 'Список ЛС',
            'users' => 'Пользователи системы',
            'validation' => 'Языковые ресурсы для проверки значений',
            'vehicles' => 'Автотехника',
        ];

        foreach ($groups as $key => $g) {
            $groups[$key] = isset($groupNames[$key]) ? $groupNames[$key] : $g;
        }


        $allTranslations = Translation::where('group', $group)->orderBy('key', 'asc')->get();
        $numTranslations = count($allTranslations);
        $translations = [];
        foreach($allTranslations as $translation){
            $translations[$translation->key][$translation->locale] = $translation;
        }

        return view('vendor.translation-manager.index')
            ->with('translations_', $translations)
            ->with('locales', $locales)
            ->with('groups', $groups)
            ->with('group', $group)
            ->with('numTranslations', $numTranslations)
            ->with('numChanged', $numChanged)
            ->with('editUrl', action('\App\Http\Controllers\TranslateController@postEdit', [$group]))
            ->with('deleteEnabled', $this->manager->getConfig('delete_enabled'));
    }

    public function getView($group = null)
    {
        return $this->getIndex($group);
    }


    protected function loadLocales()
    {
        //Set the default locale as the first one.
        $locales = Translation::groupBy('locale')
            ->select('locale')
            ->get()
            ->pluck('locale');

        if ($locales instanceof Collection) {
            $locales = $locales->all();
        }
        $locales = array_merge([config('app.locale')], $locales);
        return array_unique($locales);
    }

    public function postAdd($group = null)
    {
        $keys = explode("\n", request()->get('keys'));

        foreach($keys as $key){
            $key = trim($key);
            if($group && $key){
                $this->manager->missingKey('*', $group, $key);
            }
        }
        return redirect()->back();
    }

    public function postEdit($group = null)
    {
        if(!in_array($group, $this->manager->getConfig('exclude_groups'))) {
            $name = request()->get('name');
            $value = request()->get('value');

            list($locale, $key) = explode('|', $name, 2);
            $translation = Translation::firstOrNew([
                'locale' => $locale,
                'group' => $group,
                'key' => $key,
            ]);
            $translation->value = (string) $value ?: null;
            $translation->status = Translation::STATUS_CHANGED;
            $translation->save();
            return array('status' => 'ok');
        }
    }

    public function postDelete($group = null, $key)
    {
        if(!in_array($group, $this->manager->getConfig('exclude_groups')) && $this->manager->getConfig('delete_enabled')) {
            Translation::where('group', $group)->where('key', $key)->delete();
            return ['status' => 'ok'];
        }
    }

    public function postImport(Request $request)
    {
        $replace = $request->get('replace', false);
        $counter = $this->manager->importTranslations($replace);

        return ['status' => 'ok', 'counter' => $counter];
    }

    public function postFind()
    {
        $numFound = $this->manager->findTranslations();

        return ['status' => 'ok', 'counter' => (int) $numFound];
    }

    public function postPublish($group = null)
    {
        $json = false;

        if($group === '_json'){
            $json = true;
        }

        $this->manager->exportTranslations($group, $json);
        $this->translateService->export();

        return ['status' => 'ok'];
    }

    public function postAddGroup(Request $request)
    {
        $group = str_replace(".", '', $request->input('new-group'));
        if ($group)
        {
            return redirect()->action('\App\Http\Controllers\TranslateController@getView',$group);
        }
        else
        {
            return redirect()->back();
        }
    }

    public function postAddLocale(Request $request)
    {
        $locales = $this->manager->getLocales();
        $newLocale = str_replace([], '-', trim($request->input('new-locale')));
        if (!$newLocale || in_array($newLocale, $locales)) {
            return redirect()->back();
        }
        $this->manager->addLocale($newLocale);
        return redirect()->back();
    }

    public function postRemoveLocale(Request $request)
    {
        foreach ($request->input('remove-locale', []) as $locale => $val) {
            $this->manager->removeLocale($locale);
        }
        return redirect()->back();
    }
}
