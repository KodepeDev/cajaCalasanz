<?php
namespace App\Services;

use App\Models\ExchangeRate;

class TipoCambioService {

    public function getValue($date)
    {
        $exchange = $this->getExchange($date);
        if ($exchange) {
            $tipoCambio = $exchange;
            // dd($tipoCambio);
        } else {
            $tipoCambio = 1.000;

            // Datos
            $token = 'apis-token-11336.JFa9qncuPkFJHi2YfAmqo1kDNJThblNr';
            $fecha = $date;

            // Iniciar llamada a API
            $curl = curl_init();

            curl_setopt_array($curl, array(
                // para usar la api versión 2
                CURLOPT_URL => 'https://api.apis.net.pe/v2/sunat/tipo-cambio?date=' . $fecha,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 2,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Referer: https://apis.net.pe/tipo-de-cambio-sunat-api',
                    'Authorization: Bearer ' . $token
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            // Datos listos para usar
            $tipoCambioSunat = json_decode($response);
            $tipoCambio = $tipoCambioSunat->precioVenta;

            $exchange = ExchangeRate::create([
                'date' => $date,
                'base_currency_id' => 1,
                'quote_currency_id' => 2,
                'rate' => $tipoCambio
            ]);
        }
        // dd($tipoCambio);
        return $tipoCambio;
    }

    public function getExchange($date)
    {
        $exchange = ExchangeRate::where('date', $date)->first()->rate ?? null;
        return $exchange;
    }
}
