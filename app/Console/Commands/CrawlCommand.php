<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Goutte\Client;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use App\FullStory;

class CrawlCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawl:site';

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

                $this->crawl($link, $name);               
            }
        });
        
        $story = $this->story->sortBy('start_date');
        
        return view('story.full')
            ->with('story', $story);
    }
    
    protected function  crawl($link, $name)
    {   
        $forum = $this->client->click($link);
        

        $forum->filter('#subform_set .c_forum a')->each(function($node) use ($forum) {
            $subforum = $node->text();
            $slink = $forum->selectLink($subforum)->link();
            dump($slink);
            $this->crawl($slink, $subforum); 
        });
      
        
        try {
            $last_page = (int) $forum->filter('.cat-pages a')->last()->text();
        }
        catch(\Exception $e) {
            $last_page = 1;
        }
        
        $url = $link->getUri();
        
        for($i = 1; $i <= $last_page; $i++)
        {
            if($i == 1) {
                $open = $url;
            }
            else {
                $open = $url.$i;
            }
            
            $topic =  $this->client->request('GET', $open);
            
            $this->crawl_forum($topic, $name);
        }
        
        
        
    }
    
    protected function crawl_forum($forum, $name)
    {    
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
    }
    
    protected function crawl_topic($link)
    {
        $data = [];
        
        $topic = $this->client->click($link);
        
        $start_date = $topic->filter('.c_postinfo .left')->first()->text();
        $data['start_date'] = Carbon::parse($start_date);
        
        $this->chars = [];
        $this->end_date = null;
        
        try {
            $last_page = (int) $topic->filter('.cat-pages a')->last()->text();
        }
        catch(\Exception $e) {
            $last_page = 1;
        }
        
        $parts = explode('/',$topic->getUri());
        if(count($parts) > 6) {
            unset($parts[6]);
            $url = implode('/',$parts);
        }
        else {
            $url = $topic->getUri();       
        }
            
        for($i = 1; $i <= $last_page; $i++)
        {
            $open = $url.$i;
            
            $posts =  $this->client->request('GET', $open);
            
            $this->crawl_posts($posts);
        }

        $data['characters'] = implode(', ',array_unique($this->chars));
        $data['end_date'] = $this->end_date;
        
        return $data;
    }
    
    protected function crawl_posts($topic)
    {
        $url = $topic->getUri();
        
        $topic->filter('.c_username a')->each(function($node) {
            $char = $node->text();
            if(!empty($char)){
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
