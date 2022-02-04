<template>
    <div>
       <div class="content">
			<div class="container-fluid">
				<!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
				<div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
					<p class="_title0">Category <Button @click="addModal=true"><Icon type="md-add"></Icon>Add category</Button></p> 
					<div class="_overflow _table_div">
						<table class="_table">
								<!-- TABLE TITLE -->
							<tr>
								<th>ID</th>
								<th>Icon image</th>
								<th>Category name</th>
								<th>Created at</th>
								<th>Action</th>
							</tr>
								<!-- TABLE TITLE -->


								<!-- ITEMS -->
							<tr v-for="(category, i) in categoryList" :key="i" v-if="categoryList.length">
								<td>{{category.id}}</td>
								<td class="table_image">
									<img :src="category.iconImage">
								</td>
								<td class="_table_name">{{category.categoryName}}</td>
								<td>{{category.created_at}}</td>
								<td>
									<Button @click="showEditModal(category, i)" type="info" size="small">Edit</Button>
									<Button @click="showDeleteModal(category, i)" type="error" size="small" :loading="category.isDeleting">Delete</Button>
								</td>
							</tr>

						</table>
					</div>
				</div>

				<!-- category adding modal -->
				<Modal v-model="addModal" title="Add category" :mask-closable="false" :closable="false" >

					 <Input v-model="data.categoryName" placeholder="Add category name" />

					 <Upload
					 	ref="uploads"
                        type="drag"
						:headers="{'x-csrf-token' : token, 'X-Requested-With' : 'XMLHttpRequest'}"
						:on-success="handleSuccess"
						:on-error="handleError"						
						:max-size="2048"
						:format="['jpg', 'jpeg', 'png']"
						:on-format-error="handleFormatError"						
						:on-exceeded-size="handleMaxSize"
                        action="/app/upload">
                        <div style="padding: 20px 0">
                            <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
                            <p>Click or drag files here to upload</p>
                        </div>
                    </Upload>

						<div class="demo-upload-list" v-if="data.iconImage">
								<img :src="`${data.iconImage}`">
								<div class="demo-upload-list-cover">
									<Icon type="ios-trash-outline" @click="deleteImage"></Icon>
								</div>
						</div>

					<div slot="footer">
						<Button type="default" @click="addModal=false ; deleteImage()">Close</Button>
						<Button type="primary" @click="addCategory" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Adding' : 'Add category'}}</Button>
					</div>
				</Modal>
				<!-- category adding modal -->

				<!-- category editing modal -->
				<Modal v-model="editModal" title="Edit category" :mask-closable="false" :closable="false" >

					 <Input v-model="editData.categoryName" placeholder="Edit category name" />

					 <Upload
					 	v-show="showEditUpload"
					 	ref="editDataUpload"
                        type="drag"
						:headers="{'x-csrf-token' : token, 'X-Requested-With' : 'XMLHttpRequest'}"
						:on-success="handleSuccess"
						:on-error="handleError"						
						:max-size="2048"
						:format="['jpg', 'jpeg', 'png']"
						:on-format-error="handleFormatError"						
						:on-exceeded-size="handleMaxSize"
                        action="/app/upload">
                        <div style="padding: 20px 0">
                            <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
                            <p>Click or drag files here to upload</p>
                        </div>
                    </Upload>

						<div class="demo-upload-list" v-if="editData.iconImage">
								<img :src="`${editData.iconImage}`">
								<div class="demo-upload-list-cover">
									<Icon type="ios-trash-outline" @click="deleteImage(false)"></Icon>
								</div>
						</div>

					<div slot="footer">
						<Button type="default" @click="closeEditModal">Close</Button>
						<Button type="primary" @click="editCategory" :disabled="isEditing" :loading="isEditing">{{isEditing ? 'Editing' : 'Edit category'}}</Button>
					</div>
				</Modal>
				<!-- category editing modal -->

				<!-- Delete alert modal -->
				<Modal v-model="deleteModal" width="360">
					<p slot="header" style="color:#f60;text-align:center">
						<Icon type="ios-information-circle"></Icon>
						<span>Delete confirmation</span>
					</p>
					<div style="text-align:center">
						<p>Are you sure you want to delete  category?</p>
	
					</div>
					<div slot="footer">
						<Button type="error" size="large" long  @click="deletecategory" :loading="isDeleting">Delete</Button>
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
				iconImage: '',
				categoryName: '',
			},
			addModal: false,
			editModal: false,
			isAdding: false,
			categoryList: [],
			editData: {
				iconImage: '',
				categoryName: '',
			},
			editIndex : -1,
			deleteModal: false,
			isDeleting: false,
			deleteItem: {
				id: ''
			},
			deleteIndex: -1,
			token: '',
			showEditUpload: false,
			isEditing: false,
			//for HandleSucess function.
			isEditingItem: false,

		}
	},
	methods:{
		handleSuccess (res, file){
			res = `/uploads/${res}`
			if(this.isEditingItem){
				this.editData.iconImage = res
			}
			this.data.iconImage = res
		},
		handleError (res, file){
			this.$Notice.warning({
				title: 'The file format is incorrect',
				desc: `${file.errors.file.legth ? file.errors.file[0] : 'Something went wrong'}`
				})
		},
		handleFormatError (file){
			this.$Notice.warning({
				title: 'The file format is incorrect',
				desc: 'File format of ' + file.name + ' is inccorrect, please select jpg or png.'
			})
		},
		handleMaxSize(file){
			this.$Notice.warning({
				title: 'Exceeding file size limit',
				desc: 'File ' + file.name + ' is too large, no more than 2M'
			})
		},
		async deleteImage(isAdd=true){
			let image
			if(!isAdd){//for editing
				this.showEditUpload = true
				image = this.editData.iconImage
				this.editData.iconImage = ''
				this.$refs.editDataUpload.clearFiles()
			}else{
				image = this.data.iconImage
				this.data.iconImage = ''
				this.$refs.uploads.clearFiles()
			}
			
			resp = await this.callApi('post', 'app/delete_image', {imageName: image})
			if(res.status!=200){
				this.data.iconImage = image
				this.swr()
			}
		},
		async addCategory(){
			if(this.data.categoryName.trim()=='') return this.error('Category name is required')
			if(this.data.iconImage.trim()=='') return this.error('Icon image is required')
			this.isAdding=true
			this.data.iconImage = `${this.data.iconImage}`
			const res = await this.callApi('post', 'app/create_category', this.data)
			if(res.status===201){
				this.categoryList.unshift(res.data)
				this.success('Category has been added succesfully')
				this.addModal = false
				this.data.categoryName=''
				this.data.iconImage=''
			}else{
				if(res.status==422){
					if(res.data.errors.categoryName){
					this.error(res.data.errors.categoryName[0])
					}
					if(res.data.errors.iconImage){
					this.error(res.data.errors.iconImage[0])
					}
				}
				else{
					this.swr()
				}
			}
			this.isAdding=false
			this.$refs.uploads.clearFiles()
		},
		async editCategory(){
			if(this.editData.categoryName.trim()=='') return this.error('Category name is required')
			if(this.editData.iconImage.trim()=='') return this.error('Icon image is required')
			this.isEditing = true
			const res = await this.callApi('post', 'app/edit_category', this.editData)
			if(res.status===200){
				this.categoryList[this.editIndex] = this.editData
				this.success('Tag has been edited succesfully')
				this.editModal = false
			}else{
				if(res.status==422){
					if(res.data.errors.categoryName){
					this.error(res.data.errors.categoryName[0])
					}
				}
				else{
					this.swr()
				}
			}
			this.isEditing = false
		},
		showEditModal(category, index){
			this.editData = category
			this.editModal = true
			this.isEditingItem = true
			this.editIndex = index
		},
		closeEditModal(){
			this.isEditingItem = false
			this.editModal = false
		}
	},
	async created(){
		const res = await this.callApi('get', 'app/get_category')
		if(res.status==200){
			this.categoryList = res.data
		}else{
			this.swr()
		}
		this.token=	window.Laravel.csrfToken
	}
}
</script>

<style>
	  .demo-upload-list{
        display: inline-block;
        width: 60px;
        height: 60px;
        text-align: center;
        line-height: 60px;
        border: 1px solid transparent;
        border-radius: 4px;
        overflow: hidden;
        background: #fff;
        position: relative;
        box-shadow: 0 1px 1px rgba(0,0,0,.2);
        margin-right: 4px;
    }
    .demo-upload-list img{
        width: 100%;
        height: 100%;
    }
    .demo-upload-list-cover{
        display: none;
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(0,0,0,.6);
    }
    .demo-upload-list:hover .demo-upload-list-cover{
        display: block;
    }
    .demo-upload-list-cover i{
        color: #fff;
        font-size: 20px;
        cursor: pointer;
        margin: 0 2px;
    }
	.table_image img{
		max-width: 40px;
		max-height: 40px;

	}
</style>
