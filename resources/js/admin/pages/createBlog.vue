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
            <Button @click="addModal = true" v-if="isWritePermitted"><Icon type="md-add"></Icon>Add Blog</Button
            >
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
              <Button @click="add" :loading="isAdding" :disabled="isAdding">Save</Button>
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
        title: ''
      },
      editorSettings: {
        modules: {
          imageDrop: true,
          resize: {
            modules: [ 'Resize', 'DisplaySize', 'Toolbar' ]
        }
        }
      },
      isAdding: false,
    }
  },
  methods: {
    async add() {
      if (this.data.title.trim() == "") return this.error("Title is required")
      if (this.data.post.trim() == "") return this.error("Post is required")
      this.isAdding = true;
      const res = await this.callApi("post", "app/create_blog", this.data)
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
    }
  }
}
</script>
