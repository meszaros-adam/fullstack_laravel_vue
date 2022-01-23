import Vue from 'vue'
import Router from 'vue-router'
import firstPage from './components/pages/my-first-vue-page'
import newRoute from './components/pages/new-route'
import hooks from './components/pages/basic/hooks'

Vue.use(Router)

const routes = [
    {
        path:'/first-page',
        component: firstPage
    },
    {
        path:'/new-route',
        component: newRoute
    },

    //vue hooks
    {
        path: '/hooks',
        component: hooks
    }
]

export default new Router({
    mode:'history',
    routes
})