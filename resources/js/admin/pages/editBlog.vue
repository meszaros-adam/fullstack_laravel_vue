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
            Create Blog
          </p>
          <div class="_overflow _table_div">
            <div class="space">
              <Input v-model="data.title" placeholder="Add title"></Input>
            </div>
            <div class="space">
              <vue-editor
                ref="editor"
                id="editor"
                :editorOptions="editorSettings"
                useCustomImageHandler
                @image-added="handleImageAdded"
                v-model="data.post"
                placeholder="Write your post"
              >
              </vue-editor>
            </div>
            <div class="space">
              <Input v-model="data.post_excerpt" type="textarea"  :autosize="{minRows: 2,maxRows: 5}" placeholder="Add Post Excerpt"/>
            </div>
            <div class="space">
                <Select v-model="data.category_id" placeholder="Select category" filterable multiple>
							<Option :value="category.id" v-for="(category, i) in categoryList" :key="i" >{{category.categoryName}}</Option> 
                </Select>
            </div>
            <div class="space">
                <Select v-model="data.tag_id" placeholder="Select tag" filterable multiple>
							<Option :value="tag.id" v-for="(tag, i) in tags" :key="i" >{{tag.tagName}}</Option> 
                </Select>
            </div>
             <div class="space">
              <Input v-model="data.metaDescription" type="textarea"  :autosize="{minRows: 2,maxRows: 5}" placeholder="Add meta description"/>
            </div>
            <div class="space">
              <Button @click="edit" :loading="isEditing" :disabled="isEditing">{{isEditing ? 'Editing blog' : 'Save'}}</Button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { VueEditor, Quill } from "vue2-editor";
import { ImageDrop } from "quill-image-drop-module";
import QuillResize from 'quill-resize-module';

Quill.register("modules/imageDrop", ImageDrop);
Quill.register("modules/resize", QuillResize);


export default {
  components: {
    VueEditor
  },
  data() {
    return {
      data:{
        id: "",
        post: "",
        title: '',
         category_id: [],
        post_excerpt: '',
        metaDescription: '',
        tag_id: [],

      },
      isEditing: false,
      categoryList: [],
      tags: [],
      editorSettings: {
        modules: {
          imageDrop: true,
          resize: {
            modules: [ 'Resize', 'DisplaySize', 'Toolbar' ]
        }
        }
      },
    }
  },
  methods: {
    async edit() {
      if (this.data.title.trim() == "") return this.error("Title is required")
      if (this.data.post.trim() == "") return this.error("Post is required")
      if (this.data.post_excerpt.trim() == "") return this.error("Post excerpt is required")
      if (this.data.metaDescription.trim() == "") return this.error("Meta description is required")
      if (this.data.category_id.length == 0) return this.error("Category is required")
      if (this.data.tag_id.length == 0) return this.error("Tag is required")
      this.isEditing = true;
      const res = await this.callApi("post", "/app/edit_blog", this.data)
      if (res.status==200){
        this.success('Blog has been edited succesfully')
        //redirect...
        this.$router.push('/blogs')
      }else{
        this.swr()
      }
      this.isEditing = false;
    },
    async handleImageAdded (file, Editor, cursorLocation, resetUploader) {
      // An example of using FormData
      // NOTE: Your key could be different such as:
      // formData.append('file', file)

      var formData = new FormData();
      formData.append("image", file);
      const res = await this.callApi('post','/app/upload_editor_pic', formData )
      console.log(res)
      if(res.status == 200) {
        const url = `/uploads/${res.data}`
        Editor.insertEmbed(cursorLocation, "image", url)
        resetUploader()
      }
      else{
        this.swr()
      }
    },
  },
  async created(){
    const id = this.$route.params.id
      const [blog ,cat, tag] = await Promise.all([
        this.callApi('get', `/app/blog_single/${id}`),
        this.callApi('get', '/app/get_category'),
        this.callApi('get', '/app/get_tags'),
      ])
      if(blog.status == 200){
        this.data.id = blog.data[0].id
        this.data.post = blog.data[0].post
        this.data.title = blog.data[0].title
        this.data.post_excerpt = blog.data[0].post_excerpt
        this.data.metaDescription = blog.data[0].metaDescription
        for(let tag of blog.data[0].tags){
          this.data.tag_id.push(tag.id)
        }
        for(let cat of blog.data[0].categories){
          this.data.category_id.push(cat.id)
        }
      }
      else {
        this.swr();
      }
      if (cat.status == 200  ) {
      this.categoryList = cat.data
      }
      else {
        this.swr();
      }
      if(tag.status == 200 ){
        this.tags = tag.data
      }else {
        this.swr();
      }
  }
}
</script>
