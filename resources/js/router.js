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
import role from './admin/pages/role'
import assignRole from './admin/pages/assignRole'
import createBlog from './admin/pages/createBlog'


Vue.use(Router)

const routes = [
    //project routes
    {
        path:'/',
        component: home,
        name: 'home'
    },
    {
        path:'/tags',
        component: tags,
        name: 'tags'
    },
    {
        path:'/category',
        component: category,
        name: 'category'
    },
    {
        path:'/adminusers',
        component: adminusers,
        name: 'adminusers'
    },   
    {
        path:'/login',
        component: login,
        name: 'login'
    },   
    {
        path:'/role',
        component: role,
        name: 'role'
    },
    {
        path:'/assign-role',
        component: assignRole,
        name: 'assign-role'
    },    
    {
        path:'/create-blog',
        component: createBlog,
        name: 'create-blog'
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