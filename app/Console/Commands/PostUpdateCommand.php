<?php

namespace App\Console\Commands;

use App\Post;
use Illuminate\Console\Command;

class PostUpdateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update_post:title';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '更新Post的title';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $time = time();
        Post::all()->each(function($item) use ($time){
            $item->title = $time;
            $item->save();
        });
         dd('It"s done!');
    }
}
