<template>
  <div class="container-fluid" id="users">
    <breadcrumb :options="['Promotion Report']">
    </breadcrumb>
    <div class="row" style="padding:8px 0;">
      <div class="col-md-4">
        <button type="button" class="btn btn-success btn-sm" @click="exportData">Export to Excel</button>
      </div>
    </div>
    <general-datatable :options="tableOptions" :customers="customers">
    </general-datatable>
  </div>
</template>
<script>

import {bus} from "../../app";
import {Common} from "../../mixins/common";
import moment from "moment";
import General from "../../components/datatable/General.vue";

export default {
  components: {General},
  mixins: [Common],
  data() {
    return {
      roles: [],
      tableOptions: {
        source: 'product-promotion/report',
        search: false,
        slots: [],
        hideColumn: [],
        slotsName: [],
        filterOption: true,
        showFilter: ['startDate','endDate','customers'],
        colSize: ['col-lg-2','col-lg-2','col-lg-2'],
        sortable: [],
        pages: [20, 50, 100],
        addHeader: []
      },
      customers: []
    }
  },
  mounted() {
    this.getData()
    bus.$off('changeStatus',function () {
      this.changeStatus()
    })
  },
  methods: {
    getData() {
      this.axiosGet('product-promotion/load-customer',(response) => {
        this.customers = response.data
      })
    },
    exportData() {
      bus.$emit('export-data','promotion-report-'+moment().format('YYYY-MM-DD'))
    }
  }
}
</script>

<style>
#users table tr td:nth-child(1),table tr td:nth-child(2),table tr td:nth-child(3),table tr td:nth-child(4),table tr td:nth-child(5),table tr td:nth-child(6),table tr td:nth-child(7) {
  text-align: left;
}
</style>
