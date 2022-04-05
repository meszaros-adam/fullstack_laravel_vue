<template>
    <div>
       <div class="content">
			<div class="container-fluid">
				<!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
				<div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
					<p class="_title0">Admin users<Button @click="addModal=true" v-if="isWritePermitted"><Icon type="md-add"></Icon>Add user</Button></p> 
					<div class="_overflow _table_div">
						<table class="_table">
								<!-- TABLE TITLE -->
							<tr>
								<th>ID</th>
								<th>Name</th>
								<th>Email</th>
                                <th>Role ID</th>
                                <th>Created at</th>
								<th>Action</th>
							</tr>
								<!-- TABLE TITLE -->


								<!-- ITEMS -->
							<tr v-for="(user, i) in users" :key="i">
								<td>{{user.id}}</td>
								<td class="_table_name">{{user.fullName}}</td>
                                <td>{{user.email}}</td>
                                <td>{{user.role_id}}</td>
                                <td>{{user.created_at}}</td>
								<td>
									<Button @click="showEditModal(user, i)" type="info" size="small" v-if="isUpdatePermitted">Edit</Button>
									<Button @click="showDeleteModal(user, i)" type="error" size="small" :loading="isDeleting" v-if="isDeletePermitted">Delete</Button>
								</td>
							</tr>

						</table>
					</div>
				</div>

				<!-- User adding modal -->
				<Modal v-model="addModal" title="Add user" :mask-closable="false" :closable="false" >

                    <div class="space">
                        <Input type="text" v-model="data.fullName" placeholder="Full name"/>
                    </div>
                    <div class="space">
                        <Input type="email" v-model="data.email" placeholder="Email"/>
                    </div>
                    <div class="space">
                        <Input type="password" v-model="data.password" placeholder="Password"/>
                    </div>
                    <div class="space">
                        <Select v-model="data.role_id" placeholder="Select role">
								<Option :value="role.id" v-for="(role, i) in roles" :key="i" >{{role.roleName}}</Option> 
                        </Select>
                    </div>
					 
                     
                     
					
					<div slot="footer">
						<Button type="default" @click="addModal=false">Close</Button>
						<Button type="primary" @click="add()" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Adding' : 'Add admin'}}</Button>
					</div>
				</Modal>
				<!-- User adding modal -->

				<!-- User editing modal -->
				<Modal v-model="editModal" title="Edit user" :mask-closable="false" :closable="false" >

					 <div class="space">
                        <Input type="text" v-model="editData.fullName" placeholder="Full name"/>
                    </div>
                    <div class="space">
                        <Input type="email" v-model="editData.email" placeholder="Email"/>
                    </div>
                    <div class="space">
                        <Input type="password" v-model="editData.password" placeholder="Password"/>
                    </div>
                    <div class="space">
                        <Select v-model="editData.role_id" placeholder="Select role">
							<Option :value="role.id" v-for="(role, i) in roles" :key="i" >{{role.roleName}}</Option> 
                        </Select>
                    </div>
					
					<div slot="footer">
						<Button type="default" @click="editModal=false">Close</Button>
						<Button type="primary" @click="edit" :disabled="isEditing" :loading="isEditing">{{isEditing ? 'Editing' : 'Edit user'}}</Button>
					</div>
				</Modal>
				<!-- User editing modal -->

				<deleteModal/>

			</div>
		</div>
    </div>
</template>

<script>
import deleteModal from '../components/deleteModal'
import {mapGetters} from 'vuex'

export default {
	data(){
		return{
			data:{
				fullName: '',
                email: '',
                password: '',
                role_id: null,
			},
			addModal: false,
			editModal: false,
			isAdding: false,
			users: [],
			roles: [],
			isEditing: false,
			editData: {
				fullName: '',
                email: '',
                password: '',
                role_id: null,
			},
			editIndex : -1,
			deleteModal: false,
			isDeleting: false,
			deleteItem: {
				id: ''
			},
			deletingIndex: -1
		}
	},
	components: {
		deleteModal,
	},
	methods:{
		async add(){
			if(this.data.fullName.trim()=='') return this.error('Full name name is required')
            if(this.data.email.trim()=='') return this.error('Email is required')
            if(this.data.password.trim()=='') return this.error('Password is required')
            if(!this.data.role_id) return this.error('Role is required')

            this.isAdding=true
			const res = await this.callApi('post', 'app/create_user', this.data)
			if(res.status===201){
				this.users.unshift(res.data)
				this.success('Admin user has been added succesfully')
				this.addModal = false
				this.data.fullName=''
                this.data.email= ''
                this.data.password= ''
				this.role_id= ''
			}else{
				if(res.status==422){
                    for(let i in res.data.errors){
                        this.error(res.data.errors[i][0])
                    }
                }
				else{
					this.swr()
				}
			}
			this.isAdding=false
		},
		async edit(){
			if(this.editData.fullName.trim()=='') return this.error('Full name name is required')
            if(this.editData.email.trim()=='') return this.error('Email is required')
            if(!this.editData.role_id) return this.error('Role is required')

			this.isEditing = true
			const res = await this.callApi('post', 'app/edit_user', this.editData)
			if(res.status===200){
				this.users[this.editIndex] = this.editData
				this.success('User has been edited succesfully')
				this.editModal = false
			}else{
				if(res.status==422){
					for(let i in res.data.errors){
						this.error(res.data.errors[i][0])
					}
				}
				else{
					this.swr()
				}
			}
			this.isEditing = false
		},
		showEditModal(user, i){
			const obj ={
				id: user.id,
				fullName: user.fullName,
                email: user.email,
               	role_id: user.role_id,
			}
			this.editData = obj
			this.editModal = true
			this.editIndex = i
		},
		showDeleteModal(user, i){
			const deleteModalObj = {
				showDeleteModal: true,
				deleteUrl: 'app/delete_tag',
				data: {id: user.id},
				deletingIndex: i,
				msg: 'Are you sure you want to delete this user?',
                successMsg: 'User has been deleted succesfully',
			}
			this.$store.commit('setDeletingModalObj', deleteModalObj)
		},
	},
	async created(){

		const [res, resRole] = await Promise.all([
			this.callApi('get', 'app/get_users'),
			this.callApi('get', 'app/get_roles'),
		])

		if(res.status==200){
			this.users = res.data
		}else{
			this.swr()
		}

		if(res.status==200){
			this.roles = resRole.data
		}else{
			this.swr()
		}
	},
	computed: {
		...mapGetters([
			'getDeleteModalObj'
		])
	},
	watch: {
        getDeleteModalObj(obj){
            if(obj.isDeleted){
				this.users.splice(obj.deletingIndex, 1)
			}
        }
    },
	
}
</script>