<?php

namespace App\Services;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class ComprasParaguaiAPI
{
    public static $linkLojas = [
        'https://nissei.com/br/',
        'http://www.megaeletronicos.com/',
        'http://www.visaovip.com/',
        'http://www.cellshop.com/',
        'https://www.atacadoconnect.com/'
    ];

    public static function getPrecoMedioProduto($link)
    {
        $client = new Client([
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                'Referer' => 'https://www.comprasparaguai.com.br/',
                'Connection' => 'keep-alive'
            ]
        ]);

        $response = $client->request('GET', $link);
        $html = $response->getBody()->getContents();

        $crawler = new Crawler($html);
        $produtos = $crawler->filter('.promocao-produtos-item');

        $preco_produtos = 0;
        $qtd_produto = 0;
        foreach ($produtos as $produto) 
        {
            $produtoCrawler = new Crawler($produto);

            $element = $produtoCrawler->filter('.promocao-item-preco-oferta.promocao-item-border.flex.column');            
            if($element->count() > 0)
            {
                $linkLoja = $element->children()->eq(1)->attr('href');
                if (in_array($linkLoja, ComprasParaguaiAPI::$linkLojas)) {
                    $preco = floatval(str_replace(['US$', ' ', ','], ['', '', '.'], $produtoCrawler->filter('strong')->text()));
                    $preco_produtos += $preco;
                    $qtd_produto += 1;
                }
            }
            else
            {
                $element = $produtoCrawler->filter('.promocao-item-preco-oferta.flex.column.nao-eh-modelo');
                if ($element->count() > 0) 
                {
                    $linkLoja = $element->children()->eq(3)->children()->eq(0)->attr('href');
                    if (in_array($linkLoja, ComprasParaguaiAPI::$linkLojas)) {
                        $preco = floatval(str_replace(['U$', ' ', ','], ['', '', '.'], $produtoCrawler->filter('.preco-dolar')->text()));
                        $preco_produtos += $preco;
                        $qtd_produto += 1;
                    }
                }
            }
        }
        
        return $qtd_produto ? round($preco_produtos/$qtd_produto) : 0;
    }
}
