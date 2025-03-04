<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiConsultasController extends Controller
{
    //
    public function apiDni($dni=null){


        $token = 'apis-token-1.aTSI1U7KEuT-6bbbCguH-4Y8TI6KS73N';
        $dni = $dni;

        // Iniciar llamada a API
        $curl = curl_init();

        // Buscar dni
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.apis.net.pe/v1/dni?numero=' . $dni,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 2,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Referer: https://apis.net.pe/consulta-dni-api',
            'Authorization: Bearer ' . $token
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        // Datos listos para usar
        $persona = json_decode($response, true);
        return $persona;
    }
    public function apiRuc($ruc=null){
        // Datos
        $token = 'apis-token-1.aTSI1U7KEuT-6bbbCguH-4Y8TI6KS73N';
        $ruc = $ruc;

        // Iniciar llamada a API
        $curl = curl_init();

        // Buscar ruc sunat
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.apis.net.pe/v1/ruc?numero=' . $ruc,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Referer: http://apis.net.pe/api-ruc',
            'Authorization: Bearer ' . $token
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        // Datos de empresas según padron reducido
        $empresa = json_decode($response, true);
        return $empresa;
    }
}
