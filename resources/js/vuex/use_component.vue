<template>
    <div class="content">
		<div class="container-fluid">
            <h1>I will show you how oll other components react to changes</h1>
            <h2>Master component counter is: {{counter}}</h2>
            <componentA/>
            <componentB/>
            <componentC/>
            <Button type="info" @click="changeCounter"> change counter state </Button>
        </div>
    </div>
</template>

<script>
    import componentA from './component_A'
    import componentB from './component_B'
    import componentC from './component_C'
    import {mapGetters} from 'vuex'
    export default{
        data(){
            return{
                localVar: 'some value'
            }
        },
        computed: {
            ...mapGetters({
                counter: 'getCounter'
            })
        },
        components: {
            componentA,
            componentB,
            componentC,
        },
        methods: {
            changeCounter(){
                this.$store.dispatch('changeCounterAction', 1)
                //this.$store.commit('changeTheCounter', 1)
            },
            runSomethingWhenCounterChange(){
                console.log('i am running based on each changes happening')
            }
        },
        watch : {
            counter(value){
                console.log('counter is changing', value)
                this.runSomethingWhenCounterChange()
                console.log ('local var:' , this.localVar)
            }
        }
    }
</script>
