<template>
    <div class="page-group">
        <!-- 你的html代码 -->
        <div class="page">
            <header class="bar bar-nav">
                <a class="button button-link button-nav pull-left" v-link="{path:'/',activeClass: 'active', replace: true}">
                    <span class="icon icon-left"></span>
                    返回
                </a>
                <h1 class="title">我的生活</h1>
            </header>
            <nav class="bar bar-tab">
                <a class="tab-item external active" v-link="{path:'/home',activeClass: 'active', replace: true}">
                    <span class="icon icon-home"></span>
                    <span class="tab-label">首页</span>
                </a>
                <a class="tab-item external" v-link="{path:'/user'}">
                    <span class="icon icon-me"></span>
                    <span class="tab-label">我</span>
                </a>
                <a class="tab-item external" v-link="{path:'/collect'}">
                    <span class="icon icon-star"></span>
                    <span class="tab-label">收藏</span>
                </a>
                <a class="tab-item external" v-link="{path:'/setting'}">
                    <span class="icon icon-settings"></span>
                    <span class="tab-label">设置</span>
                </a>
            </nav>
            <div class="content">
                <!-- 这里是页面内容区 -->
                <router-view transition="fade" transition-mode="out-in" keep-alive></router-view>
            </div>
        </div>
    </div>

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
        components:{

        }
    }
</script>
