<template>
  <div class="container-fluid">
    <breadcrumb :options="['Post Delivery Print']"></breadcrumb>
    <div class="card">
      <div class="card-body">
        <div id="printDiv">
            <div class="row" style="text-align: center;">
                <div class="col-md-12">
                    <p style="font-weight: bold;font-size: 25px;">Post Delivery Checklist</p>
                </div>
            </div>
          <div class="row" style="text-align: center;">
            <div class="col-md-12">
              <p style="font-weight: bold;font-size: 20px;">Delivery Checklist</p>
            </div>
          </div>
          <div class="row">
            <div class="col-md-2">
              <img :src="`${mainOrigin}assets/images/logo-svg.png`" style="width:100%;height: 70px;"/>
            </div>
            <div class="col-md-4 head">
              <p style="font-weight: bold;font-size: 16px;">Ifad Motors Limited</p>
              <p>IFAD Tower</p>
              <p>Plot 7 (new), Tejgaon Industrial Area, Dhaka-1208</p>
            </div>
              <div class="offset-4 col-md-2 headerRightLogo" style="text-align: end">
                  <img :src="`${mainOrigin}assets/images/reLogo.png`" style="width:50%;"/>
              </div>
          </div>
          <div class="row r-info">
            <div class="col-md-2">
              <p>Dealer Code</p>
              <p>Customer Name</p>
              <p>Customer Mobile</p>
              <p>Chassis</p>
            </div>
            <div class="col-md-4">
              <p>: {{me.UserId}}</p>
              <p>: {{master.CustomerName}}</p>
              <p>: {{master.CustomerMobile}}</p>
              <p>: {{master.FrameNo}}</p>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="table-bordered">
                <table class="table table-sm m-0 small">
                  <thead class="thead-dark">
                  <tr>
                    <th>SL</th>
                    <th>Inspection Areas</th>
                    <th>Inquiry</th>
                    <th>Status</th>
                    <th>Remarks</th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr v-for="(d,index) in details" :key="index">
                    <td>{{d.ChecklistId}}</td>
                    <td>{{d.InspectionAreas}}</td>
                    <td>{{d.Inquiry}}</td>
                    <td>{{d.Status === 'Y' ? 'Yes' : 'No'}}</td>
                    <td>{{d.Remarks}}</td>
                  </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="row signature" style="margin-top: 250px">
            <div class="col-md-4">
              <p class="bold">Customer Signature</p>
              <div class="pad">
                <p>Signature: </p>
                <p>Date: </p>
              </div>
            </div>
            <div class="offset-md-4 col-md-4">
              <p class="bold">Inspector Signature</p>
              <div class="pad">
                <p>Signature: </p>
                <p>Date: </p>
              </div>
            </div>
          </div>
            <div class="row support">
                <div class="col-md-4">
                    <p class="bold"> <i class="ti ti-mobile"></i> Customer Support : 16598</p>

                </div>
                <div class="offset-md-4 col-md-4">
                    <p class="bold"> <i class="mdi mdi-fuel"></i>Use OCTANE Only</p>
                </div>
            </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import {Common} from "../../mixins/common";
import {baseurl} from "../../base_url";
import * as url from "url";

export default {
  mixins: [Common],
  data() {
    return {
      master: {},
      details: []
    }
  },
  computed: {
    me() {
      return this.$store.state.me
    }
  },
  created() {
    this.getData()
  },
  mounted() {
    document.title = 'Post Delivery Checklist Print | DMS';
  },
  methods: {
    getData() {
      this.axiosGet('post-delivery/print/'+this.$route.params.inquiryId,(response) => {
        this.master = response.master
        this.details = response.details
        setTimeout(() => {
          window.print()
        },2000)
      },function(error) {

      });
    }
  }
}
</script>

<style scoped>
#printDiv {
  padding: 15px;
}
.head {
  padding: 10px 0 0 30px;
}
.head p {
  margin: 0;
}
.headerRightLogo{
    padding: 10px 0 0 30px;
}
.r-info {
  margin: 40px 0;
}
.r-info p {
  margin: 0;
}
.preserve {
  font-size: 15px;
  color: #000000;
  margin: 30px 0 0 0 !important;
  text-transform: capitalize;
}
.note {
  margin-top: 10px;
  margin-bottom: 10px;
}
.bold {
  font-weight: bold;
  font-size: 14px;
}
.signature {
  margin-top: 50px;
}
.signature p {
  margin: 0;
}
.signature .pad {
  padding: 10px 0;
}
.support {
    margin-top: 50px;
}
.support p {
    margin: 0;
}
.support .pad {
    padding: 10px 0;
}
.col-md-1 {
  flex: 0 0 8.333333%;
  max-width: 8.333333%;
  position: relative;
  width: 100%;
  padding-right: 15px;
  padding-left: 15px;
}
.col-md-4 {
  flex: 0 0 33.333333%;
  max-width: 33.333333%;
  position: relative;
  width: 100%;
}
.col-md-3 {
  flex: 0 0 25%;
  max-width: 25%;
  position: relative;
  width: 100%;
}
.col-md-2 {
  flex: 0 0 16.666667%;
  max-width: 16.666667%;
  position: relative;
  width: 100%;
  padding-right: 15px;
  padding-left: 15px;
}
.offset-4 {
  margin-left: 33.333333%;
}

</style>

<style>
thead {
  background: #ffffff !important;
  color: #000000 !important;
}
.table-bordered thead th {
  font-weight: bold;
}
</style>