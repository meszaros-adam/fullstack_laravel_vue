<template>
    <div>
       <div class="content">
			<div class="container-fluid">
				<!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
				<div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
					<p class="_title0">Blogs <Button @click="$router.push('create-blog')" v-if="isWritePermitted"><Icon type="md-add"></Icon>Add Blog</Button></p> 
					<div class="_overflow _table_div">
						<table class="_table">
								<!-- TABLE TITLE -->
							<tr>
								<th>ID</th>
								<th>Title</th>
								<th>Categories</th>
								<th>Tags</th>
                                <th>Views</th>
                                <th>Action</th>
							</tr>
								<!-- TABLE TITLE -->


								<!-- ITEMS -->
							<tr v-for="(blog, i) in blogs" :key="i" v-if="blogs.length">
								<td>{{blog.id}}</td>
								<td class="_table_name">{{blog.title}}</td>
								<td> <span v-for="(category, c) in blog.categories" :key="c"  v-if="blog.categories.length"><Tag type="border">{{category.categoryName}}</Tag></span></td>
                                <td> <span v-for="(tag, t) in blog.tags" :key="t" v-if="blog.tags.length"><Tag type="border">{{tag.tagName}}</Tag></span></td>
                                <td>{{blog.views}}</td>
								<td>
                                    <Button type="info" size="small">Visit Blog</Button>
									<Button @click="showEditModal(blog, i)" v-if="isUpdatePermitted" type="info" size="small">Edit</Button>
									<Button @click="showDeleteModal(blog, i)" v-if="isDeletePermitted" type="error" size="small">Delete</Button>
								</td>
							</tr>

						</table>
					</div>
				</div>

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
            blogs: [],
			isAdding: false,
			tags: [],
            index: -1,
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
		showDeleteModal(blog, i){
			const deleteModalObj = {
				showDeleteModal: true,
				deleteUrl: 'app/delete_blog',
				data: {id: blog.id},
				deletingIndex: i,
                msg: 'Are you sure you want to delete this blog?',
                successMsg: 'Blog has been deleted succesfully',
			}
			this.$store.commit('setDeletingModalObj', deleteModalObj)
		},
	},
	async created(){
		const res = await this.callApi('get', 'app/blogsdata')
		if(res.status==200){
			this.blogs = res.data
		}else{
			this.swr()
		}
	},
	computed: {
		...mapGetters([
			'getDeleteModalObj'
		]),
	},
	watch: {
        getDeleteModalObj(obj){
            if(obj.isDeleted){
				this.blogs.splice(obj.deletingIndex, 1)
			}
        }
    },
}
</script>
