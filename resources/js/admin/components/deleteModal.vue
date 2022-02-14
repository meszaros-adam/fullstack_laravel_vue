<template>
    <div>
        <!-- Delete alert modal -->
				<Modal 
                :value="getDeleteModalObj.showDeleteModal" 
                :mask-closable="false"
                width="360"
				@on-cancel="cancel"
				>
				

					<p slot="header" style="color:#f60;text-align:center">
						<Icon type="ios-information-circle"></Icon>
						<span>Delete confirmation</span>
					</p>
					<div style="text-align:center">
						<p>Are you sure you want to delete tag?</p>
	
					</div>
					<div slot="footer">
						<Button type="error" size="large" long  @click="deleteTag" :loading="isDeleting">Delete</Button>
					</div>
				</Modal>
				<!-- Delete alert modal -->
    </div>
</template>

<script>
import{mapGetters} from 'vuex'
export default{
    data(){
        return{
            isDeleting: false,
        }
    },
    methods: {
        async deleteTag(){ 

				this.isDeleting=true
				const res = await this.callApi('post', this.getDeleteModalObj.deleteUrl , this.getDeleteModalObj.data)
				if(res.status===200){
					this.success('Tag has been deleted successfully!')
                    this.$store.commit('setDeleteModal', true)
				}else{
					this.swr()
				}
				this.deleteModal= false
				this.isDeleting=false
		},
		//set delete modal object to default, after cancel 
		cancel(){
			const deleteModalObj = {
				showDeleteModal: false,
            	deleteUrl: '',
            	data: null,
            	deletingIndex: -1,
            	isDeleted: false, 
			}
			this.$store.commit('setDeletingModalObj', deleteModalObj)
		},
    },
    computed:{
        ...mapGetters(['getDeleteModalObj'])
    }
} 
</script>
