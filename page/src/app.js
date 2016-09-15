import Vue from 'vue'
import VueRouter from 'vue-router'
//import VueResource from 'vue-resource'
import routerConfig from './router'
import app from './Main.vue'

// Router
Vue.use(VueRouter)

const router = new VueRouter({
    hashbang: true,
    history: true,
    saveScrollPosition: true,
    suppressTransitionError: true
})

routerConfig(router)

// Resource
//Vue.use(VueResource)


router.start(app, '#app')

window.router = router
