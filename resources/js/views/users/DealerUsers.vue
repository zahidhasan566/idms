<template>
  <div class="container-fluid" id="users">
    <breadcrumb :options="['Dealer Users']">
      <button class="btn btn-primary" @click="addDeptModal()">Add User</button>
    </breadcrumb>
    <div class="row" style="padding:8px 0px;">
      <div class="col-md-4">
        <button type="button" class="btn btn-success btn-sm" @click="exportData">Export to Excel</button>
      </div>
    </div>
    <advanced-datatable :options="tableOptions">
      <template slot="action" slot-scope="row">
        <a href="javascript:" @click="addDeptModal(row.item)"> <i class="ti-pencil-alt"></i></a>
        <a href="javascript:" @click="changePassword(row.item.UserId)"> <i class="ti-lock"></i></a>
      </template>
    </advanced-datatable>
    <add-edit-dealer-user @changeStatus="changeStatus" v-if="loading"/>
    <reset-password @changeStatus="changeStatus" v-if="loading"/>
  </div>
</template>
<script >

import {bus} from "../../app";
import {Common} from "../../mixins/common";
import moment from "moment";

export default {
  mixins: [Common],
  data() {
    return {
      tableOptions: {
        source: 'dealer/users',
        search: true,
        slots: [4,8,9],
        hideColumn: [],
        slotsName: ['action'],
        sortable: [2],
        pages: [20, 50, 100],
        addHeader: ['Action']
      },
      loading: false,
      cpLoading: false
    }
  },
  mounted() {
    bus.$off('changeStatus',function () {
      this.changeStatus()
    })
  },
  methods: {
    changeStatus() {
      this.loading = false
    },
    addDeptModal(row = '') {
      this.loading = true;
      setTimeout(() => {
        bus.$emit('add-edit-user', row);
      })
    },
    changePassword(row) {
      this.loading = true;
      setTimeout(() => {
        bus.$emit('edit-password', row);
      })
    },
    deleteDept(id) {
      this.deleteAlert(() => {
        this.axiosDelete('users', id, (response) => {
          this.successNoti(response.message);
          this.$store.commit('departmentDelete', id);
          bus.$emit('refresh-datatable');
        }, (error) => {
          this.errorNoti(error);
        })
      });
    },
    exportData() {
      bus.$emit('export-data','user-list-'+moment().format('YYYY-MM-DD'))
    }
  }
}
</script>

<style>
#users table tr td:nth-child(1),table tr td:nth-child(2),table tr td:nth-child(3),table tr td:nth-child(4),table tr td:nth-child(5),table tr td:nth-child(6),table tr td:nth-child(7) {
  text-align: left;
}
</style>
