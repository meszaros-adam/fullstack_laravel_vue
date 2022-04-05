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
            <table class="_table">
              <!-- TABLE TITLE -->
              <tr>
                <th>ID</th>
                <th>Role type</th>
                <th>Created at</th>
                <th>Action</th>
              </tr>
              <!-- TABLE TITLE -->

              <!-- ITEMS -->
              <tr v-for="(role, i) in roles" :key="i">
                <td>{{ role.id }}</td>
                <td class="_table_name">{{ role.roleName }}</td>
                <td>{{ role.created_at }}</td>
                <td>
                  <Button
                    @click="showEditModal(role, i)"
                    type="info"
                    size="small"
                    v-if="isUpdatePermitted"
                    >Edit</Button
                  >
                  <Button
                    @click="showDeleteModal(role, i)"
                    type="error"
                    size="small"
                    v-if="isDeletePermitted"
                    >Delete</Button
                  >
                </td>
              </tr>
            </table>
          </div>
        </div>

        <!-- Role adding modal -->
        <Modal
          v-model="addModal"
          title="Add role"
          :mask-closable="false"
          :closable="false"
        >
          <Input v-model="data.roleName" placeholder="Add role name" />
          

          <div slot="footer">
            <Button type="default" @click="addModal = false">Close</Button>
            <Button
              type="primary"
              @click="add"
              :disabled="isAdding"
              :loading="isAdding"
              >{{ isAdding ? "Adding" : "Add role" }}</Button
            >
          </div>
        </Modal>
        <!-- Role adding modal -->

        <!-- Role editing modal -->
        <Modal
          v-model="editModal"
          title="Edit role"
          :mask-closable="false"
          :closable="false"
        >
          <Input v-model="editData.roleName" />

          <div slot="footer">
            <Button type="default" @click="editModal = false">Close</Button>
            <Button
              type="primary"
              @click="edit"
              :disabled="isEditing"
              :loading="isEditing"
              >{{ isEditing ? "Editing" : "Edit role" }}</Button
            >
          </div>
        </Modal>
        <!-- Role editing modal -->

        <deleteModal />
      </div>
    </div>
  </div>
</template>

<script>
import deleteModal from "../components/deleteModal";
import { mapGetters } from "vuex";

export default {
  data() {
    return {
      data: {
        roleName: '',
      },
      addModal: false,
      editModal: false,
      isAdding: false,
      roles: [],
      isEditing: false,
      editData: {
        id: '',
        roleName: '',
      },
      editIndex: -1,
      deleteModal: false,
      isDeleting: false,
      deleteItem: {
        id: '',
      },
      deletingIndex: -1,
    };
  },
  components: {
    deleteModal,
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
    async edit() {
      if (this.editData.roleName.trim() == "")
        return this.error("Role name is required");
      this.isEditing = true;
      const res = await this.callApi("post", "app/edit_role", this.editData);
      if (res.status === 200) {
        this.roles[this.editIndex].roleName = this.editData.roleName;
        this.success("Role has been edited succesfully");
        this.editModal = false;
      } else {
        if (res.status == 422) {
          if (res.data.errors.roleName) {
            this.error(res.data.errors.roleName[0]);
          }
        } else {
          this.swr();
        }
      }
      this.isEditing = false;
    },
    showEditModal(role, i) {
      const obj = {
        roleName: role.roleName,
        id: role.id
      };
      this.editData = obj;
      this.editModal = true;
      this.editIndex = i;
      console.log(this.editData);
    },
    showDeleteModal(role, i) {
      const deleteModalObj = {
        showDeleteModal: true,
        deleteUrl: "app/delete_role",
        data: {id: role.id},
        deletingIndex: i,
        msg: 'Are you sure you want to delete this role?',
        successMsg: 'Role has been deleted succesfully',
      };
      this.$store.commit("setDeletingModalObj", deleteModalObj);
    },
  },
  async created() {
    const res = await this.callApi("get", "app/get_roles");
    if (res.status == 200) {
      this.roles = res.data;
    } else {
      this.swr();
    }
  },
  computed: {
    ...mapGetters(["getDeleteModalObj"]),
  },
  watch: {
    getDeleteModalObj(obj) {
      if (obj.isDeleted) {
        this.roles.splice(obj.deletingIndex, 1);
      }
    },
  },
};
</script>
