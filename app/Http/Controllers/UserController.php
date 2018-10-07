<?php

namespace App\Http\Controllers;

use App\Jobs\SendReminderEmail;
use App\User;
use Dingo\Api\Routing\Helpers;

class UserController extends Controller
{
    use Helpers;
    public function store()
    {
        $where = [
            ['id', '>', 30]
        ];
        User::where($where)->get()->each(function ($user) {
            SendReminderEmail::dispatch($user);
            SendReminderEmail::dispatch($user)->onConnection('beanstalkd')->onQueue('send_remind_email');
        });

        $msg = 'done';
        return response()->json(compact('msg'));
    }

    public function test()
    {
        $point=6521;
        $baseData = [
        1 => [0,2300],
        2 => [2301,5000],
        3 => [5001,13000],
        4 => [13001,23000],
        5 => [23001,50000]
    ];
        $list_container = [];
        collect($baseData)->each(function ($item, $key) use (&$list_container, $point){
            if ($point > reset($item) && $point<end($item)) {
                $list_container[$key] = $item;
                return false;
            }
        });

        dump($list_container);

        // collection
        $list_collection = collect($baseData)->filter(function ($item) use($point){
            return $point > reset($item) && $point<end($item);
        });

        // array
        $list_result = array_filter($baseData, function($item) use ($point){
            return $point > reset($item) && $point<end($item);
        });
        dump($list_result);
    }


    public function lesson7()
    {
        $orders = [
            [
                'date' => '2015',
                'supplier' => 'ACME',
                'item_code' => 'Iphone5',
                'item_unit_price' => 1,
                'qty' => 1,
                'customer_name' => 'Andy'
            ],
            [
                'date' => '2016',
                'supplier' => 'ACBE',
                'item_code' => 'Iphone6',
                'item_unit_price' => 1,
                'qty' => 2,
                'customer_name' => 'Andy'
            ],
            [
                'date' => '2016',
                'supplier' => 'ACCE',
                'item_code' => 'Iphone7',
                'item_unit_price' => 1,
                'qty' => 3,
                'customer_name' => 'Andy'
            ],
            [
                'date' => '2015',
                'supplier' => 'ACCE',
                'item_code' => 'Iphone6',
                'item_unit_price' => 1,
                'qty' => 4,
                'customer_name' => 'Mandy'
            ],
            [
                'date' => '2016',
                'supplier' => 'ACCE',
                'item_code' => 'Iphone6',
                'item_unit_price' => 1,
                'qty' => 5,
                'customer_name' => 'carsonlius'
            ],
        ];

        $orders = collect($orders);

        dump($orders->groupBy('date'));

        // reduce
        $result = $orders->groupBy('date')->reduce(function($carry, $item){
                $date = $item->pluck('date')->first();
                $carry[$date] = $item->pluck('item_unit_price')->sum();
                return $carry;
            }, []);

        // map
        $result = $orders->groupBy('date')->map(function($item, $date){
           return $item->pluck('item_unit_price')->sum();
        });

        dump($result);
        $result = $orders->groupBy('date')->map(function($item, $date){
            return $item->groupBy('supplier')->map(function ($item_sub){
                return $item_sub->sum(function ($one_last){
                    return $one_last['qty'] * $one_last['item_unit_price'];
                });
            });
        });
        dump($result->toArray());

    }

    public function lesson6()
    {
        $employees = [
            [
                'name' => 'carsonlius',
                'email' => 'carsonlius@163.com',
                'company' => 'None'
            ],
            [
                'name' => 'JellyBool',
                'email' => 'jelly@163.com',
                'company' => 'None2'
            ],
            [
                'name' => 'Taylor',
                'email' => 'taylor@163.com',
                'company' => 'None3'
            ],
        ];

        dump(collect($employees)->pluck('email', 'name'));
        dump(collect($employees)->reduce(function($carry, $item){
            $carry[$item['name']] = $item['email'];
            return $carry;
        }));

        dump(collect($employees)->reduce(function($carry, $item){
            $carry[$item['name']] = $item['email'];
            return $carry;
        }), []);
        dump(collect($employees)->pluck('name')->combine(collect($employees)->pluck('email')->toArray()));

    }

    public function lesson5()
    {
        $year_last = [
            111.11,
            222.22,
            333.33,
            444.44,
            555.55,
            666.66,
            777.77,
            888.88,
            999.99,
        ];

        $year_this = [
            11.1,
            2222.2,
            33.3,
            44.4,
            55.5,
            66.6,
            77.7,
            88.8,
            99.9
        ];


//        array_walk($year_this, function ($money, $month) use(&$profit, $year_last){
//            $diff = $money-$year_last[$month];
//            array_push($profit, $diff);
//        });

        $profit = collect($year_this)->zip($year_last)->map(function ($item) {
            return $item->first() - $item->last();
        });

        dd($profit);
    }

    public function lesson4()
    {
        $messages = $this->giveMessage();
        dd(collect($messages)->map(function ($item) {
            return "- $item";
        })->implode(PHP_EOL));
    }

    protected function giveMessage(): array
    {
        return [
            '龙应台在香港大学演讲，问观众启蒙歌是哪一首。',
            '一位观众回答上大学时好多师兄带我唱的《我的祖国》。',
            '龙应台问真的？我的祖国怎么唱，头一句是什么。',
            '结果全体观众都开口唱了，龙应台沉默又不失礼貌的微笑。',
            ' 每次一听到这首歌，我都会泪目的。',
        ];
    }

    public function lesson3()
    {
        $score = GitHubScore::score(collect($this->event()));
        dump('score is ' . $score);
    }

    public function event(): array
    {
        $event_json = '
        [
    {
        "id": "8307071577",
        "type": "PushEvent",
        "actor": {
            "id": 29518211,
            "login": "carsonlius",
            "display_login": "carsonlius",
            "gravatar_id": "",
            "url": "https://api.github.com/users/carsonlius",
            "avatar_url": "https://avatars.githubusercontent.com/u/29518211?"
        },
        "repo": {
            "id": 125470950,
            "name": "carsonlius/zhihu_try",
            "url": "https://api.github.com/repos/carsonlius/zhihu_try"
        },
        "payload": {
            "push_id": 2899293585,
            "size": 1,
            "distinct_size": 1,
            "ref": "refs/heads/master",
            "head": "71e0d09016f2235bafbae6fd3658b9f999486b85",
            "before": "be54879832cbea6191f7aee880be9a328234a5bb",
            "commits": [
                {
                    "sha": "71e0d09016f2235bafbae6fd3658b9f999486b85",
                    "author": {
                        "email": "carsonlius@163.com",
                        "name": "carsonlius"
                    },
                    "message": "modify: 新建用户绑定到游客角色",
                    "distinct": true,
                    "url": "https://api.github.com/repos/carsonlius/zhihu_try/commits/71e0d09016f2235bafbae6fd3658b9f999486b85"
                }
            ]
        },
        "public": true,
        "created_at": "2018-09-23T03:37:14Z"
    },
    {
        "id": "8307032994",
        "type": "WatchEvent",
        "actor": {
            "id": 29518211,
            "login": "carsonlius",
            "display_login": "carsonlius",
            "gravatar_id": "",
            "url": "https://api.github.com/users/carsonlius",
            "avatar_url": "https://avatars.githubusercontent.com/u/29518211?"
        },
        "repo": {
            "id": 50096829,
            "name": "pandeydip/dump-die",
            "url": "https://api.github.com/repos/pandeydip/dump-die"
        },
        "payload": {
            "action": "started"
        },
        "public": true,
        "created_at": "2018-09-23T02:57:46Z"
    },
    {
        "id": "8295070985",
        "type": "PushEvent",
        "actor": {
            "id": 29518211,
            "login": "carsonlius",
            "display_login": "carsonlius",
            "gravatar_id": "",
            "url": "https://api.github.com/users/carsonlius",
            "avatar_url": "https://avatars.githubusercontent.com/u/29518211?"
        },
        "repo": {
            "id": 125470950,
            "name": "carsonlius/zhihu_try",
            "url": "https://api.github.com/repos/carsonlius/zhihu_try"
        },
        "payload": {
            "push_id": 2892873261,
            "size": 1,
            "distinct_size": 1,
            "ref": "refs/heads/master",
            "head": "be54879832cbea6191f7aee880be9a328234a5bb",
            "before": "7aa338adb0eff26eeffed8703e80c05642e45fc6",
            "commits": [
                {
                    "sha": "be54879832cbea6191f7aee880be9a328234a5bb",
                    "author": {
                        "email": "carsonlius@163.com",
                        "name": "carsonlius"
                    },
                    "message": "modify: 调整第三方登陆区域的背景",
                    "distinct": true,
                    "url": "https://api.github.com/repos/carsonlius/zhihu_try/commits/be54879832cbea6191f7aee880be9a328234a5bb"
                }
            ]
        },
        "public": true,
        "created_at": "2018-09-20T11:52:55Z"
    },
    {
        "id": "8294970267",
        "type": "PushEvent",
        "actor": {
            "id": 29518211,
            "login": "carsonlius",
            "display_login": "carsonlius",
            "gravatar_id": "",
            "url": "https://api.github.com/users/carsonlius",
            "avatar_url": "https://avatars.githubusercontent.com/u/29518211?"
        },
        "repo": {
            "id": 125470950,
            "name": "carsonlius/zhihu_try",
            "url": "https://api.github.com/repos/carsonlius/zhihu_try"
        },
        "payload": {
            "push_id": 2892821226,
            "size": 2,
            "distinct_size": 2,
            "ref": "refs/heads/master",
            "head": "7aa338adb0eff26eeffed8703e80c05642e45fc6",
            "before": "b080de52666ba35e849bb574013570812c551ea1",
            "commits": [
                {
                    "sha": "91c0d5ec2b3fb0d36d2bfc0fccb5af1c1981485e",
                    "author": {
                        "email": "carsonlius@163.com",
                        "name": "carsonlius"
                    },
                    "message": "modify: 第三方github 登陆",
                    "distinct": true,
                    "url": "https://api.github.com/repos/carsonlius/zhihu_try/commits/91c0d5ec2b3fb0d36d2bfc0fccb5af1c1981485e"
                },
                {
                    "sha": "7aa338adb0eff26eeffed8703e80c05642e45fc6",
                    "author": {
                        "email": "carsonlius@163.com",
                        "name": "carsonlius"
                    },
                    "message": "modify: fixed",
                    "distinct": true,
                    "url": "https://api.github.com/repos/carsonlius/zhihu_try/commits/7aa338adb0eff26eeffed8703e80c05642e45fc6"
                }
            ]
        },
        "public": true,
        "created_at": "2018-09-20T11:32:45Z"
    },
    {
        "id": "8293736883",
        "type": "WatchEvent",
        "actor": {
            "id": 29518211,
            "login": "carsonlius",
            "display_login": "carsonlius",
            "gravatar_id": "",
            "url": "https://api.github.com/users/carsonlius",
            "avatar_url": "https://avatars.githubusercontent.com/u/29518211?"
        },
        "repo": {
            "id": 25138401,
            "name": "overtrue/share.js",
            "url": "https://api.github.com/repos/overtrue/share.js"
        },
        "payload": {
            "action": "started"
        },
        "public": true,
        "created_at": "2018-09-20T07:45:56Z"
    },
    {
        "id": "8286659236",
        "type": "WatchEvent",
        "actor": {
            "id": 29518211,
            "login": "carsonlius",
            "display_login": "carsonlius",
            "gravatar_id": "",
            "url": "https://api.github.com/users/carsonlius",
            "avatar_url": "https://avatars.githubusercontent.com/u/29518211?"
        },
        "repo": {
            "id": 47725432,
            "name": "overtrue/laravel-socialite",
            "url": "https://api.github.com/repos/overtrue/laravel-socialite"
        },
        "payload": {
            "action": "started"
        },
        "public": true,
        "created_at": "2018-09-19T06:07:04Z"
    },
    {
        "id": "8286636112",
        "type": "WatchEvent",
        "actor": {
            "id": 29518211,
            "login": "carsonlius",
            "display_login": "carsonlius",
            "gravatar_id": "",
            "url": "https://api.github.com/users/carsonlius",
            "avatar_url": "https://avatars.githubusercontent.com/u/29518211?"
        },
        "repo": {
            "id": 47127359,
            "name": "overtrue/socialite",
            "url": "https://api.github.com/repos/overtrue/socialite"
        },
        "payload": {
            "action": "started"
        },
        "public": true,
        "created_at": "2018-09-19T06:00:08Z"
    },
    {
        "id": "8271556082",
        "type": "WatchEvent",
        "actor": {
            "id": 29518211,
            "login": "carsonlius",
            "display_login": "carsonlius",
            "gravatar_id": "",
            "url": "https://api.github.com/users/carsonlius",
            "avatar_url": "https://avatars.githubusercontent.com/u/29518211?"
        },
        "repo": {
            "id": 5366735,
            "name": "ptrofimov/beanstalk_console",
            "url": "https://api.github.com/repos/ptrofimov/beanstalk_console"
        },
        "payload": {
            "action": "started"
        },
        "public": true,
        "created_at": "2018-09-16T10:32:18Z"
    },
    {
        "id": "8252972434",
        "type": "PushEvent",
        "actor": {
            "id": 29518211,
            "login": "carsonlius",
            "display_login": "carsonlius",
            "gravatar_id": "",
            "url": "https://api.github.com/users/carsonlius",
            "avatar_url": "https://avatars.githubusercontent.com/u/29518211?"
        },
        "repo": {
            "id": 125470950,
            "name": "carsonlius/zhihu_try",
            "url": "https://api.github.com/repos/carsonlius/zhihu_try"
        },
        "payload": {
            "push_id": 2870637497,
            "size": 1,
            "distinct_size": 1,
            "ref": "refs/heads/master",
            "head": "b080de52666ba35e849bb574013570812c551ea1",
            "before": "b319f32b936d23b8871c6305bc21bf4bd37d53a8",
            "commits": [
                {
                    "sha": "b080de52666ba35e849bb574013570812c551ea1",
                    "author": {
                        "email": "carsonlius@163.com",
                        "name": "pengpeng"
                    },
                    "message": "Delete nginx_access.log\n\nnot useful",
                    "distinct": true,
                    "url": "https://api.github.com/repos/carsonlius/zhihu_try/commits/b080de52666ba35e849bb574013570812c551ea1"
                }
            ]
        },
        "public": true,
        "created_at": "2018-09-12T12:33:52Z"
    },
    {
        "id": "8252970898",
        "type": "PushEvent",
        "actor": {
            "id": 29518211,
            "login": "carsonlius",
            "display_login": "carsonlius",
            "gravatar_id": "",
            "url": "https://api.github.com/users/carsonlius",
            "avatar_url": "https://avatars.githubusercontent.com/u/29518211?"
        },
        "repo": {
            "id": 125470950,
            "name": "carsonlius/zhihu_try",
            "url": "https://api.github.com/repos/carsonlius/zhihu_try"
        },
        "payload": {
            "push_id": 2870636703,
            "size": 1,
            "distinct_size": 1,
            "ref": "refs/heads/master",
            "head": "b319f32b936d23b8871c6305bc21bf4bd37d53a8",
            "before": "cbde1f8371775eeec4086384a6f1d861f62c1ec8",
            "commits": [
                {
                    "sha": "b319f32b936d23b8871c6305bc21bf4bd37d53a8",
                    "author": {
                        "email": "carsonlius@163.com",
                        "name": "pengpeng"
                    },
                    "message": "Delete nginx_errror.log\n\nnot useful",
                    "distinct": true,
                    "url": "https://api.github.com/repos/carsonlius/zhihu_try/commits/b319f32b936d23b8871c6305bc21bf4bd37d53a8"
                }
            ]
        },
        "public": true,
        "created_at": "2018-09-12T12:33:35Z"
    },
    {
        "id": "8252874811",
        "type": "PushEvent",
        "actor": {
            "id": 29518211,
            "login": "carsonlius",
            "display_login": "carsonlius",
            "gravatar_id": "",
            "url": "https://api.github.com/users/carsonlius",
            "avatar_url": "https://avatars.githubusercontent.com/u/29518211?"
        },
        "repo": {
            "id": 125470950,
            "name": "carsonlius/zhihu_try",
            "url": "https://api.github.com/repos/carsonlius/zhihu_try"
        },
        "payload": {
            "push_id": 2870586407,
            "size": 1,
            "distinct_size": 1,
            "ref": "refs/heads/master",
            "head": "cbde1f8371775eeec4086384a6f1d861f62c1ec8",
            "before": "6b4032d0c6365a8306293c3727489eed463e8f76",
            "commits": [
                {
                    "sha": "cbde1f8371775eeec4086384a6f1d861f62c1ec8",
                    "author": {
                        "email": "carsonlius@163.com",
                        "name": "carsonlius"
                    },
                    "message": "modify: menu",
                    "distinct": true,
                    "url": "https://api.github.com/repos/carsonlius/zhihu_try/commits/cbde1f8371775eeec4086384a6f1d861f62c1ec8"
                }
            ]
        },
        "public": true,
        "created_at": "2018-09-12T12:15:58Z"
    },
    {
        "id": "8252769509",
        "type": "WatchEvent",
        "actor": {
            "id": 29518211,
            "login": "carsonlius",
            "display_login": "carsonlius",
            "gravatar_id": "",
            "url": "https://api.github.com/users/carsonlius",
            "avatar_url": "https://avatars.githubusercontent.com/u/29518211?"
        },
        "repo": {
            "id": 1863329,
            "name": "laravel/laravel",
            "url": "https://api.github.com/repos/laravel/laravel"
        },
        "payload": {
            "action": "started"
        },
        "public": true,
        "created_at": "2018-09-12T11:55:21Z",
        "org": {
            "id": 958072,
            "login": "laravel",
            "gravatar_id": "",
            "url": "https://api.github.com/orgs/laravel",
            "avatar_url": "https://avatars.githubusercontent.com/u/958072?"
        }
    },
    {
        "id": "8251653451",
        "type": "PullRequestEvent",
        "actor": {
            "id": 29518211,
            "login": "carsonlius",
            "display_login": "carsonlius",
            "gravatar_id": "",
            "url": "https://api.github.com/users/carsonlius",
            "avatar_url": "https://avatars.githubusercontent.com/u/29518211?"
        },
        "repo": {
            "id": 74971525,
            "name": "ultraware/roles",
            "url": "https://api.github.com/repos/ultraware/roles"
        },
        "payload": {
            "action": "opened",
            "number": 28,
            "pull_request": {
                "url": "https://api.github.com/repos/ultraware/roles/pulls/28",
                "id": 214876338,
                "node_id": "MDExOlB1bGxSZXF1ZXN0MjE0ODc2MzM4",
                "html_url": "https://github.com/ultraware/roles/pull/28",
                "diff_url": "https://github.com/ultraware/roles/pull/28.diff",
                "patch_url": "https://github.com/ultraware/roles/pull/28.patch",
                "issue_url": "https://api.github.com/repos/ultraware/roles/issues/28",
                "number": 28,
                "state": "open",
                "locked": false,
                "title": "modify: 1. add permission tree 2. can be used as menu",
                "user": {
                    "login": "carsonlius",
                    "id": 29518211,
                    "node_id": "MDQ6VXNlcjI5NTE4MjEx",
                    "avatar_url": "https://avatars0.githubusercontent.com/u/29518211?v=4",
                    "gravatar_id": "",
                    "url": "https://api.github.com/users/carsonlius",
                    "html_url": "https://github.com/carsonlius",
                    "followers_url": "https://api.github.com/users/carsonlius/followers",
                    "following_url": "https://api.github.com/users/carsonlius/following{/other_user}",
                    "gists_url": "https://api.github.com/users/carsonlius/gists{/gist_id}",
                    "starred_url": "https://api.github.com/users/carsonlius/starred{/owner}{/repo}",
                    "subscriptions_url": "https://api.github.com/users/carsonlius/subscriptions",
                    "organizations_url": "https://api.github.com/users/carsonlius/orgs",
                    "repos_url": "https://api.github.com/users/carsonlius/repos",
                    "events_url": "https://api.github.com/users/carsonlius/events{/privacy}",
                    "received_events_url": "https://api.github.com/users/carsonlius/received_events",
                    "type": "User",
                    "site_admin": false
                },
                "body": "permissions table add two field ,one is parent_id then wo can generate permissions tree;\r\nanother field is is_show  then we can control menu  ",
                "created_at": "2018-09-12T08:30:34Z",
                "updated_at": "2018-09-12T08:30:34Z",
                "closed_at": null,
                "merged_at": null,
                "merge_commit_sha": null,
                "assignee": null,
                "assignees": [],
                "requested_reviewers": [],
                "requested_teams": [],
                "labels": [],
                "milestone": null,
                "commits_url": "https://api.github.com/repos/ultraware/roles/pulls/28/commits",
                "review_comments_url": "https://api.github.com/repos/ultraware/roles/pulls/28/comments",
                "review_comment_url": "https://api.github.com/repos/ultraware/roles/pulls/comments{/number}",
                "comments_url": "https://api.github.com/repos/ultraware/roles/issues/28/comments",
                "statuses_url": "https://api.github.com/repos/ultraware/roles/statuses/d637b9bf83d2822385a83de1b5f869e254d33d70",
                "head": {
                    "label": "carsonlius:5.5",
                    "ref": "5.5",
                    "sha": "d637b9bf83d2822385a83de1b5f869e254d33d70",
                    "user": {
                        "login": "carsonlius",
                        "id": 29518211,
                        "node_id": "MDQ6VXNlcjI5NTE4MjEx",
                        "avatar_url": "https://avatars0.githubusercontent.com/u/29518211?v=4",
                        "gravatar_id": "",
                        "url": "https://api.github.com/users/carsonlius",
                        "html_url": "https://github.com/carsonlius",
                        "followers_url": "https://api.github.com/users/carsonlius/followers",
                        "following_url": "https://api.github.com/users/carsonlius/following{/other_user}",
                        "gists_url": "https://api.github.com/users/carsonlius/gists{/gist_id}",
                        "starred_url": "https://api.github.com/users/carsonlius/starred{/owner}{/repo}",
                        "subscriptions_url": "https://api.github.com/users/carsonlius/subscriptions",
                        "organizations_url": "https://api.github.com/users/carsonlius/orgs",
                        "repos_url": "https://api.github.com/users/carsonlius/repos",
                        "events_url": "https://api.github.com/users/carsonlius/events{/privacy}",
                        "received_events_url": "https://api.github.com/users/carsonlius/received_events",
                        "type": "User",
                        "site_admin": false
                    },
                    "repo": {
                        "id": 148442219,
                        "node_id": "MDEwOlJlcG9zaXRvcnkxNDg0NDIyMTk=",
                        "name": "roles",
                        "full_name": "carsonlius/roles",
                        "owner": {
                            "login": "carsonlius",
                            "id": 29518211,
                            "node_id": "MDQ6VXNlcjI5NTE4MjEx",
                            "avatar_url": "https://avatars0.githubusercontent.com/u/29518211?v=4",
                            "gravatar_id": "",
                            "url": "https://api.github.com/users/carsonlius",
                            "html_url": "https://github.com/carsonlius",
                            "followers_url": "https://api.github.com/users/carsonlius/followers",
                            "following_url": "https://api.github.com/users/carsonlius/following{/other_user}",
                            "gists_url": "https://api.github.com/users/carsonlius/gists{/gist_id}",
                            "starred_url": "https://api.github.com/users/carsonlius/starred{/owner}{/repo}",
                            "subscriptions_url": "https://api.github.com/users/carsonlius/subscriptions",
                            "organizations_url": "https://api.github.com/users/carsonlius/orgs",
                            "repos_url": "https://api.github.com/users/carsonlius/repos",
                            "events_url": "https://api.github.com/users/carsonlius/events{/privacy}",
                            "received_events_url": "https://api.github.com/users/carsonlius/received_events",
                            "type": "User",
                            "site_admin": false
                        },
                        "private": false,
                        "html_url": "https://github.com/carsonlius/roles",
                        "description": "Powerful package for handling roles and permissions in Laravel 5",
                        "fork": true,
                        "url": "https://api.github.com/repos/carsonlius/roles",
                        "forks_url": "https://api.github.com/repos/carsonlius/roles/forks",
                        "keys_url": "https://api.github.com/repos/carsonlius/roles/keys{/key_id}",
                        "collaborators_url": "https://api.github.com/repos/carsonlius/roles/collaborators{/collaborator}",
                        "teams_url": "https://api.github.com/repos/carsonlius/roles/teams",
                        "hooks_url": "https://api.github.com/repos/carsonlius/roles/hooks",
                        "issue_events_url": "https://api.github.com/repos/carsonlius/roles/issues/events{/number}",
                        "events_url": "https://api.github.com/repos/carsonlius/roles/events",
                        "assignees_url": "https://api.github.com/repos/carsonlius/roles/assignees{/user}",
                        "branches_url": "https://api.github.com/repos/carsonlius/roles/branches{/branch}",
                        "tags_url": "https://api.github.com/repos/carsonlius/roles/tags",
                        "blobs_url": "https://api.github.com/repos/carsonlius/roles/git/blobs{/sha}",
                        "git_tags_url": "https://api.github.com/repos/carsonlius/roles/git/tags{/sha}",
                        "git_refs_url": "https://api.github.com/repos/carsonlius/roles/git/refs{/sha}",
                        "trees_url": "https://api.github.com/repos/carsonlius/roles/git/trees{/sha}",
                        "statuses_url": "https://api.github.com/repos/carsonlius/roles/statuses/{sha}",
                        "languages_url": "https://api.github.com/repos/carsonlius/roles/languages",
                        "stargazers_url": "https://api.github.com/repos/carsonlius/roles/stargazers",
                        "contributors_url": "https://api.github.com/repos/carsonlius/roles/contributors",
                        "subscribers_url": "https://api.github.com/repos/carsonlius/roles/subscribers",
                        "subscription_url": "https://api.github.com/repos/carsonlius/roles/subscription",
                        "commits_url": "https://api.github.com/repos/carsonlius/roles/commits{/sha}",
                        "git_commits_url": "https://api.github.com/repos/carsonlius/roles/git/commits{/sha}",
                        "comments_url": "https://api.github.com/repos/carsonlius/roles/comments{/number}",
                        "issue_comment_url": "https://api.github.com/repos/carsonlius/roles/issues/comments{/number}",
                        "contents_url": "https://api.github.com/repos/carsonlius/roles/contents/{+path}",
                        "compare_url": "https://api.github.com/repos/carsonlius/roles/compare/{base}...{head}",
                        "merges_url": "https://api.github.com/repos/carsonlius/roles/merges",
                        "archive_url": "https://api.github.com/repos/carsonlius/roles/{archive_format}{/ref}",
                        "downloads_url": "https://api.github.com/repos/carsonlius/roles/downloads",
                        "issues_url": "https://api.github.com/repos/carsonlius/roles/issues{/number}",
                        "pulls_url": "https://api.github.com/repos/carsonlius/roles/pulls{/number}",
                        "milestones_url": "https://api.github.com/repos/carsonlius/roles/milestones{/number}",
                        "notifications_url": "https://api.github.com/repos/carsonlius/roles/notifications{?since,all,participating}",
                        "labels_url": "https://api.github.com/repos/carsonlius/roles/labels{/name}",
                        "releases_url": "https://api.github.com/repos/carsonlius/roles/releases{/id}",
                        "deployments_url": "https://api.github.com/repos/carsonlius/roles/deployments",
                        "created_at": "2018-09-12T07:48:11Z",
                        "updated_at": "2018-09-12T07:48:13Z",
                        "pushed_at": "2018-09-12T08:28:07Z",
                        "git_url": "git://github.com/carsonlius/roles.git",
                        "ssh_url": "git@github.com:carsonlius/roles.git",
                        "clone_url": "https://github.com/carsonlius/roles.git",
                        "svn_url": "https://github.com/carsonlius/roles",
                        "homepage": "",
                        "size": 183,
                        "stargazers_count": 0,
                        "watchers_count": 0,
                        "language": "PHP",
                        "has_issues": false,
                        "has_projects": true,
                        "has_downloads": true,
                        "has_wiki": true,
                        "has_pages": false,
                        "forks_count": 0,
                        "mirror_url": null,
                        "archived": false,
                        "open_issues_count": 0,
                        "license": {
                            "key": "mit",
                            "name": "MIT License",
                            "spdx_id": "MIT",
                            "url": "https://api.github.com/licenses/mit",
                            "node_id": "MDc6TGljZW5zZTEz"
                        },
                        "forks": 0,
                        "open_issues": 0,
                        "watchers": 0,
                        "default_branch": "5.7"
                    }
                },
                "base": {
                    "label": "ultraware:5.5",
                    "ref": "5.5",
                    "sha": "825786f507a116680b1fcad86cb0a22dabb67cfa",
                    "user": {
                        "login": "ultraware",
                        "id": 9347946,
                        "node_id": "MDEyOk9yZ2FuaXphdGlvbjkzNDc5NDY=",
                        "avatar_url": "https://avatars1.githubusercontent.com/u/9347946?v=4",
                        "gravatar_id": "",
                        "url": "https://api.github.com/users/ultraware",
                        "html_url": "https://github.com/ultraware",
                        "followers_url": "https://api.github.com/users/ultraware/followers",
                        "following_url": "https://api.github.com/users/ultraware/following{/other_user}",
                        "gists_url": "https://api.github.com/users/ultraware/gists{/gist_id}",
                        "starred_url": "https://api.github.com/users/ultraware/starred{/owner}{/repo}",
                        "subscriptions_url": "https://api.github.com/users/ultraware/subscriptions",
                        "organizations_url": "https://api.github.com/users/ultraware/orgs",
                        "repos_url": "https://api.github.com/users/ultraware/repos",
                        "events_url": "https://api.github.com/users/ultraware/events{/privacy}",
                        "received_events_url": "https://api.github.com/users/ultraware/received_events",
                        "type": "Organization",
                        "site_admin": false
                    },
                    "repo": {
                        "id": 74971525,
                        "node_id": "MDEwOlJlcG9zaXRvcnk3NDk3MTUyNQ==",
                        "name": "roles",
                        "full_name": "ultraware/roles",
                        "owner": {
                            "login": "ultraware",
                            "id": 9347946,
                            "node_id": "MDEyOk9yZ2FuaXphdGlvbjkzNDc5NDY=",
                            "avatar_url": "https://avatars1.githubusercontent.com/u/9347946?v=4",
                            "gravatar_id": "",
                            "url": "https://api.github.com/users/ultraware",
                            "html_url": "https://github.com/ultraware",
                            "followers_url": "https://api.github.com/users/ultraware/followers",
                            "following_url": "https://api.github.com/users/ultraware/following{/other_user}",
                            "gists_url": "https://api.github.com/users/ultraware/gists{/gist_id}",
                            "starred_url": "https://api.github.com/users/ultraware/starred{/owner}{/repo}",
                            "subscriptions_url": "https://api.github.com/users/ultraware/subscriptions",
                            "organizations_url": "https://api.github.com/users/ultraware/orgs",
                            "repos_url": "https://api.github.com/users/ultraware/repos",
                            "events_url": "https://api.github.com/users/ultraware/events{/privacy}",
                            "received_events_url": "https://api.github.com/users/ultraware/received_events",
                            "type": "Organization",
                            "site_admin": false
                        },
                        "private": false,
                        "html_url": "https://github.com/ultraware/roles",
                        "description": "Powerful package for handling roles and permissions in Laravel 5",
                        "fork": true,
                        "url": "https://api.github.com/repos/ultraware/roles",
                        "forks_url": "https://api.github.com/repos/ultraware/roles/forks",
                        "keys_url": "https://api.github.com/repos/ultraware/roles/keys{/key_id}",
                        "collaborators_url": "https://api.github.com/repos/ultraware/roles/collaborators{/collaborator}",
                        "teams_url": "https://api.github.com/repos/ultraware/roles/teams",
                        "hooks_url": "https://api.github.com/repos/ultraware/roles/hooks",
                        "issue_events_url": "https://api.github.com/repos/ultraware/roles/issues/events{/number}",
                        "events_url": "https://api.github.com/repos/ultraware/roles/events",
                        "assignees_url": "https://api.github.com/repos/ultraware/roles/assignees{/user}",
                        "branches_url": "https://api.github.com/repos/ultraware/roles/branches{/branch}",
                        "tags_url": "https://api.github.com/repos/ultraware/roles/tags",
                        "blobs_url": "https://api.github.com/repos/ultraware/roles/git/blobs{/sha}",
                        "git_tags_url": "https://api.github.com/repos/ultraware/roles/git/tags{/sha}",
                        "git_refs_url": "https://api.github.com/repos/ultraware/roles/git/refs{/sha}",
                        "trees_url": "https://api.github.com/repos/ultraware/roles/git/trees{/sha}",
                        "statuses_url": "https://api.github.com/repos/ultraware/roles/statuses/{sha}",
                        "languages_url": "https://api.github.com/repos/ultraware/roles/languages",
                        "stargazers_url": "https://api.github.com/repos/ultraware/roles/stargazers",
                        "contributors_url": "https://api.github.com/repos/ultraware/roles/contributors",
                        "subscribers_url": "https://api.github.com/repos/ultraware/roles/subscribers",
                        "subscription_url": "https://api.github.com/repos/ultraware/roles/subscription",
                        "commits_url": "https://api.github.com/repos/ultraware/roles/commits{/sha}",
                        "git_commits_url": "https://api.github.com/repos/ultraware/roles/git/commits{/sha}",
                        "comments_url": "https://api.github.com/repos/ultraware/roles/comments{/number}",
                        "issue_comment_url": "https://api.github.com/repos/ultraware/roles/issues/comments{/number}",
                        "contents_url": "https://api.github.com/repos/ultraware/roles/contents/{+path}",
                        "compare_url": "https://api.github.com/repos/ultraware/roles/compare/{base}...{head}",
                        "merges_url": "https://api.github.com/repos/ultraware/roles/merges",
                        "archive_url": "https://api.github.com/repos/ultraware/roles/{archive_format}{/ref}",
                        "downloads_url": "https://api.github.com/repos/ultraware/roles/downloads",
                        "issues_url": "https://api.github.com/repos/ultraware/roles/issues{/number}",
                        "pulls_url": "https://api.github.com/repos/ultraware/roles/pulls{/number}",
                        "milestones_url": "https://api.github.com/repos/ultraware/roles/milestones{/number}",
                        "notifications_url": "https://api.github.com/repos/ultraware/roles/notifications{?since,all,participating}",
                        "labels_url": "https://api.github.com/repos/ultraware/roles/labels{/name}",
                        "releases_url": "https://api.github.com/repos/ultraware/roles/releases{/id}",
                        "deployments_url": "https://api.github.com/repos/ultraware/roles/deployments",
                        "created_at": "2016-11-28T12:36:37Z",
                        "updated_at": "2018-08-27T03:24:17Z",
                        "pushed_at": "2018-09-11T13:15:11Z",
                        "git_url": "git://github.com/ultraware/roles.git",
                        "ssh_url": "git@github.com:ultraware/roles.git",
                        "clone_url": "https://github.com/ultraware/roles.git",
                        "svn_url": "https://github.com/ultraware/roles",
                        "homepage": "",
                        "size": 183,
                        "stargazers_count": 98,
                        "watchers_count": 98,
                        "language": "PHP",
                        "has_issues": true,
                        "has_projects": true,
                        "has_downloads": true,
                        "has_wiki": true,
                        "has_pages": false,
                        "forks_count": 28,
                        "mirror_url": null,
                        "archived": false,
                        "open_issues_count": 2,
                        "license": {
                            "key": "mit",
                            "name": "MIT License",
                            "spdx_id": "MIT",
                            "url": "https://api.github.com/licenses/mit",
                            "node_id": "MDc6TGljZW5zZTEz"
                        },
                        "forks": 28,
                        "open_issues": 2,
                        "watchers": 98,
                        "default_branch": "5.7"
                    }
                },
                "_links": {
                    "self": {
                        "href": "https://api.github.com/repos/ultraware/roles/pulls/28"
                    },
                    "html": {
                        "href": "https://github.com/ultraware/roles/pull/28"
                    },
                    "issue": {
                        "href": "https://api.github.com/repos/ultraware/roles/issues/28"
                    },
                    "comments": {
                        "href": "https://api.github.com/repos/ultraware/roles/issues/28/comments"
                    },
                    "review_comments": {
                        "href": "https://api.github.com/repos/ultraware/roles/pulls/28/comments"
                    },
                    "review_comment": {
                        "href": "https://api.github.com/repos/ultraware/roles/pulls/comments{/number}"
                    },
                    "commits": {
                        "href": "https://api.github.com/repos/ultraware/roles/pulls/28/commits"
                    },
                    "statuses": {
                        "href": "https://api.github.com/repos/ultraware/roles/statuses/d637b9bf83d2822385a83de1b5f869e254d33d70"
                    }
                },
                "author_association": "NONE",
                "merged": false,
                "mergeable": null,
                "rebaseable": null,
                "mergeable_state": "unknown",
                "merged_by": null,
                "comments": 0,
                "review_comments": 0,
                "maintainer_can_modify": true,
                "commits": 1,
                "additions": 5,
                "deletions": 1,
                "changed_files": 3
            }
        },
        "public": true,
        "created_at": "2018-09-12T08:30:34Z",
        "org": {
            "id": 9347946,
            "login": "ultraware",
            "gravatar_id": "",
            "url": "https://api.github.com/orgs/ultraware",
            "avatar_url": "https://avatars.githubusercontent.com/u/9347946?"
        }
    },
    {
        "id": "8251639912",
        "type": "PushEvent",
        "actor": {
            "id": 29518211,
            "login": "carsonlius",
            "display_login": "carsonlius",
            "gravatar_id": "",
            "url": "https://api.github.com/users/carsonlius",
            "avatar_url": "https://avatars.githubusercontent.com/u/29518211?"
        },
        "repo": {
            "id": 148442219,
            "name": "carsonlius/roles",
            "url": "https://api.github.com/repos/carsonlius/roles"
        },
        "payload": {
            "push_id": 2869948239,
            "size": 1,
            "distinct_size": 1,
            "ref": "refs/heads/5.5",
            "head": "d637b9bf83d2822385a83de1b5f869e254d33d70",
            "before": "825786f507a116680b1fcad86cb0a22dabb67cfa",
            "commits": [
                {
                    "sha": "d637b9bf83d2822385a83de1b5f869e254d33d70",
                    "author": {
                        "email": "carsonlius@163.com",
                        "name": "carsonlius"
                    },
                    "message": "modify: 1. add permission tree 2. can be used as menu",
                    "distinct": true,
                    "url": "https://api.github.com/repos/carsonlius/roles/commits/d637b9bf83d2822385a83de1b5f869e254d33d70"
                }
            ]
        },
        "public": true,
        "created_at": "2018-09-12T08:28:08Z"
    },
    {
        "id": "8251432727",
        "type": "ForkEvent",
        "actor": {
            "id": 29518211,
            "login": "carsonlius",
            "display_login": "carsonlius",
            "gravatar_id": "",
            "url": "https://api.github.com/users/carsonlius",
            "avatar_url": "https://avatars.githubusercontent.com/u/29518211?"
        },
        "repo": {
            "id": 74971525,
            "name": "ultraware/roles",
            "url": "https://api.github.com/repos/ultraware/roles"
        },
        "payload": {
            "forkee": {
                "id": 148442219,
                "node_id": "MDEwOlJlcG9zaXRvcnkxNDg0NDIyMTk=",
                "name": "roles",
                "full_name": "carsonlius/roles",
                "owner": {
                    "login": "carsonlius",
                    "id": 29518211,
                    "node_id": "MDQ6VXNlcjI5NTE4MjEx",
                    "avatar_url": "https://avatars0.githubusercontent.com/u/29518211?v=4",
                    "gravatar_id": "",
                    "url": "https://api.github.com/users/carsonlius",
                    "html_url": "https://github.com/carsonlius",
                    "followers_url": "https://api.github.com/users/carsonlius/followers",
                    "following_url": "https://api.github.com/users/carsonlius/following{/other_user}",
                    "gists_url": "https://api.github.com/users/carsonlius/gists{/gist_id}",
                    "starred_url": "https://api.github.com/users/carsonlius/starred{/owner}{/repo}",
                    "subscriptions_url": "https://api.github.com/users/carsonlius/subscriptions",
                    "organizations_url": "https://api.github.com/users/carsonlius/orgs",
                    "repos_url": "https://api.github.com/users/carsonlius/repos",
                    "events_url": "https://api.github.com/users/carsonlius/events{/privacy}",
                    "received_events_url": "https://api.github.com/users/carsonlius/received_events",
                    "type": "User",
                    "site_admin": false
                },
                "private": false,
                "html_url": "https://github.com/carsonlius/roles",
                "description": "Powerful package for handling roles and permissions in Laravel 5",
                "fork": true,
                "url": "https://api.github.com/repos/carsonlius/roles",
                "forks_url": "https://api.github.com/repos/carsonlius/roles/forks",
                "keys_url": "https://api.github.com/repos/carsonlius/roles/keys{/key_id}",
                "collaborators_url": "https://api.github.com/repos/carsonlius/roles/collaborators{/collaborator}",
                "teams_url": "https://api.github.com/repos/carsonlius/roles/teams",
                "hooks_url": "https://api.github.com/repos/carsonlius/roles/hooks",
                "issue_events_url": "https://api.github.com/repos/carsonlius/roles/issues/events{/number}",
                "events_url": "https://api.github.com/repos/carsonlius/roles/events",
                "assignees_url": "https://api.github.com/repos/carsonlius/roles/assignees{/user}",
                "branches_url": "https://api.github.com/repos/carsonlius/roles/branches{/branch}",
                "tags_url": "https://api.github.com/repos/carsonlius/roles/tags",
                "blobs_url": "https://api.github.com/repos/carsonlius/roles/git/blobs{/sha}",
                "git_tags_url": "https://api.github.com/repos/carsonlius/roles/git/tags{/sha}",
                "git_refs_url": "https://api.github.com/repos/carsonlius/roles/git/refs{/sha}",
                "trees_url": "https://api.github.com/repos/carsonlius/roles/git/trees{/sha}",
                "statuses_url": "https://api.github.com/repos/carsonlius/roles/statuses/{sha}",
                "languages_url": "https://api.github.com/repos/carsonlius/roles/languages",
                "stargazers_url": "https://api.github.com/repos/carsonlius/roles/stargazers",
                "contributors_url": "https://api.github.com/repos/carsonlius/roles/contributors",
                "subscribers_url": "https://api.github.com/repos/carsonlius/roles/subscribers",
                "subscription_url": "https://api.github.com/repos/carsonlius/roles/subscription",
                "commits_url": "https://api.github.com/repos/carsonlius/roles/commits{/sha}",
                "git_commits_url": "https://api.github.com/repos/carsonlius/roles/git/commits{/sha}",
                "comments_url": "https://api.github.com/repos/carsonlius/roles/comments{/number}",
                "issue_comment_url": "https://api.github.com/repos/carsonlius/roles/issues/comments{/number}",
                "contents_url": "https://api.github.com/repos/carsonlius/roles/contents/{+path}",
                "compare_url": "https://api.github.com/repos/carsonlius/roles/compare/{base}...{head}",
                "merges_url": "https://api.github.com/repos/carsonlius/roles/merges",
                "archive_url": "https://api.github.com/repos/carsonlius/roles/{archive_format}{/ref}",
                "downloads_url": "https://api.github.com/repos/carsonlius/roles/downloads",
                "issues_url": "https://api.github.com/repos/carsonlius/roles/issues{/number}",
                "pulls_url": "https://api.github.com/repos/carsonlius/roles/pulls{/number}",
                "milestones_url": "https://api.github.com/repos/carsonlius/roles/milestones{/number}",
                "notifications_url": "https://api.github.com/repos/carsonlius/roles/notifications{?since,all,participating}",
                "labels_url": "https://api.github.com/repos/carsonlius/roles/labels{/name}",
                "releases_url": "https://api.github.com/repos/carsonlius/roles/releases{/id}",
                "deployments_url": "https://api.github.com/repos/carsonlius/roles/deployments",
                "created_at": "2018-09-12T07:48:11Z",
                "updated_at": "2018-08-27T03:24:17Z",
                "pushed_at": "2018-09-11T13:15:11Z",
                "git_url": "git://github.com/carsonlius/roles.git",
                "ssh_url": "git@github.com:carsonlius/roles.git",
                "clone_url": "https://github.com/carsonlius/roles.git",
                "svn_url": "https://github.com/carsonlius/roles",
                "homepage": "",
                "size": 183,
                "stargazers_count": 0,
                "watchers_count": 0,
                "language": null,
                "has_issues": false,
                "has_projects": true,
                "has_downloads": true,
                "has_wiki": true,
                "has_pages": false,
                "forks_count": 0,
                "mirror_url": null,
                "archived": false,
                "open_issues_count": 0,
                "license": {
                    "key": "mit",
                    "name": "MIT License",
                    "spdx_id": "MIT",
                    "url": "https://api.github.com/licenses/mit",
                    "node_id": "MDc6TGljZW5zZTEz"
                },
                "forks": 0,
                "open_issues": 0,
                "watchers": 0,
                "default_branch": "master",
                "public": true
            }
        },
        "public": true,
        "created_at": "2018-09-12T07:48:12Z",
        "org": {
            "id": 9347946,
            "login": "ultraware",
            "gravatar_id": "",
            "url": "https://api.github.com/orgs/ultraware",
            "avatar_url": "https://avatars.githubusercontent.com/u/9347946?"
        }
    },
    {
        "id": "8251183883",
        "type": "DeleteEvent",
        "actor": {
            "id": 29518211,
            "login": "carsonlius",
            "display_login": "carsonlius",
            "gravatar_id": "",
            "url": "https://api.github.com/users/carsonlius",
            "avatar_url": "https://avatars.githubusercontent.com/u/29518211?"
        },
        "repo": {
            "id": 148412832,
            "name": "carsonlius/roles",
            "url": "https://api.github.com/repos/carsonlius/roles"
        },
        "payload": {
            "ref": "5.7",
            "ref_type": "branch",
            "pusher_type": "user"
        },
        "public": true,
        "created_at": "2018-09-12T06:54:19Z"
    },
    {
        "id": "8251183778",
        "type": "DeleteEvent",
        "actor": {
            "id": 29518211,
            "login": "carsonlius",
            "display_login": "carsonlius",
            "gravatar_id": "",
            "url": "https://api.github.com/users/carsonlius",
            "avatar_url": "https://avatars.githubusercontent.com/u/29518211?"
        },
        "repo": {
            "id": 148412832,
            "name": "carsonlius/roles",
            "url": "https://api.github.com/repos/carsonlius/roles"
        },
        "payload": {
            "ref": "5.6",
            "ref_type": "branch",
            "pusher_type": "user"
        },
        "public": true,
        "created_at": "2018-09-12T06:54:17Z"
    },
    {
        "id": "8251181804",
        "type": "DeleteEvent",
        "actor": {
            "id": 29518211,
            "login": "carsonlius",
            "display_login": "carsonlius",
            "gravatar_id": "",
            "url": "https://api.github.com/users/carsonlius",
            "avatar_url": "https://avatars.githubusercontent.com/u/29518211?"
        },
        "repo": {
            "id": 148412832,
            "name": "carsonlius/roles",
            "url": "https://api.github.com/repos/carsonlius/roles"
        },
        "payload": {
            "ref": "5.5",
            "ref_type": "branch",
            "pusher_type": "user"
        },
        "public": true,
        "created_at": "2018-09-12T06:53:49Z"
    },
    {
        "id": "8251181564",
        "type": "DeleteEvent",
        "actor": {
            "id": 29518211,
            "login": "carsonlius",
            "display_login": "carsonlius",
            "gravatar_id": "",
            "url": "https://api.github.com/users/carsonlius",
            "avatar_url": "https://avatars.githubusercontent.com/u/29518211?"
        },
        "repo": {
            "id": 148412832,
            "name": "carsonlius/roles",
            "url": "https://api.github.com/repos/carsonlius/roles"
        },
        "payload": {
            "ref": "5.4",
            "ref_type": "branch",
            "pusher_type": "user"
        },
        "public": true,
        "created_at": "2018-09-12T06:53:45Z"
    },
    {
        "id": "8251181412",
        "type": "DeleteEvent",
        "actor": {
            "id": 29518211,
            "login": "carsonlius",
            "display_login": "carsonlius",
            "gravatar_id": "",
            "url": "https://api.github.com/users/carsonlius",
            "avatar_url": "https://avatars.githubusercontent.com/u/29518211?"
        },
        "repo": {
            "id": 148412832,
            "name": "carsonlius/roles",
            "url": "https://api.github.com/repos/carsonlius/roles"
        },
        "payload": {
            "ref": "5.3",
            "ref_type": "branch",
            "pusher_type": "user"
        },
        "public": true,
        "created_at": "2018-09-12T06:53:43Z"
    },
    {
        "id": "8251181276",
        "type": "DeleteEvent",
        "actor": {
            "id": 29518211,
            "login": "carsonlius",
            "display_login": "carsonlius",
            "gravatar_id": "",
            "url": "https://api.github.com/users/carsonlius",
            "avatar_url": "https://avatars.githubusercontent.com/u/29518211?"
        },
        "repo": {
            "id": 148412832,
            "name": "carsonlius/roles",
            "url": "https://api.github.com/repos/carsonlius/roles"
        },
        "payload": {
            "ref": "5.2",
            "ref_type": "branch",
            "pusher_type": "user"
        },
        "public": true,
        "created_at": "2018-09-12T06:53:42Z"
    },
    {
        "id": "8251181125",
        "type": "DeleteEvent",
        "actor": {
            "id": 29518211,
            "login": "carsonlius",
            "display_login": "carsonlius",
            "gravatar_id": "",
            "url": "https://api.github.com/users/carsonlius",
            "avatar_url": "https://avatars.githubusercontent.com/u/29518211?"
        },
        "repo": {
            "id": 148412832,
            "name": "carsonlius/roles",
            "url": "https://api.github.com/repos/carsonlius/roles"
        },
        "payload": {
            "ref": "5.1",
            "ref_type": "branch",
            "pusher_type": "user"
        },
        "public": true,
        "created_at": "2018-09-12T06:53:40Z"
    },
    {
        "id": "8250598233",
        "type": "WatchEvent",
        "actor": {
            "id": 29518211,
            "login": "carsonlius",
            "display_login": "carsonlius",
            "gravatar_id": "",
            "url": "https://api.github.com/users/carsonlius",
            "avatar_url": "https://avatars.githubusercontent.com/u/29518211?"
        },
        "repo": {
            "id": 148412832,
            "name": "carsonlius/roles",
            "url": "https://api.github.com/repos/carsonlius/roles"
        },
        "payload": {
            "action": "started"
        },
        "public": true,
        "created_at": "2018-09-12T03:42:11Z"
    },
    {
        "id": "8250488720",
        "type": "PushEvent",
        "actor": {
            "id": 29518211,
            "login": "carsonlius",
            "display_login": "carsonlius",
            "gravatar_id": "",
            "url": "https://api.github.com/users/carsonlius",
            "avatar_url": "https://avatars.githubusercontent.com/u/29518211?"
        },
        "repo": {
            "id": 125470950,
            "name": "carsonlius/zhihu_try",
            "url": "https://api.github.com/repos/carsonlius/zhihu_try"
        },
        "payload": {
            "push_id": 2869336123,
            "size": 1,
            "distinct_size": 1,
            "ref": "refs/heads/master",
            "head": "6b4032d0c6365a8306293c3727489eed463e8f76",
            "before": "e0b18c5221403dea2cd26957bd37aa5338e1defc",
            "commits": [
                {
                    "sha": "6b4032d0c6365a8306293c3727489eed463e8f76",
                    "author": {
                        "email": "carsonlius@163.com",
                        "name": "carsonlius"
                    },
                    "message": "modify: 解决ultraware/roles 扩展的问题",
                    "distinct": true,
                    "url": "https://api.github.com/repos/carsonlius/zhihu_try/commits/6b4032d0c6365a8306293c3727489eed463e8f76"
                }
            ]
        },
        "public": true,
        "created_at": "2018-09-12T03:03:26Z"
    },
    {
        "id": "8250478741",
        "type": "ForkEvent",
        "actor": {
            "id": 29518211,
            "login": "carsonlius",
            "display_login": "carsonlius",
            "gravatar_id": "",
            "url": "https://api.github.com/users/carsonlius",
            "avatar_url": "https://avatars.githubusercontent.com/u/29518211?"
        },
        "repo": {
            "id": 74971525,
            "name": "ultraware/roles",
            "url": "https://api.github.com/repos/ultraware/roles"
        },
        "payload": {
            "forkee": {
                "id": 148412832,
                "node_id": "MDEwOlJlcG9zaXRvcnkxNDg0MTI4MzI=",
                "name": "roles",
                "full_name": "carsonlius/roles",
                "owner": {
                    "login": "carsonlius",
                    "id": 29518211,
                    "node_id": "MDQ6VXNlcjI5NTE4MjEx",
                    "avatar_url": "https://avatars0.githubusercontent.com/u/29518211?v=4",
                    "gravatar_id": "",
                    "url": "https://api.github.com/users/carsonlius",
                    "html_url": "https://github.com/carsonlius",
                    "followers_url": "https://api.github.com/users/carsonlius/followers",
                    "following_url": "https://api.github.com/users/carsonlius/following{/other_user}",
                    "gists_url": "https://api.github.com/users/carsonlius/gists{/gist_id}",
                    "starred_url": "https://api.github.com/users/carsonlius/starred{/owner}{/repo}",
                    "subscriptions_url": "https://api.github.com/users/carsonlius/subscriptions",
                    "organizations_url": "https://api.github.com/users/carsonlius/orgs",
                    "repos_url": "https://api.github.com/users/carsonlius/repos",
                    "events_url": "https://api.github.com/users/carsonlius/events{/privacy}",
                    "received_events_url": "https://api.github.com/users/carsonlius/received_events",
                    "type": "User",
                    "site_admin": false
                },
                "private": false,
                "html_url": "https://github.com/carsonlius/roles",
                "description": "Powerful package for handling roles and permissions in Laravel 5",
                "fork": true,
                "url": "https://api.github.com/repos/carsonlius/roles",
                "forks_url": "https://api.github.com/repos/carsonlius/roles/forks",
                "keys_url": "https://api.github.com/repos/carsonlius/roles/keys{/key_id}",
                "collaborators_url": "https://api.github.com/repos/carsonlius/roles/collaborators{/collaborator}",
                "teams_url": "https://api.github.com/repos/carsonlius/roles/teams",
                "hooks_url": "https://api.github.com/repos/carsonlius/roles/hooks",
                "issue_events_url": "https://api.github.com/repos/carsonlius/roles/issues/events{/number}",
                "events_url": "https://api.github.com/repos/carsonlius/roles/events",
                "assignees_url": "https://api.github.com/repos/carsonlius/roles/assignees{/user}",
                "branches_url": "https://api.github.com/repos/carsonlius/roles/branches{/branch}",
                "tags_url": "https://api.github.com/repos/carsonlius/roles/tags",
                "blobs_url": "https://api.github.com/repos/carsonlius/roles/git/blobs{/sha}",
                "git_tags_url": "https://api.github.com/repos/carsonlius/roles/git/tags{/sha}",
                "git_refs_url": "https://api.github.com/repos/carsonlius/roles/git/refs{/sha}",
                "trees_url": "https://api.github.com/repos/carsonlius/roles/git/trees{/sha}",
                "statuses_url": "https://api.github.com/repos/carsonlius/roles/statuses/{sha}",
                "languages_url": "https://api.github.com/repos/carsonlius/roles/languages",
                "stargazers_url": "https://api.github.com/repos/carsonlius/roles/stargazers",
                "contributors_url": "https://api.github.com/repos/carsonlius/roles/contributors",
                "subscribers_url": "https://api.github.com/repos/carsonlius/roles/subscribers",
                "subscription_url": "https://api.github.com/repos/carsonlius/roles/subscription",
                "commits_url": "https://api.github.com/repos/carsonlius/roles/commits{/sha}",
                "git_commits_url": "https://api.github.com/repos/carsonlius/roles/git/commits{/sha}",
                "comments_url": "https://api.github.com/repos/carsonlius/roles/comments{/number}",
                "issue_comment_url": "https://api.github.com/repos/carsonlius/roles/issues/comments{/number}",
                "contents_url": "https://api.github.com/repos/carsonlius/roles/contents/{+path}",
                "compare_url": "https://api.github.com/repos/carsonlius/roles/compare/{base}...{head}",
                "merges_url": "https://api.github.com/repos/carsonlius/roles/merges",
                "archive_url": "https://api.github.com/repos/carsonlius/roles/{archive_format}{/ref}",
                "downloads_url": "https://api.github.com/repos/carsonlius/roles/downloads",
                "issues_url": "https://api.github.com/repos/carsonlius/roles/issues{/number}",
                "pulls_url": "https://api.github.com/repos/carsonlius/roles/pulls{/number}",
                "milestones_url": "https://api.github.com/repos/carsonlius/roles/milestones{/number}",
                "notifications_url": "https://api.github.com/repos/carsonlius/roles/notifications{?since,all,participating}",
                "labels_url": "https://api.github.com/repos/carsonlius/roles/labels{/name}",
                "releases_url": "https://api.github.com/repos/carsonlius/roles/releases{/id}",
                "deployments_url": "https://api.github.com/repos/carsonlius/roles/deployments",
                "created_at": "2018-09-12T03:00:07Z",
                "updated_at": "2018-08-27T03:24:17Z",
                "pushed_at": "2018-09-11T13:15:11Z",
                "git_url": "git://github.com/carsonlius/roles.git",
                "ssh_url": "git@github.com:carsonlius/roles.git",
                "clone_url": "https://github.com/carsonlius/roles.git",
                "svn_url": "https://github.com/carsonlius/roles",
                "homepage": "",
                "size": 183,
                "stargazers_count": 0,
                "watchers_count": 0,
                "language": null,
                "has_issues": false,
                "has_projects": true,
                "has_downloads": true,
                "has_wiki": true,
                "has_pages": false,
                "forks_count": 0,
                "mirror_url": null,
                "archived": false,
                "open_issues_count": 0,
                "license": {
                    "key": "mit",
                    "name": "MIT License",
                    "spdx_id": "MIT",
                    "url": "https://api.github.com/licenses/mit",
                    "node_id": "MDc6TGljZW5zZTEz"
                },
                "forks": 0,
                "open_issues": 0,
                "watchers": 0,
                "default_branch": "master",
                "public": true
            }
        },
        "public": true,
        "created_at": "2018-09-12T03:00:08Z",
        "org": {
            "id": 9347946,
            "login": "ultraware",
            "gravatar_id": "",
            "url": "https://api.github.com/orgs/ultraware",
            "avatar_url": "https://avatars.githubusercontent.com/u/9347946?"
        }
    },
    {
        "id": "8246406812",
        "type": "PushEvent",
        "actor": {
            "id": 29518211,
            "login": "carsonlius",
            "display_login": "carsonlius",
            "gravatar_id": "",
            "url": "https://api.github.com/users/carsonlius",
            "avatar_url": "https://avatars.githubusercontent.com/u/29518211?"
        },
        "repo": {
            "id": 125470950,
            "name": "carsonlius/zhihu_try",
            "url": "https://api.github.com/repos/carsonlius/zhihu_try"
        },
        "payload": {
            "push_id": 2867240705,
            "size": 1,
            "distinct_size": 1,
            "ref": "refs/heads/master",
            "head": "e0b18c5221403dea2cd26957bd37aa5338e1defc",
            "before": "b9a3c7ac8aa7e7da6a3a64d1ca5220eba90a5c7f",
            "commits": [
                {
                    "sha": "e0b18c5221403dea2cd26957bd37aa5338e1defc",
                    "author": {
                        "email": "carsonlius@163.com",
                        "name": "carsonlius"
                    },
                    "message": "mdoify: lavary/laravel-menu 重构菜单",
                    "distinct": true,
                    "url": "https://api.github.com/repos/carsonlius/zhihu_try/commits/e0b18c5221403dea2cd26957bd37aa5338e1defc"
                }
            ]
        },
        "public": true,
        "created_at": "2018-09-11T12:51:46Z"
    },
    {
        "id": "8246078947",
        "type": "IssuesEvent",
        "actor": {
            "id": 29518211,
            "login": "carsonlius",
            "display_login": "carsonlius",
            "gravatar_id": "",
            "url": "https://api.github.com/users/carsonlius",
            "avatar_url": "https://avatars.githubusercontent.com/u/29518211?"
        },
        "repo": {
            "id": 75356542,
            "name": "huangshuwei/vue-easytable",
            "url": "https://api.github.com/repos/huangshuwei/vue-easytable"
        },
        "payload": {
            "action": "closed",
            "issue": {
                "url": "https://api.github.com/repos/huangshuwei/vue-easytable/issues/236",
                "repository_url": "https://api.github.com/repos/huangshuwei/vue-easytable",
                "labels_url": "https://api.github.com/repos/huangshuwei/vue-easytable/issues/236/labels{/name}",
                "comments_url": "https://api.github.com/repos/huangshuwei/vue-easytable/issues/236/comments",
                "events_url": "https://api.github.com/repos/huangshuwei/vue-easytable/issues/236/events",
                "html_url": "https://github.com/huangshuwei/vue-easytable/issues/236",
                "id": 358949074,
                "node_id": "MDU6SXNzdWUzNTg5NDkwNzQ=",
                "number": 236,
                "title": "翻墙也看不了文档 Unable to round-trip http request to upstream: read tcp 172.18.236.215:5575185.199.108.153:80: wsarecv: An existing connection was forcibly closed by the remote host.",
                "user": {
                    "login": "carsonlius",
                    "id": 29518211,
                    "node_id": "MDQ6VXNlcjI5NTE4MjEx",
                    "avatar_url": "https://avatars0.githubusercontent.com/u/29518211?v=4",
                    "gravatar_id": "",
                    "url": "https://api.github.com/users/carsonlius",
                    "html_url": "https://github.com/carsonlius",
                    "followers_url": "https://api.github.com/users/carsonlius/followers",
                    "following_url": "https://api.github.com/users/carsonlius/following{/other_user}",
                    "gists_url": "https://api.github.com/users/carsonlius/gists{/gist_id}",
                    "starred_url": "https://api.github.com/users/carsonlius/starred{/owner}{/repo}",
                    "subscriptions_url": "https://api.github.com/users/carsonlius/subscriptions",
                    "organizations_url": "https://api.github.com/users/carsonlius/orgs",
                    "repos_url": "https://api.github.com/users/carsonlius/repos",
                    "events_url": "https://api.github.com/users/carsonlius/events{/privacy}",
                    "received_events_url": "https://api.github.com/users/carsonlius/received_events",
                    "type": "User",
                    "site_admin": false
                },
                "labels": [],
                "state": "closed",
                "locked": false,
                "assignee": null,
                "assignees": [],
                "milestone": null,
                "comments": 0,
                "created_at": "2018-09-11T08:53:53Z",
                "updated_at": "2018-09-11T11:48:35Z",
                "closed_at": "2018-09-11T11:48:35Z",
                "author_association": "NONE",
                "body": "Unable to round-trip http request to upstream: read tcp 172.18.236.215:55759->185.199.108.153:80: wsarecv: An existing connection was forcibly closed by the remote host."
            }
        },
        "public": true,
        "created_at": "2018-09-11T11:48:35Z"
    },
    {
        "id": "8245543347",
        "type": "PushEvent",
        "actor": {
            "id": 29518211,
            "login": "carsonlius",
            "display_login": "carsonlius",
            "gravatar_id": "",
            "url": "https://api.github.com/users/carsonlius",
            "avatar_url": "https://avatars.githubusercontent.com/u/29518211?"
        },
        "repo": {
            "id": 125470950,
            "name": "carsonlius/zhihu_try",
            "url": "https://api.github.com/repos/carsonlius/zhihu_try"
        },
        "payload": {
            "push_id": 2866798115,
            "size": 1,
            "distinct_size": 1,
            "ref": "refs/heads/master",
            "head": "b9a3c7ac8aa7e7da6a3a64d1ca5220eba90a5c7f",
            "before": "19034d7b33085720e23bcb3eb620b035e064429d",
            "commits": [
                {
                    "sha": "b9a3c7ac8aa7e7da6a3a64d1ca5220eba90a5c7f",
                    "author": {
                        "email": "carsonlius@163.com",
                        "name": "carsonlius"
                    },
                    "message": "modify: 权限增加是否展示选项",
                    "distinct": true,
                    "url": "https://api.github.com/repos/carsonlius/zhihu_try/commits/b9a3c7ac8aa7e7da6a3a64d1ca5220eba90a5c7f"
                }
            ]
        },
        "public": true,
        "created_at": "2018-09-11T10:00:05Z"
    },
    {
        "id": "8245164730",
        "type": "IssuesEvent",
        "actor": {
            "id": 29518211,
            "login": "carsonlius",
            "display_login": "carsonlius",
            "gravatar_id": "",
            "url": "https://api.github.com/users/carsonlius",
            "avatar_url": "https://avatars.githubusercontent.com/u/29518211?"
        },
        "repo": {
            "id": 75356542,
            "name": "huangshuwei/vue-easytable",
            "url": "https://api.github.com/repos/huangshuwei/vue-easytable"
        },
        "payload": {
            "action": "opened",
            "issue": {
                "url": "https://api.github.com/repos/huangshuwei/vue-easytable/issues/236",
                "repository_url": "https://api.github.com/repos/huangshuwei/vue-easytable",
                "labels_url": "https://api.github.com/repos/huangshuwei/vue-easytable/issues/236/labels{/name}",
                "comments_url": "https://api.github.com/repos/huangshuwei/vue-easytable/issues/236/comments",
                "events_url": "https://api.github.com/repos/huangshuwei/vue-easytable/issues/236/events",
                "html_url": "https://github.com/huangshuwei/vue-easytable/issues/236",
                "id": 358949074,
                "node_id": "MDU6SXNzdWUzNTg5NDkwNzQ=",
                "number": 236,
                "title": "翻墙也看不了文档 Unable to round-trip http request to upstream: read tcp 172.18.236.215:5575185.199.108.153:80: wsarecv: An existing connection was forcibly closed by the remote host.",
                "user": {
                    "login": "carsonlius",
                    "id": 29518211,
                    "node_id": "MDQ6VXNlcjI5NTE4MjEx",
                    "avatar_url": "https://avatars0.githubusercontent.com/u/29518211?v=4",
                    "gravatar_id": "",
                    "url": "https://api.github.com/users/carsonlius",
                    "html_url": "https://github.com/carsonlius",
                    "followers_url": "https://api.github.com/users/carsonlius/followers",
                    "following_url": "https://api.github.com/users/carsonlius/following{/other_user}",
                    "gists_url": "https://api.github.com/users/carsonlius/gists{/gist_id}",
                    "starred_url": "https://api.github.com/users/carsonlius/starred{/owner}{/repo}",
                    "subscriptions_url": "https://api.github.com/users/carsonlius/subscriptions",
                    "organizations_url": "https://api.github.com/users/carsonlius/orgs",
                    "repos_url": "https://api.github.com/users/carsonlius/repos",
                    "events_url": "https://api.github.com/users/carsonlius/events{/privacy}",
                    "received_events_url": "https://api.github.com/users/carsonlius/received_events",
                    "type": "User",
                    "site_admin": false
                },
                "labels": [],
                "state": "open",
                "locked": false,
                "assignee": null,
                "assignees": [],
                "milestone": null,
                "comments": 0,
                "created_at": "2018-09-11T08:53:53Z",
                "updated_at": "2018-09-11T08:53:53Z",
                "closed_at": null,
                "author_association": "NONE",
                "body": "Unable to round-trip http request to upstream: read tcp 172.18.236.215:55759->185.199.108.153:80: wsarecv: An existing connection was forcibly closed by the remote host."
            }
        },
        "public": true,
        "created_at": "2018-09-11T08:53:53Z"
    },
    {
        "id": "8244695820",
        "type": "PushEvent",
        "actor": {
            "id": 29518211,
            "login": "carsonlius",
            "display_login": "carsonlius",
            "gravatar_id": "",
            "url": "https://api.github.com/users/carsonlius",
            "avatar_url": "https://avatars.githubusercontent.com/u/29518211?"
        },
        "repo": {
            "id": 125470950,
            "name": "carsonlius/zhihu_try",
            "url": "https://api.github.com/repos/carsonlius/zhihu_try"
        },
        "payload": {
            "push_id": 2866364190,
            "size": 1,
            "distinct_size": 1,
            "ref": "refs/heads/master",
            "head": "19034d7b33085720e23bcb3eb620b035e064429d",
            "before": "4fce65cba0d49983e227b45565a9b555be43f7e1",
            "commits": [
                {
                    "sha": "19034d7b33085720e23bcb3eb620b035e064429d",
                    "author": {
                        "email": "carsonlius@163.com",
                        "name": "carsonlius"
                    },
                    "message": "modify: 权限管理tree",
                    "distinct": true,
                    "url": "https://api.github.com/repos/carsonlius/zhihu_try/commits/19034d7b33085720e23bcb3eb620b035e064429d"
                }
            ]
        },
        "public": true,
        "created_at": "2018-09-11T07:21:52Z"
    }
]
        ';


        return json_decode($event_json, true);
    }


    /**
     *
     */
    public function lesson2()
    {
        $gates = [
            'BaiYun_A_A17',
            'BeiJing_J7',
            'ShuangLiu_K203',
            'HongQiao_A157',
            'A2',
            'BaiYun_B_B230'
        ];

        $boards = [
            'A17',
            'J7',
            'K203',
            'A157',
            'A2',
            'B230'
        ];

        $list_gate = collect($gates)->map(function ($gate) {
            return collect(explode('_', $gate))->last();
        });

        dump($list_gate);
    }


    public function lesson1()
    {
        $orders = [[
            'id' => 1,
            'user_id' => 1,
            'number' => '13908080808',
            'status' => 0,
            'fee' => 10,
            'discount' => 44,
            'order_products' => [
                ['order_id' => 1, 'product_id' => 1, 'param' => '6寸', 'price' => 555.00, 'product' => ['id' => 1, 'name' => '蛋糕名称', 'images' => []]],
                ['order_id' => 1, 'product_id' => 1, 'param' => '7寸', 'price' => 333.00, 'product' => ['id' => 1, 'name' => '蛋糕名称', 'images' => []]],
            ],
        ]];
        return $this->response->item(collect($orders), function (){});
    }
}
