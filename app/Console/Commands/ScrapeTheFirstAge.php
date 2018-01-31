<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Goutte\Client;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use App\FullStory;

class ScrapeTheFirstAge extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawl';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape the First Age Forums';
    
    protected $client;
    protected $ooc = [
        'The First Age Website',
        'The First Age Wiki',
        'Chat room',
        'About',
        'General Discussion',
        'Current Events',
        'Biographies & Backstory',
        'Past Lives',
    ];
    protected $story;
    protected $chars;
    
    public function __construct()
    {
        parent::__construct();
        $this->client = new Client();
        $this->story = new Collection();
        $this->chars = [];
        $this->end_date = null;
        
    }

    public function handle()
    {
        $crawler = $this->client->request('GET', 'http://w11.zetaboards.com/TheFirstAge/index/');
        $crawler->filter('.c_forum a')->each(function ($node) use ($crawler) {
            if(! in_array($node->text(), $this->ooc)) {
                $name = $node->text();

                $link = $crawler->selectLink($name)->link();

                $forum = $this->crawl_forum($link, $name);

                $forum->filter('.cat-pages a')->each(function($node) use ($name) {
                    $link = $node->link();
                    $this->crawl_forum($link, $name);
                }); 
                
                
            }
        });
        
        $story = $this->story->sortBy('start_date');
        
        return view('story.full')
            ->with('story', $story);
    }
    
    protected function  crawl_forum($link, $name)
    {
        $forum = $this->client->click($link);
         
        $forum->filter('.c_cat-title > a')->each(function ($node, $index)  use ($name) {
            $link = $node->link();
            $url = $node->link()->getUri();
            $title = $node->text();
            $parts = explode('/',$url);
            $zeta_id = $parts[5];
            
            $data = $this->crawl_topic($link);
            
            $data['forum'] = $name;
            $data['title'] = $title;
            $data['link'] = $url;
            $data['zeta_id'] = $zeta_id;
           
            if(! preg_match('/Description/', $title)) {
                FullStory::updateOrCreate(['zeta_id' => $zeta_id], $data);
            }
           
        });
        
        return $forum;
    }
    
    protected function crawl_topic($link)
    {
        $data = [
            'characters' => [],
        ];
        
        $topic = $this->client->click($link);
        
         
        $filter = $topic->filter('.c_postinfo .left');
        
        $start_date = $filter->first()->text();
        
        $data['start_date'] = Carbon::parse($start_date);
        
        $this->chars = [];
        $this->end_date = null;
        
        $this->crawl_posts($topic);
        
         $topic->filter('.cat-pages a')->each(function($node) {
            $link = $node->link();
            $topic = $this->client->click($link);
            $this->crawl_posts($topic);
        }); 
       

        $data['characters'] = implode(', ',array_unique($this->chars));
        $data['end_date'] = $this->end_date;
        
        return $data;
    }
    
    protected function crawl_posts($topic)
    {
        $topic->filter('.c_username a')->each(function($node) {
            $char = $node->text();
            if(!empty($char)) {
                $this->chars[] = $char;
            }
        });
        
        $topic->filter('.c_postinfo .left')->each(function($node) {
            $date = $node->text();
            if(!empty($date)) {
                $date = Carbon::parse($date);
               if($this->end_date == null) {
                   $this->end_date = $date;
               }
               else if($date->gt($this->end_date)) {
                   $this->end_date = $date;
               }                
            }
        });
    }
}
