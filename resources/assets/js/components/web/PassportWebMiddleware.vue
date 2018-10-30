<template>
    <div class="panel panel-default">
        <div class="panel-heading">
            本页面用来测试passport web middleware; 这个Package允许前端无缝的方式api
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
        mounted: function () {
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
</script>

<style scoped>

</style>