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
                v-model="data.content"
                placeholder="Write some text"
              >
              </vue-editor>
            </div>
            <div class="space">
              <Button @click="save">Save</Button>
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
        content: "",
        title: ''
      },
      editorSettings: {
        modules: {
          imageDrop: true,
          resize: {
            modules: [ 'Resize', 'DisplaySize', 'Toolbar' ]
        }
        }
      }
    }
  },
  methods: {
    async add() {
      if (this.data.roleName.trim() == "")
        return this.error("Role name is required");
      this.isAdding = true;
      const res = await this.callApi("post", "app/create_role", this.data);
      if (res.status === 201) {
        this.roles.unshift(res.data);
        this.success("Role has been added succesfully");
        this.addModal = false;
        this.data.roleName = "";
      } else {
        if (res.status == 422) {
          if (res.data.errors.RoleName) {
            this.error(res.data.errors.RoleName[0]);
          }
        } else {
          this.swr();
        }
      }
      this.isAdding = false;
    },
    async save(){
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
