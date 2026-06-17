<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BaseController extends Controller
{
    protected $apiBaseUrl;

    public function __construct()
    {
        $this->apiBaseUrl = config('api.base_url');
    }

    /*
    |--------------------------------------------------------------------------
    | DEFAULT HEADERS
    |--------------------------------------------------------------------------
    */

    protected function getDefaultHeaders()
    {
        return [
            'Accept' => 'application/json',
            'X-Device-ID' => session('device_id')
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | GET
    |--------------------------------------------------------------------------
    */

    protected function apiGet(
        $endpoint,
        $headers = []
    ) {

        return Http::withToken(
                session('user_token')
            )
            ->withHeaders(
                array_merge(
                    $this->getDefaultHeaders(),
                    $headers
                )
            )
            ->timeout(15)
            ->get(
                $this->apiBaseUrl .
                $endpoint
            );
    }

    /*
    |--------------------------------------------------------------------------
    | POST
    |--------------------------------------------------------------------------
    */

    protected function apiPost(
        $endpoint,
        $data = [],
        $headers = []
    ) {

        Log::info('API POST DEBUG', [

            'endpoint' => $endpoint,

            'headers' => array_merge(
                $this->getDefaultHeaders(),
                $headers
            ),

            'payload' => $data
        ]);

        return Http::withToken(
                session('user_token')
            )
            ->withHeaders(
                array_merge(
                    $this->getDefaultHeaders(),
                    $headers
                )
            )
            ->timeout(15)
            ->post(
                $this->apiBaseUrl .
                $endpoint,

                $data
            );
    }

    /*
    |--------------------------------------------------------------------------
    | PUT
    |--------------------------------------------------------------------------
    */

    protected function apiPut(
        $endpoint,
        $data = [],
        $headers = []
    ) {

        return Http::withToken(
                session('user_token')
            )
            ->withHeaders(
                array_merge(
                    $this->getDefaultHeaders(),
                    $headers
                )
            )
            ->timeout(15)
            ->put(
                $this->apiBaseUrl .
                $endpoint,

                $data
            );
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    protected function apiDelete(
        $endpoint,
        $headers = []
    ) {

        return Http::withToken(
                session('user_token')
            )
            ->withHeaders(
                array_merge(
                    $this->getDefaultHeaders(),
                    $headers
                )
            )
            ->timeout(15)
            ->delete(
                $this->apiBaseUrl .
                $endpoint
            );
    }
}