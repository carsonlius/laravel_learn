<?php

namespace Tests\Feature;

use App\User;
use Couchbase\Exception;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Team;

class TeamTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * @test
     */
    public function aTeamHasAName()
    {
        $name = 'CodeCast';
        $team = new Team(compact('name'));
        $this->assertEquals($name, $team->name);
    }

    /**
     * @test
     */
    public function aTeamCanAddMember()
    {
        $team = factory(Team::class)->create();
        $user = factory(User::class)->create();
        $user_two = factory(User::class)->create();
        $team->add($user);
        $team->add($user_two);
        $this->assertCount(2, $team->count());
    }

    /**
     * 一个团队中的成员数量是有限制的
     * @test
     */
    public function aTeamHasAMaxSize()
    {
        $team = factory(Team::class)->create(['size' => 2]);
        $user = factory(User::class)->create();
        $user_two = factory(User::class)->create();
        $team->add($user);
        $team->add($user_two);
        $this->assertCount(2, $team->count());
        $this->expectException(\Exception::class);
        $user_three = factory(User::class)->create();
        $team->add($user_three);
    }

    /**
     * @test
     */
    public function aTeamCanRemoveAMember()
    {
        $team = factory(Team::class)->create(['size' => 4]);
        $user = factory(User::class)->create();
        $user_two = factory(User::class)->create();
        $team->add($user);
        $team->add($user_two);

        $team->remove($user);
        $this->assertCount(1, $team->count());
    }

    /**
     * @test
     */
    public function aTeamCanRemoveManyMemberAtOnce()
    {
        $team = factory(Team::class)->create(['size' => 4]);
        $user = factory(User::class, 3)->create();
        $team->add($user);
        $team->remove($user->take(2));
        $this->assertCount(1, $team->count());
    }

    /**
     * @test
     */
    public function aTeamCanRemoveAllMemberAtOnce()
    {
        $team = factory(Team::class)->create(['size' => 4]);
        $user = factory(User::class, 3)->create();
        $team->add($user);
        $team->removeAll();
        $this->assertCount(0, $team->count());
    }
}
