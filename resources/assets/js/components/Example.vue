<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Example Component哈哈</div>

                    <div class="panel-body">
                        ========================================= 11111 ===============================================
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data : function(){
            return {
                name : 'liusen'
            }
        },
        mounted : function(){
            console.log('Example Component begin ========');
            let vm = this;
            axios.get('https://learn7.carsonlius.vip/api/v1/test').then(function (response) {
                console.log(response);
                console.log('Example Component end! =========');
                vm.name = response.data;
            })


        }
    }
</script>
