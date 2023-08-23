<?php

namespace App\Services;

use Exception;
use GuzzleHttp\Client;
use Mockery\CountValidator\Exact;

class PaymentService
{

    public function createSubaccount(array $data): mixed
    {
        $url = '/subaccount';

        try {
            //create paystack subaccount
            $accountData = [
                'business_name' => $data['name'],
                'bank_code' => $data['bank_code'],
                'account_number' => $data['account_number'],
                'percentage_charge' => 20
            ];

            $result = $this->hitPaystack('POST', $url, $accountData);

            if (!is_array($result)) {
                throw new Exception($result->getMessage(), 1);
            }
            $data = array_merge($data, ['subaccount_code' => $result['subaccount_code']]);
            return $data;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function initializeTransaction(array $data): mixed
    {
        $url = '/transaction/initialize';
        $response = $this->hitPaystack('POST', $url, $data);
        return $response['authorization_url'];
    }

    public function verifyTransaction(string $ref): mixed
    {

        return $this->hitPaystack('GET', '/transaction/verify/' . $ref);
    }

    private function hitPaystack(string $method, string $url, mixed $data = ''): mixed
    {
        $client = new Client();

        $options = [
            'headers' => [
                'Authorization' => 'Bearer ' . config('payment.paystack_secret'),
                'Content-Type' => 'application/json'
            ],
            'json' => $data
        ];

        $url = config('payment.paystack_url') . $url;

        try {
            //code...
            $response = $client->request($method, $url, $options);
            $result = $response->getBody()->getContents();
            $formatedData = json_decode($result, true);

            if ($formatedData['status'] == true) {
                return $formatedData['data'];
            }
            return false;
        } catch (\Throwable $th) {
            return $th;
        }
    }
}
