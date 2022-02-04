<template>
    <div>
       <div class="content">
			<div class="container-fluid">
				<!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
				<div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
					<p class="_title0">Tags <Button @click="addModal=true"><Icon type="md-add"></Icon>Add tag</Button></p> 
					<div class="_overflow _table_div">
						<table class="_table">
								<!-- TABLE TITLE -->
							<tr>
								<th>ID</th>
								<th>Tag name</th>
								<th>Created at</th>
								<th>Action</th>
							</tr>
								<!-- TABLE TITLE -->


								<!-- ITEMS -->
							<tr v-for="(tag, i) in tags" :key="i" v-if="tags.length">
								<td>{{tag.id}}</td>
								<td class="_table_name">{{tag.tagName}}</td>
								<td>{{tag.created_at}}</td>
								<td>
									<Button @click="showEditModal(tag, i)" type="info" size="small">Edit</Button>
									<Button @click="showDeleteModal(tag, i)" type="error" size="small" :loading="tag.isDeleting">Delete</Button>
								</td>
							</tr>

						</table>
					</div>
				</div>

				<!-- Tag adding modal -->
				<Modal v-model="addModal" title="Add tag" :mask-closable="false" :closable="false" >
					 <Input v-model="data.tagName" placeholder="Add tag name" />
					
					<div slot="footer">
						<Button type="default" @click="addModal=false">Close</Button>
						<Button type="primary" @click="addTag" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Adding' : 'Add tag'}}</Button>
					</div>
				</Modal>
				<!-- Tag adding modal -->

				<!-- Tag editing modal -->
				<Modal v-model="editModal" title="Edit tag" :mask-closable="false" :closable="false" >

					 <Input v-model="editData.tagName" placeholder="Edit tag name" />
					
					<div slot="footer">
						<Button type="default" @click="editModal=false">Close</Button>
						<Button type="primary" @click="editTag" :disabled="isEditing" :loading="isEditing">{{isEditing ? 'Editing' : 'Edit tag'}}</Button>
					</div>
				</Modal>
				<!-- Tag editing modal -->

				<!-- Delete alert modal -->
				<Modal v-model="deleteModal" width="360">
					<p slot="header" style="color:#f60;text-align:center">
						<Icon type="ios-information-circle"></Icon>
						<span>Delete confirmation</span>
					</p>
					<div style="text-align:center">
						<p>Are you sure you want to delete  tag?</p>
	
					</div>
					<div slot="footer">
						<Button type="error" size="large" long  @click="deleteTag" :loading="isDeleting">Delete</Button>
					</div>
				</Modal>
				<!-- Delete alert modal -->

				
			</div>
		</div>
    </div>
</template>

<script>
export default {
	data(){
		return{
			data:{
				tagName: ''
			},
			addModal: false,
			editModal: false,
			isAdding: false,
			tags: [],
			isEditing: false,
			editData: {
				tagName:''
			},
			index : -1,
			deleteModal: false,
			isDeleting: false,
			deleteItem: {
				id: ''
			},
			deletingIndex: -1
		}
	},
	methods:{
		async addTag(){
			this.isAdding=true
			if(this.data.tagName.trim()==''){
			 return this.error('Tag name is required')
			}
			const res = await this.callApi('post', 'app/create_tag', this.data)
			if(res.status===201){
				this.tags.unshift(res.data)
				this.success('Tag has been added succesfully')
				this.addModal = false
				this.data.tagName=''
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
			this.isAdding=false
		},
		async editTag(){
			if(this.editData.tagName.trim()=='') return this.error('Tag name is required')
			this.isEditing = true
			const res = await this.callApi('post', 'app/edit_tag', this.editData)
			if(res.status===200){
				this.tags[this.index].tagName = this.editData.tagName
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
			this.editData = tag
			this.editModal = true
			this.index = i
		},
		async deleteTag(){
				this.isDeleting=true
				const res = await this.callApi('post', 'app/delete_tag', this.deleteItem)
				if(res.status===200){
					this.tags.splice(this.deletingIndex, 1)
					this.success('Tag has been deleted successfully!')
				}else{
					this.swr()
				}
				this.deleteModal= false
				this.isDeleting=false
		},
		showDeleteModal(tag, i){
			let obj = {
				id : tag.id,
				tagName : tag.tagName,
			}
			this.deleteModal= true
			this.deleteItem=obj
			this.deletingIndex = i
		},
	},
	async created(){
		const res = await this.callApi('get', 'app/get_tags')
		if(res.status==200){
			this.tags = res.data
		}else{
			this.swr()
		}
	}
	
}
</script>
