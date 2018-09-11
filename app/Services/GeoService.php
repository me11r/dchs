<?php

namespace App\Services;


class GeoService
{

    public function getCoordinates($address)
    {
        try{
            $url = "https://geocode-maps.yandex.ru/1.x/?geocode=Алматы, $address";
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => $url,
                CURLOPT_USERAGENT => 'Codular Sample cURL Request',
                CURLOPT_POST => 0,
            ));
            $resp = simplexml_load_string(curl_exec($curl))[0] ?? null;
            $found = $resp->GeoObjectCollection->featureMember[0];
            if($found->GeoObject->metaDataProperty->GeocoderMetaData->precision == 'exact'){
                $coords = explode(' ', $found->GeoObject->Point->pos);
                $result = [
                    'lat' => $coords[1],
                    'long' => $coords[0],
                ];
            }
            curl_close($curl);

            return $result ?? null;
        }
        catch (\Exception $e){
            return null;
        }
    }
}