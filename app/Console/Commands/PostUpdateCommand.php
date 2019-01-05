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
    protected $signature = 'update-post:title 
                            {user}: The ID of the user';

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
//        $name = $this->secret('What is your name?');
        $userId = $this->argument('user');

//        $name2 = $this->anticipate('What is your name?', ['Taylor', 'Dayle']);
        $time = time();
        $list_title = ['Hello', 'World'];
        $title_suffix = $list_title[$time%2];

        $list_header = ['id', 'title'];
        $list_post = Post::all($list_header);

        $bar = $this->output->createProgressBar(count($list_post));
        foreach ($list_post as $post) {
            $post->title = $time . '_' . $title_suffix;
            $post->save();
            $bar->advance(1);
            usleep(1000);
        }
        $bar->finish();

    }
}
