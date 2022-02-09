import Vue from 'vue'
import vuex from 'vuex'

Vue.use(vuex)

export default new vuex.Store({
    state:{
        counter: 1000
    },
    mutations: {
        changeTheCounter(state, data){
            state .counter += data
        }
    }
})