
export default function (router) {
    router.map({
        '/': {
            component (resolve) {
                require(['./views/Home.vue'], resolve)
            }
        },
        '/home': {
            component (resolve) {
                require(['./views/Home.vue'], resolve)
            }
        },
        '/user':{
            component (resolve) {
                require(['./views/User.vue'], resolve)
            }
        },
        '/collect':{
            component (resolve) {
                require(['./views/Collect.vue'], resolve)
            }
        },
        '/setting':{
            component (resolve) {
                require(['./views/Setting.vue'], resolve)
            }
        }
    });
}