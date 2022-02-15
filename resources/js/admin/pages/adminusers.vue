<template>
    <div>
       <div class="content">
			<div class="container-fluid">
				<!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
				<div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
					<p class="_title0">Admin users<Button @click="addModal=true"><Icon type="md-add"></Icon>Add user</Button></p> 
					<div class="_overflow _table_div">
						<table class="_table">
								<!-- TABLE TITLE -->
							<tr>
								<th>ID</th>
								<th>Name</th>
								<th>Email</th>
                                <th>User type</th>
                                <th>Created at</th>
								<th>Action</th>
							</tr>
								<!-- TABLE TITLE -->


								<!-- ITEMS -->
							<tr v-for="(user, i) in users" :key="i" v-if="users.length">
								<td>{{user.id}}</td>
								<td class="_table_name">{{user.fullName}}</td>
                                <td>{{user.email}}</td>
                                <td>{{user.userType}}</td>
                                <td>{{user.created_at}}</td>
								<td>
									<Button @click="showEditModal(user, i)" type="info" size="small">Edit</Button>
									<Button @click="showDeleteModal(user, i)" type="error" size="small" :loading="isDeleting">Delete</Button>
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
                        <Select v-model="data.userType" placeholder="Admin type">
                                <Option  value="Admin">Admin</Option>
                                <Option  value="Editor">Editor</Option>
                        </Select>
                    </div>
					 
                     
                     
					
					<div slot="footer">
						<Button type="default" @click="addModal=false">Close</Button>
						<Button type="primary" @click="addUser()" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Adding' : 'Add admin'}}</Button>
					</div>
				</Modal>
				<!-- Tag adding modal -->

				<!-- Tag editing modal -->
				<Modal v-model="editModal" title="Edit tag" :mask-closable="false" :closable="false" >

					 <Input v-model="editData.tagName" />
					
					<div slot="footer">
						<Button type="default" @click="editModal=false">Close</Button>
						<Button type="primary" @click="editTag" :disabled="isEditing" :loading="isEditing">{{isEditing ? 'Editing' : 'Edit tag'}}</Button>
					</div>
				</Modal>
				<!-- Tag editing modal -->

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
                userType: 'Admin',
			},
			addModal: false,
			editModal: false,
			isAdding: false,
			users: [],
			isEditing: false,
			editData: {
				fullName: '',
                email: '',
                password: '',
                userType: 'Admin',
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
		async addUser(){
			if(this.data.fullName.trim()=='') return this.error('Full name name is required')
            if(this.data.email.trim()=='') return this.error('Email is required')
            if(this.data.password.trim()=='') return this.error('Password is required')
            if(this.data.userType.trim()=='') return this.error('Admin type is required')

            this.isAdding=true
			const res = await this.callApi('post', 'app/create_user', this.data)
			if(res.status===201){
				this.users.unshift(res.data)
				this.success('Admin user has been added succesfully')
				this.addModal = false
				this.data.fullName=''
                this.data.email= ''
                this.data.password= ''
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
		async editTag(){
			if(this.editData.tagName.trim()=='') return this.error('Tag name is required')
			this.isEditing = true
			const res = await this.callApi('post', 'app/edit_tag', this.editData)
			if(res.status===200){
				this.tags[this.editIndex].tagName = this.editData.tagName
				this.success('Tag has been edited succesfully')
				this.editModal = false
			}else{
				if(res.status==422){
					if(res.data.errors.tagName){
					this.error(res.data.errors.tagName[0])
					}
				}
				else{
					this.swr()
				}
			}
			this.isEditing = false
		},
		showEditModal(tag, i){
			const obj ={
				tagName: tag.tagName,
				id: tag.id,
			}
			this.editData = obj
			this.editModal = true
			this.editIndex = i
		},
		showDeleteModal(tag, i){
			const deleteModalObj = {
				showDeleteModal: true,
				deleteUrl: 'app/delete_tag',
				data: tag,
				deletingIndex: i,
				objectType: 'tag',
			}
			this.$store.commit('setDeletingModalObj', deleteModalObj)
		},
	},
	async created(){
		const res = await this.callApi('get', 'app/get_users')
		if(res.status==200){
			this.users = res.data
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
				this.tags.splice(obj.deletingIndex, 1)
			}
        }
    },
	
}
</script>