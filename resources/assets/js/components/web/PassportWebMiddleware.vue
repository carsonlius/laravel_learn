<template>
    <div class="panel panel-default">
        <div class="panel-heading">
            本页面用来测试passport web middleware; 这个Package允许前端无缝的方式api,HAHAHAHAHA
        </div>
        <div class="panel-body">
          {{ this.user }}
        </div>
    </div>
</template>

<script>
    export default {
        name: "ConsumeJS",
        data: function () {
            return {
                user: {},
                lesson: {}
            }
        },
        created: function () {
            this.channelNotify();
            this.channelPrivate();
            this.channelSubscribe();
            this.privateSubscribe();

            // 测试内部环境axios对接口的调用权限
            this.testPermission();
        },
        methods : {
            channelNotify: function(){
                console.log('触发了notification channel');
                window.Echo.private('App.User.' + window.Laravel.user).notification(function (notification) {
                    console.log(notification.type);
                    console.log(notification);
                    alert('触发了notification channel');
                });
            },
            channelPrivate : function(){
                console.log('Example Component2!');
                console.log(window.Laravel.user);
                window.Echo.private('clear-posts.' + window.Laravel.user).listen('ClearUserUpdatedEvent', function(e){
                    alert('现在进入了user-updated private渠道');
                    console.log('private', e);
                });
            },
            channelSubscribe : function(){
                window.Echo.channel('posts.' + window.Laravel.user).listen('PostUpdatedEvent', function (e) {
                    console.log('这里是public channel环境', e);
                });
            },
            privateSubscribe : function(){
                // let user_id = 1;
                window.Echo.private('clear-orders.' + window.Laravel.user).listen('orderUpdatedEvent', function (e) {
                    alert('here is  orders private channel...');
                    console.log('这里是orders private channel环境', e);
                });
            },
            testPermission: function(){
                axios.get('/api/user')
                    .then(response => {
                        this.user = response.data;
                        console.log(response);
                    });
                axios.get('/api/lesson1').then(response=>{
                    this.response = response.data;
                    console.log(response);
                }).catch(response=>{
                    console.log('请求lesson1遇到了问题', response);
                });
            }
        }
    }
</script>

<style scoped>

</style>