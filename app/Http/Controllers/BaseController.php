<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class BaseController extends Controller
{
    protected $apiBaseUrl;

    public function __construct()
    {
        $this->apiBaseUrl = config('api.base_url');
    }

    protected function apiGet($endpoint)
    {
        return Http::withToken(session('user_token'))
            ->timeout(15)
            ->get($this->apiBaseUrl . $endpoint);
    }

    protected function apiPost($endpoint, $data = [])
    {
        return Http::withToken(session('user_token'))
            ->timeout(15)
            ->post($this->apiBaseUrl . $endpoint, $data);
    }

    protected function apiPut($endpoint, $data = [])
    {
        return Http::withToken(session('user_token'))
            ->timeout(15)
            ->put($this->apiBaseUrl . $endpoint, $data);
    }

    protected function apiDelete($endpoint)
    {
        return Http::withToken(session('user_token'))
            ->timeout(15)
            ->delete($this->apiBaseUrl . $endpoint);
    }
}