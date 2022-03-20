<template>
  <div>
    <div class="content">
      <div class="container-fluid">
        <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
        <div
          class="
            _1adminOverveiw_table_recent
            _box_shadow
            _border_radious
            _mar_b30
            _p20
          "
        >
          <p class="_title0">
            Role Management
            <Select v-model="data.id" placeholder="Select admin type" style="width:300px" @on-change="changeAdmin">
				<Option :value="role.id" v-for="(role, i) in roles" :key="i" >{{role.roleName}}</Option> 
            </Select>
          </p>
          <div class="_overflow _table_div">
            <table class="_table">
              <!-- TABLE TITLE -->
              <tr>
                <th>Resource name</th>
                <th>Read</th>
                <th>Write</th>
                <th>Update</th>
                <th>Delete</th>
              </tr>
              <!-- TABLE TITLE -->

              <!-- ITEMS -->
              <tr v-for="(r,i) in resources" :key="i" v-if="resources">
                <td>{{r.resourceName}}</td>
                <td><Checkbox v-model="r.read"></Checkbox></td>
                <td><Checkbox v-model="r.write"></Checkbox></td>
                <td><Checkbox v-model="r.update"></Checkbox></td>
                <td><Checkbox v-model="r.delete"></Checkbox></td>
              </tr>
              <div class="center-button">
                <Button type="primary" :loading="isSending" :disabled="isSending" @click="assignRoles">Assign</Button>
              </div>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      data: {
        id: null,
      },
      isSending: false,
      roles: [],
      resources: null,
      defaultResources: [{resourceName: 'Tags', read: false, write: false, update: false, delete: false,name: 'tags'},
                  {resourceName: 'Category', read: false, write: false, update: false, delete: false,name: 'category'},
                  {resourceName: 'Adminuser', read: false, write: false, update: false, delete: false,name: 'adminusers'},
                  {resourceName: 'Role', read: false, write: false, update: false, delete: false,name: 'role'},
                  {resourceName: 'AssignRole', read: false, write: false, update: false, delete: false,name: 'assign-role'},
                  {resourceName: 'Home', read: false, write: false, update: false, delete: false,name: '/'}
                  ],
    };
  },
  methods: {
    async assignRoles(){
      if(this.data.id==null) return this.info('Please choose role!')
      let data = JSON.stringify(this.resources)
      const res = await this.callApi('post','app/assign_role',{'permission' :data, 'id': this.data.id})
      if(res.status==200){
        this.success('Role has been assigned succesfully!')
        let index = this.roles.findIndex(role => role.id ==this.data.id)
        this.roles[index].permission = data

      }
      else{
        this.swr()
      }
    },
    changeAdmin(){
    let index = this.roles.findIndex(role => role.id == this.data.id)
    let permission = this.roles[index].permission
    if(!permission){
      this.resources = this.defaultResources
    }else{
      this.resources = JSON.parse(permission)
    }
  }
  },
  async created() {
    const res = await this.callApi("get", "app/get_roles")
    if (res.status == 200) {
      this.roles = res.data
      if(res.data.length){
        this.data.id 
      }
    } else {
      this.swr()
    }
  },
};
</script>
