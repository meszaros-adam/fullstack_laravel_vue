<template>
    <div class="content">
		<div class="container-fluid">
            <h1>I will show you how oll other components react to changes</h1>
            <h2>Master component counter is: {{$store.state.counter}}</h2>
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
    export default{

        components: {
            componentA,
            componentB,
            componentC,
        },
        methods: {
            changeCounter(){
                this.$store.commit('changeTheCounter', 1)
            },
        } 
    }
</script>
