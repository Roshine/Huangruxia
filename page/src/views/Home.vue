<template>
    <div class="content-block">这里是content</div>
</template>
<style>
</style>
<script>
    export default{
        data(){
            return{
                msg:'hello vue'
            }
        },
        ready () {
            $.init()
        },
        components:{
        }
    }
</script>
