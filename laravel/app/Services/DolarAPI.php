<?php

namespace App\Services;

use App\Models\Configs;
use App\Models\CotacaoDolar;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;

class DolarAPI
{
    public static function getCotacaoDolarMegaEletronicos()
    {
        $client = new Client([
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                'Referer' => 'https://megaeletronicos.com/',
                'Connection' => 'keep-alive'
            ]
        ]);

        $response = $client->request('GET', 'https://megaeletronicos.com/');
        $html = $response->getBody()->getContents();

        $crawler = new Crawler($html);
        $cotizaciones = $crawler->filter('.d-flex.flex-row.align-items-center.cotizacion');
        $dolar = $cotizaciones->children()->eq(3)->text();
        return (float)$dolar;
    }

    public static function getCotacaoDolarComprasParaguai()
    {
        $client = new Client([
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                'Referer' => 'https://www.comprasparaguai.com.br/',
                'Connection' => 'keep-alive'
            ]
        ]);

        $response = $client->request('GET', 'https://www.comprasparaguai.com.br/');
        $html = $response->getBody()->getContents();

        $crawler = new Crawler($html);
        $cotizaciones = $crawler->filter('.esp-cotation');
        $dolar = $cotizaciones->children()->eq(0)->children()->eq(0)->text();
        return floatval(str_replace([',', 'R$'], ['.', ''], $dolar));
    }

    public static function getCotacaoDolarComercial($withPercent = 0)
    {
        if($withPercent)
            return round(Http::get('https://economia.awesomeapi.com.br/json/last/USD-BRL')->json()['USDBRL']['ask'] * (1+($withPercent/100)), 2 );
        else
            return Http::get('https://economia.awesomeapi.com.br/json/last/USD-BRL')->json()['USDBRL']['ask'];
    }
}
