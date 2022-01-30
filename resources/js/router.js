import Vue from 'vue'
import Router from 'vue-router'
import firstPage from './components/pages/my-first-vue-page'
import newRoute from './components/pages/new-route'
import hooks from './components/pages/basic/hooks'
import methods from './components/pages/basic/methods'

//admin project pages
import home from './components/home'
import tags from './admin/pages/tags'
import category from './admin/pages/category'

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
]

export default new Router({
    mode:'history',
    routes
})