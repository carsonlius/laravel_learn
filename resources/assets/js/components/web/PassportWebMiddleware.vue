<template>
    <div>
        <h5>Hello World3</h5>
    </div>
</template>

<script>
    export default {
        name: "ConsumeJS",
        data : function(){
            return {
                user : {}
            }
        },
        mounted : function () {
            axios.get('/api/user')
                .then(response => {
                    this.user = response.data;
                    console.log(response);
                });
        }
    }
</script>

<style scoped>

</style>