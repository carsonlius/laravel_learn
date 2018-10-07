<?php

namespace App\Http\Controllers;

use Illuminate\Support\Collection;

class GitHubScore extends Controller
{
    private $event;

    /**
     * GitHubScore constructor.
     * @param Collection $event
     */
    private function __construct(Collection $event)
    {
        $this->event = $event;
    }


    public static function score(Collection $events): int
    {
        return (new static($events))->scoreEvent();
    }

    private function scoreEvent(): int
    {
        return $this->event->pluck('type')->map(function ($type) {
            return $this->loopCollection()->get($type, 1);
        })->sum();
    }

    private function loopCollection(): Collection
    {
        return collect([
            'PushEvent' => 5,
            'CreateEvent' => 4,
            'IssueEvent' => 3,
            'IssueCommentEvent' => 2
        ]);
    }
}
