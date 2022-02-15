<template>
    <div>
        <!-- Delete alert modal -->
				<Modal 
                :value="getDeleteModalObj.showDeleteModal" 
                :mask-closable="false"
				:closable="false"
                width="360"
				>
				

					<p slot="header" style="color:#f60;text-align:center">
						<Icon type="ios-information-circle"></Icon>
						<span>Delete confirmation</span>
					</p>
					<div style="text-align:center">
						<p>Are you sure you want to delete {{this.getDeleteModalObj.objectType}}?</p>
	
					</div>
					<div slot="footer">
						<Button type="default" size="large" @click="cancel">Cancel</Button>
						<Button type="error" size="large" @click="deleteTag" :loading="isDeleting">Delete</Button>
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
				const deletingModalObj = {
					showDeleteModal: false,
					deleteUrl: '',
					data: null,
					deletingIndex: this.getDeleteModalObj.deletingIndex,
					isDeleted: true, 
				}
				this.isDeleting=true
				const res = await this.callApi('post', this.getDeleteModalObj.deleteUrl , this.getDeleteModalObj.data)
				if(res.status===200){
					this.success('Tag has been deleted successfully!')
                    this.$store.commit('setDeletingModalObj', deletingModalObj)
					this.isDeleting=false
				}else{
					this.swr()
					this.isDeleting=false
				}
		},
		//set deleting modal object to default, after cancel 
		cancel(){ 
			const deletingModalObj = {
				showDeleteModal: false,
            	deleteUrl: '',
            	data: null,
            	deletingIndex: -1,
            	isDeleted: false, 
			}
			this.$store.commit('setDeletingModalObj', deletingModalObj)
		},
    },
    computed:{
        ...mapGetters(['getDeleteModalObj'])
    }
} 
</script>
