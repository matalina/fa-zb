<?php

namespace App\Console\Commands;

use Goutte\Client;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Console\Command;
use App\Member;
use Carbon\Carbon;

class CrawlMemebers extends Command
{
    protected $signature = 'crawl:members';

    protected $description = 'Crawl members list';

    protected $client;
    protected $memeber;
    protected $pages = true;

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
        $this->member = [];
    }

    public function handle()
    {
        $this->info('Started');
        $page = 1;
        $url = 'http://w11.zetaboards.com/TheFirstAge/members/';
        $pages = true;
        
        while($this->pages) {
            $crawler = $this->client->request('GET', $url.$page);
                
            $crawler->filter('#member_list_full tr')->each(function ($node) {
                $member = [];
                $node->children()->each(function($child) use (& $member) {
                    if($child->text() == 'No members') {
                        $this->pages = false;
                    }
                    else {
                        $member[] = $child->text();
                    }
                });
                $this->member[] = $member;

            });
            $page++;
        }
        foreach($this->member as $member) {
            if(count($member) > 1 && $member[0] != 'Member Name') {
                $date = Carbon::parse($member[3]);
                
                Member::create([
                    'username' => $member[0],
                    'join_date' => $date->toDateTimeString(), 
                ]);
            }
        }
        
        $this->info('Ended');
    }
}
