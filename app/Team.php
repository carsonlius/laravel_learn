<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = ['name', 'size'];

    /**
     * @param  $user
     * @throws \Exception
     */
    public function add($user)
    {
        // 检查team是否已经满员
        $this->validateTeamIsFull();

        $this->members()->attach($user);
    }

    public function remove($user)
    {
        $this->members()->detach($user);
    }

    /**
     * @throws \Exception
     */
    private function validateTeamIsFull()
    {
        if (($this->members()->count()) >= $this->size) {
            throw new \Exception('Exception');
        }
    }

    public function removeAll()
    {
        $this->members()->sync([]);
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'user_team');
    }

    public function count()
    {
        return $this->members;
    }
}
