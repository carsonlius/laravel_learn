<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Example Component222</div>

                    <div class="panel-body">
                        I'm an example component222!
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data : function(){
        },
        mounted : function(){
            console.log('Example Component!');
            console.log(window.Laravel.user_id);
            window.Echo.private('clear-posts.' + window.Laravel.user).listen('ClearUserUpdatedEvent', function(e){
                alert('现在进入了private渠道');
                console.log('private', e);
            });
        }
    }
</script>
