<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class ApiClientController extends Controller
{
    public function request($method, $url, $data = [], $options = [])
    {
        $options = $this->_checkDataAndOptions($data, $options);
        try {
            $client   = new Client();
            $response = $client->request($method, $url, $options);
            return response()->json([
                'status' => 'success',
                'data' => (string) $response->getBody() | $response->getBody()->getContents()
            ], 200);
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse();
                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage(),
                    'detail' => [
                        'status' => $response->getStatusCode(),
                        'content' => (string) $response->getBody() | $response->getBody()->getContents()  // Body, normally it is JSON;
                    ]
                ], 400);
            }
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    private function _checkDataAndOptions($data, $options)
    {
        if (array_key_exists('headers', $options)) {
            if (is_array($options['headers']) && array_key_exists('Content-Type', $options['headers'])) {
                switch ($options['headers']['Content-Type']) {
                    case 'multipart/form-data':
                        foreach ($data as $key => $value) {
                            $formData[] = array(
                                'name' => $key,
                                'contents' => $value
                            );
                        }
                        $options['multipart'] = $formData;
                        break;
                    case 'application/x-www-form-urlencoded':
                        $options['form_params'] = $data;
                        break;
                    case 'application/json':
                    default:
                        $options['json'] = $data;
                        break;
                }
            }
        } else {
            $options['headers']['Content-Type'] = 'application/json';
            $options['json'] = $data;
        }

        return $options;
    }
}
