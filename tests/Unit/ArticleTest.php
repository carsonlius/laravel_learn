<?php

namespace Tests\Unit;

use App\Article;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ArticleTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function itFetchesTreadingArticles()
    {
        factory(Article::class, 3)->create();
        factory(Article::class)->create(['reads' => 10]);
        $most_popular= factory(Article::class)->create(['reads' => 20]);

        $articles = Article::trading()->get();
        $this->assertEquals($most_popular->id, $articles->first()->id);
        $this->assertCount(5, $articles);
    }    
}
