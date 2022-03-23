require('./bootstrap')
import Vue from 'vue'
import router from './router'
import store from './store'
import ViewUI from 'view-design'
import 'view-design/dist/styles/iview.css'
import common from './common'
import Editor from 'vue-editor-js/src/index'


Vue.use(ViewUI)
Vue.use(Editor)

Vue.mixin(common)
Vue.component('mainapp', require('./components/mainapp.vue').default)

const app = new Vue({
    el: '#app',
    router,
    store
})
