<?php

namespace App\Console\Commands;

use Goutte\Client;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Console\Command;

class CrawlMemebers extends Command
{
    protected $signature = 'crawl:members';

    protected $description = 'Crawl members list';

    protected $client;

    public function __construct()
    {
        parent::__construct();
        parent::__construct();

        $user_agent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.113 Safari/537.36';

        $this->client = new Client();

        $guzzleClient = new GuzzleClient(array(
            'headers' => [
                'User-Agent' => $user_agent,
                'timeout' => '30',
            ],
        ));

        $this->client->setClient($guzzleClient);
    }

    public function handle()
    {
        $this->info('Started');
        $url = 'http://w11.zetaboards.com/TheFirstAge/index/';
        $crawler = $this->client->request('GET', $url);

        dd($crawler);

        $crawler->filter('#member_list_full tbody tr')->each(function ($node) {
            dd($node);
        });
        $this->info('Ended');
    }
}
