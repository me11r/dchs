<?php

if (! function_exists('t')) {
    /**
     * Translate value.
     * Example of using: {{ t('/reports/analytics-spiasr.water_consumption.title', {'date_from': '123', 'date_to': '456'}) }}
     *
     * @param  string  $key
     * @param  array  $replace
     * @param  string  $locale
     * @return string
     */
    function t(string $key, array $replace = [], string $locale = null)
    {
        $locale = $locale ? $locale : app()->getLocale();

        $oppositeLocale = $locale === 'ru' ? 'kk' : 'ru';

        $data = \App\Translation::getByKey($key)
            ->getByLocale($locale)
            ->first();


        if ($data) {

            if (count($replace)) {

                foreach ($replace as $replace_key => $replace_item) {
                    $data->value = str_replace(":{$replace_key}", $replace_item, $data->value);
                }
            }

            return $data->value;
        }

        return $key;
    }
}
