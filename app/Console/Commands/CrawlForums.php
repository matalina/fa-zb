<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Goutte\Client;
use GuzzleHttp\Client as GuzzleClient;
use Carbon\Carbon;
use App\Forum;

class CrawlForums extends Command
{

    protected $signature = 'crawl:index';


    protected $description = 'Crawl Index Page of forum';

    protected $client;
    protected $forums = [];

    public function __construct()
    {
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
        $url = 'http://w11.zetaboards.com/TheFirstAge/index/';
        
        $crawler = $this->client->request('GET', $url);
                
        $crawler->filter('.category')->each(function ($node) {
            $ids = $node->extract('id');

            if($ids[0] != 'stats') {
                $temp = $node->filter('.cat_head h2')->first();
                $name = trim($temp->text());

                $category = Forum::create([
                    'name' => $name,
                    'parent' => 0,
                    'type' => 'category',
                    'description' => '',
                ]);
               
                $node->filter('.forums tr')->each(function ($forum)  use ($category) {
                    $data = [];
                    $forum->children()->each(function($cell) use (& $data) {
                        $data[] = trim($cell->text());
                    });
                    dump($data);
                    if(empty($data[0]) && count($data) > 1) {
                        $parts = explode(PHP_EOL,$data[1]);
                        $name = trim($parts[0]);
                        $description = trim($parts[1]);
                        
                        if(preg_match('/Hits:/',$data[2])) {
                            $type = 'link';
                        }
                        else {
                            $type = 'forum';
                        }
                        
                        $forum = Forum::create([
                            'name' => $name,
                            'parent' => $category->id,
                            'type' => $type,
                            'description' => $description,
                        ]);
                    }
                    
                    if(count($data) == 1) {
                        if(preg_match('/Subforums: (.*)/',$data[0], $matches)) {
                            $names = explode(',', $matches[1]);
                            foreach($names as $n) {
                                $forum = Forum::create([
                                    'name' => $n,
                                    'parent' => $category->id,
                                    'type' => 'forum',
                                    'description' => ''
                                ]);
                            }
                        }
                    }
                });
                
            }
        });
        
        dd(Forum::all());
    }
}
