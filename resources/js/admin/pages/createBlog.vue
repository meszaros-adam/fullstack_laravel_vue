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
            Role Management
            <Button @click="addModal = true" v-if="isWritePermitted"><Icon type="md-add"></Icon>Add role</Button
            >
          </p>
          <div class="_overflow _table_div">
            	<editor 
                ref="editor"
                autofocus
                holder-id="codex-editor"
                save-button-id="save-button"
                :init-data="initData"
                @save="onSave"
                :config="{
                    tools: {
                      header: require('@editorjs/header'),
                      list: require('@editorjs/list'),
                      code: require('@editorjs/code'),
                      inlineCode: require('@editorjs/inline-code'),
                      personality: require('@editorjs/personality'),
                      embed: require('@editorjs/embed'),
                      link: require('@editorjs/link'),
                      marker: require('@editorjs/marker'),
                      table: require('@editorjs/table'),
                      raw: require('@editorjs/raw'),
                      delimiter: require('@editorjs/delimiter'),
                      quote: require('@editorjs/quote'),
                      image: require('@editorjs/image'),
                      warning: require('@editorjs/warning'),
                      paragraph: require('@editorjs/paragraph'),
                      checklist: require('@editorjs/checklist'),
                    }
                }"

              />

		          <Button id="save-button" @click="save">Save the data</Button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>

export default {
  data() {
    return {
       config: {
        image: {
          // Like in https://github.com/editor-js/image#config-params
          field: 'image',
          types: 'image/*',
        },
      },
      initData: null,
      data: {
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
    onSave(response){
      console.log('response on save: ',response)
    },
    async save(){
      const res = await this.$refs.editor._data.state.editor.save()
      console.log('res is tis: ', res)
    }

  }
}
</script>
