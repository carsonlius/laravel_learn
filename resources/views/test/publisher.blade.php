@extends('layouts.app')
@section('content')
    <div>
        <h5>发布者/订阅者模式</h5>
        <input type="text" data-bind-123="name" value=""/> 姓名
    </div>
@endsection
<script>
    /*
         Publisher 代表出版社构造器
         subscribers 代表投递报纸的列表
         deliver  分发
         subscribe 订阅报纸的接口
         publisher1  出版社实例对象
         observer1 实例化的读者
     */

    // 出版社（权力制高点）
    function Publisher() {

        // 订阅了本出版社的读者列表
        this.subscribers = [];
    }

    // 添加一个迭代方法，遍历所有投递列表
    Publisher.prototype.deliver = function(data) {
        // 遍历当前出版社对象所有的订阅过的方法
        this.subscribers.forEach(function(fn) {
            //data用于传参数给内部方法
            fn.fire(data);
        });
        return this;
    };


    // 观察者
    function Observe(callback) {
        this.fire = callback;
    }

    // 给予订阅者阅读的能力
    Observe.prototype.subscribe = function(publisher) {

        var that = this;
        // some如果有一个返回值为true则为true
        var alreadyExists = publisher.subscribers.some(function(el){

            // 这里的el指的是函数对象
            return el.fire === that.fire;
        });

        // 如果存在这个函数对象则不进行添加
        if (!alreadyExists) {
            publisher.subscribers.push(this);
        }

        //订阅列表
        console.log('publisher列表', publisher.subscribers);
        return this;
    };

    // 给予观察者退订的能力
    Observe.prototype.unsubscribe = function(publisher) {

        var that = this;

        // 过滤，将返回值为true的重组成数组
        publisher.subscribers = publisher.subscribers.filter(function(el) {

            // 这里的el.fire指的是观察者传入的callback
            // that.fire就是当前对象对callback的引用
            return !(el.fire === that.fire);
        });
        console.log(publisher.subscribers);
        return this;
    };

    var publisher1 = new Publisher();

    // 实例化的读者，这个需要改进
    var observer1 = new Observe(function(data) {
        console.log(data);
    });

    // 读者订阅了一家报纸,在报社可以查询到该读者已经在订阅者列表了，
    // publisher1.subscribers->[observer1]
    observer1.subscribe(publisher1);

    // 分发报纸，执行所有内在方法
    publisher1.deliver(123);// 输出123
    publisher1.deliver('hello world');// 输出123

    // 退订
    observer1.unsubscribe(publisher1);
</script>