<?php


namespace App\Services\API;


use GuzzleHttp\Client;
use SimpleXMLElement;

class ReportService
{
    private $client;
    private $url;

    /**
     * ReportService constructor.
     * @param string $serviceType
     */
    public function __construct(string $serviceType = 'emergency')
    {
        $this->client = new Client();
        $this->url = config('reports-api.address') . "/{$serviceType}";
    }

    public function getData(string $url)
    {
        $results = [];
        $xmlData = $this->getXmlFromRequest($url);
        if (is_array($xmlData) && isset($xmlData['error'])) {
            return $xmlData;
        }
        $arrayFromJson = json_decode(json_encode($xmlData), true);

        if (!empty($arrayFromJson['EVENT'])) {
            foreach ($arrayFromJson['EVENT'] as $event) {
                $event = array_map(function ($item) {
                    if (!is_array($item)) {
                        $item = trim($item);
                    } else {
                        $item = array_map('trim', $item);
                        $item = (isset($item[0]) && $item[0] === '') ? '' : $item;
                    }
                    $item = empty($item) ? '' : $item;
                    return $item;
                }, $event);
                $event['STREETX'] = (is_array($event['STREETX']) && !$event['STREETX'][0]) ? '' : $event['STREETX'];
                $results[] = array_change_key_case($event,CASE_LOWER);
            }
        }
        return $results;
    }

    /**
     * @param array $data
     * $data = [
     *      'date_from' => (string)
     *      'date_to' => (string)
     * ]
     * @return array 
    */
    public function getPeriod($data): array
    {
        $action = 'get_period';
        $data['date_to'] = $data['date_to'] ?? null;
        $url = "{$this->url}/{$action}/{$data['date_from']}/{$data['date_to']}/";
        return $this->getData($url);
    }

    /**
     * @param array $data
     * $data = [
     *      'date_from' => (string)
     * ]
     * @return array 
     */
    public function getSince($data): array
    {
        $action = 'get_since';
        $url = "{$this->url}/{$action}/{$data['date_from']}";
        return $this->getData($url);
    }

    /**
     * @param array $data
     * $data = [
     *      'date_from' => (string)
     * ]
     * @return array 
     */
    public function getDay($data): array
    {
        $action = 'get_day';
        $url = "{$this->url}/{$action}/{$data['date_from']}";
        return $this->getData($url);
    }
    
    /**
     * @param array $data
     * $data = [
     *      'date_from' => (string)
     * ]
     * @return array 
     */
    public function getMonth($data): array
    {
        $action = 'get_month';
        $url = "{$this->url}/{$action}/{$data['date_from']}";
        return $this->getData($url);
    }

    public function makeRequest(string $url): ?string
    {
        $response = $this->client->request('GET', $url);
        return $response->getBody() ? $response->getBody()->getContents() : null;
    }

    /**
     * @param string $url
     * @return mixed
     */
    private function getXmlFromRequest(string $url)
    {
        $response = $this->makeRequest($url);
        if (!$response) {
            return null;
        }
        if (str_contains($response, ':ERROR:')) {
            return $this->handleError($response);
        }
        /*to make sure that data in [CDATA] blocks will not be empty*/
        $response = preg_replace_callback('/<!\[CDATA\[(.*)\]\]>/', function ($matches) {
            $converted = htmlspecialchars($matches[1]);
            $trimmed = trim($converted);
            return $trimmed;
        }, $response);

        return new SimpleXMLElement($response);
    }

    private function handleError(string $responseBody)
    {
        $errorDescription = '';

        if (str_contains($responseBody, 'name expected')) {
            $errorDescription = 'имя сервиса не указано';
        } elseif (str_contains($responseBody, 'not found')) {
            $errorDescription = 'сервис не обнаружен';
        } elseif (str_contains($responseBody, 'command expected')) {
            $errorDescription = 'команда не задана';
        } elseif (str_contains($responseBody, 'invalid command')) {
            $errorDescription = 'команда не распознана';
        } elseif (str_contains($responseBody, 'parameter expected')) {
            $errorDescription = 'дата (параметр) не указана';
        } elseif (str_contains($responseBody, 'invalid date. Expected in format')) {
            $errorDescription = 'ошибка распознавания даты';
        } elseif (str_contains($responseBody, 'date range invalid')) {
            $errorDescription = 'неправильно задан диапазон дат';
        } elseif (str_contains($responseBody, 'date range exceeded')) {
            $errorDescription = 'ограничение выдачи для всех команд: возвращаемые данные должны лежать в диапазоне одного месяца (31 день)';
        }
        return $error = [
            'error' => $responseBody,
            'description' => $errorDescription,
        ];
    }
}