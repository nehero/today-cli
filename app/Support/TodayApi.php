<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class TodayApi
{
  public function getInstance()
  {
    $headers = [
      'Accept' => 'application/json',
    ];
    $user = DB::table('users')->first();
    if ($user) {
      $headers['Authorization'] = "Bearer {$user->token}";
    }
    return Http::withHeaders($headers)->baseUrl(env('API_URL'));
  }

  public function authenticate($email, $password)
  {
    return $this->getInstance()->post("api-keys", [
        'email' => $email,
        'password' => $password,
    ])->json();
  }

  public function todaysItems()
  {
    return $this->getInstance()->get("items")->json();
  }
  
  public function addItemToTodayList($body)
  {
    return $this->getInstance()->post("items", [
      'body' => $body,
    ])->json();
  }
  
  public function completeItem($itemId)
  {
    return $this->getInstance()->post("items/{$itemId}/complete")->json();
  }
}