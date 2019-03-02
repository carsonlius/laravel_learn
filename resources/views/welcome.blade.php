<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    <script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.js"></script>

    <!-- Styles -->
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Raleway', sans-serif;
            font-weight: 100;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 84px;
        }

        .links > a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .m-b-md {
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
<div class="flex-center position-ref full-height">
    @if (Route::has('login'))
        <div class="top-right links">
            {{ \Auth::check() ? 'Hello' : 'World' }}
            @auth
                <a href="{{ url('/home') }}">Home</a>
            @else
                <a href="{{ route('login') }}">Login</a>
                <a href="{{ route('register') }}">Register</a>
            @endauth
        </div>
    @endif
    <div class="content">
        <div class="title m-b-md">
            Laravel(47.104.196.199) Cheers
        </div>

        <div class="links">
            <a href="https://laravel.com/docs">Documentation</a>
            <a href="https://laracasts.com">Laracasts</a>
            <a href="https://laravel-news.com">News</a>
            <a href="https://forge.laravel.com">Forge</a>
            <a href="https://github.com/laravel/laravel">GitHub</a>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <input type="text" data-bind-123="name" value=""/> 姓名

            <input type="text" id="name" value=""> 表现值
        </div>
    </div>
</div>

<script>

    function DataBinder(object_id) {
        //使用一个jQuery对象作为简单的订阅者发布者
        var pubSub = jQuery({});

        //我们希望一个data元素可以在表单中指明绑定：data-bind-<object_id>="<property_name>"

        var data_attr = "bind-" + object_id,
            message = object_id + ":change";

        //使用data-binding属性和代理来监听那个元素上的变化事件
        // 以便变化能够“广播”到所有的关联对象

        jQuery(document).on("change", "[data-" + data_attr + "]", function (evt) {
            var input = jQuery(this);
            console.log('触发监听事件', input.val(), input.data(data_attr));
            pubSub.trigger(message, [input.data(data_attr), input.val()]);
        });

        //PubSub将变化传播到所有的绑定元素，设置input标签的值或者其他标签的HTML内容

        pubSub.on(message, function (evt, prop_name, new_val) {
            console.log('事件发生运行的函数', evt, prop_name, new_val);
            jQuery("[data-" + data_attr + "=" + prop_name + "]").each(function () {
                var $bound = jQuery(this);

                if ($bound.is("input,text textarea,select")) {
                    $bound.val(new_val);
                } else {
                    $bound.html(new_val);
                }
            });
        });

        return pubSub;
    }

    function User(uid) {
        var binder = new DataBinder(uid),
            user = {
                attributes: {name : ''},

                // 属性设置器使用数据绑定器PubSub来发布变化

                set: function (attr_name, val) {
                    this.attributes[attr_name] = val;
                    console.log('设置变化');
                    binder.trigger(uid + ":change", [attr_name, val, this]);
                },

                get: function (attr_name) {
                    return this.attributes[attr_name];
                },

                _binder: binder
            };

        binder.on(uid + ":change", function (vet, attr_name, new_val, initiator) {
            console.log(initiator, '初始化');
            if (initiator !== user) {
                user.set(attr_name, new_val);
            }
        });
        return user;
    }

    var user = new User('123');
    console.log(user);
    // user.set("name","Wolfgang");
</script>
</body>
</html>
