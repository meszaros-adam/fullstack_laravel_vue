import Vue from 'vue'
import Router from 'vue-router'
import firstPage from './components/pages/my-first-vue-page'
import newRoute from './components/pages/new-route'
import hooks from './components/pages/basic/hooks'
import methods from './components/pages/basic/methods'
import use_component from './vuex/use_component'

//admin project pages
import home from './components/home'
import tags from './admin/pages/tags'
import category from './admin/pages/category'
import adminusers from './admin/pages/adminusers'
import login from './admin/pages/login'


Vue.use(Router)

const routes = [
    //project routes
    {
        path:'/',
        component: home
    },
    {
        path:'/tags',
        component: tags
    },
    {
        path:'/category',
        component: category
    },
    {
        path:'/adminusers',
        component: adminusers
    },   
    {
        path:'/login',
        component: login
    },   
  
    //basic tutorial routes
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
    },
    //more basics
    {
        path: '/methods',
        component: methods
    },
    {
        path:'/testvuex',
        component: use_component
    },
]

export default new Router({
    mode:'history',
    routes
})