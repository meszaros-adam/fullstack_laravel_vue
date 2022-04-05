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
              <Button @click="add" :loading="isAdding" :disabled="isAdding">{{isAdding ? 'Saving blog' : 'Save'}}</Button>
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
        post: "",
        title: '',
        category_id: [],
        post_excerpt: '',
        metaDescription: '',
        tag_id: [],

      },
      isAdding: false,
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
    async add() {
      if (this.data.title.trim() == "") return this.error("Title is required")
      if (this.data.post.trim() == "") return this.error("Post is required")
      if (this.data.post_excerpt.trim() == "") return this.error("Post excerpt is required")
      if (this.data.metaDescription.trim() == "") return this.error("Meta description is required")
      if (this.data.category_id.length == 0) return this.error("Category is required")
      if (this.data.tag_id.length == 0) return this.error("Tag is required")
      this.isAdding = true;
      const res = await this.callApi("post", "app/create_blog", this.data)
      if (res.status==200){
        this.success('Blog has been created succesfully')
        //redirect...
        this.$router.push('/blogs')
      }else{
        this.swr()
      }
      this.isAdding = false;
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
      const [cat, tag] = await Promise.all([
        this.callApi('get', 'app/get_category'),
        this.callApi('get', 'app/get_tags'),
      ])
      if (cat.status == 200  ) {
      this.categoryList = cat.data;
      }
      if(tag.status == 200 ){
        this.tags = tag.data;
      }else {
        this.swr();
      }
  }
}
</script>
