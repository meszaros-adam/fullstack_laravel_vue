<template>
    <div class="container-fluid">
        <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20 col-md-4">
                <div class="login_header">
                    <h2>Login to the dashboard:</h2>
                </div>
                <div class="space">
                    <label>Email: </label>
                    <Input v-model="data.email" type="email" placeholder="Email"/>
                </div>
                <div class="space">
                    <label>Password: </label>
                    <Input  v-model="data.password" type="password" placeholder="Password"/>
                </div>
                <div class="login_footer">
                    <Button type="primary" @click="login" :disabled="isLogging" :loading="isLogging">{{isLogging ? 'Logging in' : 'Login' }}</Button>
                </div>
        </div>
    </div>
</template>

<script>
export default {
    data(){
        return{
            data: {
                email: '',
                password: '',
            },
            isLogging: false,
        }
    },
    methods: {
        async login(){
            if(this.data.email.trim()=='') return this.error('Email is required');
            if(this.data.password.trim()=='') return this.error('Password is required');
            if(this.data.password.length < 5) return this.error('Password must be at least 6 characters long');
            this.isLogging = true
            const res = await this.callApi('post', 'app/admin_login', this.data)
            if(res.status==200){
                this.success(res.data.msg)
                window.location='/'
            }
            else{
                if(res.status==401){
                    this.info(res.data.msg)
                }
                else{
                    for(let i in res.data.errors)
                    this.swr(res.data.errors[i][0])
                }
            }
            this.isLogging = false
        }
    }
}
</script>

<style scoped>
    ._1adminOverveiw_table_recent{
        margin: 0 auto;
        margin-top: 40px;
    }
    .login_footer{
        text-align: right;
    }
    .login_header h2{
        font-family: monospace;
        margin-bottom: 30px;
        border-bottom: 2px dashed black;
        color: black;
    }
</style>