<template>
  <div class="container-fluid" id="spare-parts">
    <breadcrumb :options="['Spare Parts Invoice List','Spare Parts Invoice Create']"></breadcrumb>

    <div class="row">
      <div class="col-sm-12">
        <div class="card">
          <div class="card-body" id="customer_form">
            <ValidationObserver v-slot="{ handleSubmit }">
              <form @submit.prevent="handleSubmit(onSubmit)" @keydown.enter="$event.preventDefault()">
                <div class="row">
                  <div class="col-md-3">
                    <div class="row form-divider m-b-15">
                      <div class="form-divider-title">
                        <p style="width: 160px">Customer</p>
                      </div>
                      <div class="col-12">
                        <ValidationProvider name="Chassis No" mode="eager" v-slot="{ errors }">
                          <div class="form-group">
                            <label for="chassis">Chassis No</label>
                            <input type="text" class="form-control" name="chassis" id="chassis"
                                   v-model="form.chassis" @change="getCustomerByChassis">
                          </div>
                        </ValidationProvider>
                        <ValidationProvider name="Customer Name" mode="eager" rules="required" v-slot="{ errors }">
                          <div class="form-group">
                            <label for="customerName">Customer Name<span style="color: red">*</span></label>
                            <input type="text" class="form-control" name="customerName" id="customerName"
                                   v-model="form.customerName">
                            <div class="error" v-if="form.errors.has('customerName')"
                                 v-html="form.errors.get('customerName')"/>
                            <span class="error-message"> {{ errors[0] }}</span>
                          </div>
                        </ValidationProvider>
                        <ValidationProvider name="Customer Address" mode="eager"v-slot="{ errors }">
                          <div class="form-group">
                            <label for="Customer Address">Customer Address</label>
                            <textarea rows="10" class="form-control" name="customerAddress" id="customerAddress"
                                      v-model="form.customerAddress" style="height:50px;"></textarea>
                            <div class="error" v-if="form.errors.has('customerAddress')"
                                 v-html="form.errors.get('customerAddress')"/>
                            <span class="error-message"> {{ errors[0] }}</span>
                          </div>
                        </ValidationProvider>
                        <ValidationProvider name="Customer Mobile" mode="eager"  v-slot="{ errors }">
                          <div class="form-group">
                            <label for="Customer Mobile">Customer Mobile</label>
                            <input type="text" class="form-control" name="customerMobile" id="customerMobile"
                                   v-model="form.customerMobile">
                            <div class="error" v-if="form.errors.has('customerMobile')"
                                 v-html="form.errors.get('customerMobile')"/>
                            <span class="error-message"> {{ errors[0] }}</span>
                          </div>
                        </ValidationProvider>
                      </div>
                    </div>
                    <div class="row form-divider" style="margin-top:25px !important;">
                      <div class="form-divider-title">
                        <p style="width: 160px">Optional</p>
                      </div>
                      <div class="col-12">
                        <ValidationProvider name="Affiliation" mode="eager"  v-slot="{ errors }">
                          <div class="form-group">
                            <label for="affiliation">Affiliated Agent</label>
                            <select class="form-control" name="affiliation" id="affiliation"
                                    v-model="form.affiliator">
                              <option value="">Select</option>
                              <option :value="a.AffiliatorCode+'###'+a.Mobile" v-for="(a,i) in affiliators" :key="i">
                                {{ a.AffiliatorName }}
                              </option>
                            </select>
                            <div style="display: inline-flex; align-items: center;">
                              <button type="button" class="btn btn-success btn-sm" @click="applyAffiliatorDiscount">Apply</button>
                              <div>
                                <p class="m-l-10 m-t-5">Discount Applied: <span>{{Number(form.affiliatorDiscount).toFixed(2)}}</span></p>
                              </div>
                            </div>
                          </div>
                        </ValidationProvider>
                        <ValidationProvider name="Reference" mode="eager" v-slot="{ errors }">
                          <div class="form-group">
                            <label for="reference">Local Mechanics</label>
                            <select class="form-control" name="reference" id="reference"
                                    v-model="form.reference">
                              <option value="">Select</option>
                              <option :value="m.MechanicsCode" v-for="(m,i) in mechanics" :key="i">
                                {{ m.UpazillaName }} - {{ m.MechanicsName }}
                              </option>
                            </select>
                              <span class="error-message"> {{ errors[0] }}</span>
                          </div>
                        </ValidationProvider>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="row form-divider m-b-15">
                      <div class="form-divider-title">
                        <p style="width: 160px">Spare Parts</p>
                      </div>
                      <div class="col-12">
                        <ValidationProvider name="Spare Parts" mode="eager" rules="required" v-slot="{ errors }">
                          <div class="form-group">
                            <label for="customerName">Spare Parts<span style="color: red">* (provide at least 3 character)</span></label>
                            <v-select :filterable="false" :options="spareparts" label="title" v-model="form.sparePart"
                                      @search="filterSpareParts" @input="selectSpareParts"></v-select>
                            <div class="error" v-if="form.errors.has('spareParts')"
                                 v-html="form.errors.get('spareParts')"/>
                            <span class="error-message"> {{ errors[0] }}</span>
                          </div>
                        </ValidationProvider>
                        <ValidationProvider name="Rack Name" mode="eager"  v-slot="{ errors }">
                          <div class="form-group">
                            <label for="rackName">Rack name</label>
                            <input type="text" class="form-control" name="rackName" id="rackName"
                                   v-model="form.rackName" readonly>
                              <span class="error-message"> {{ errors[0] }}</span>
                          </div>
                        </ValidationProvider>
                        <ValidationProvider name="Unit Price" mode="eager" v-slot="{ errors }">
                          <div class="form-group">
                            <label for="unitPrice">Unit Price</label>
                            <input type="text" class="form-control" name="unitPrice" id="unitPrice"
                                   v-model="Number(form.unitPrice).toFixed(2)" readonly>
                          </div>
                        </ValidationProvider>
                        <ValidationProvider name="Quantity" mode="eager" rules="required|min:0" v-slot="{ errors }">
                          <div class="form-group">
                            <label for="quantity">Quantity</label>
                            <div style="display: flex;">
                              <button type="button" class="btn btn-primary" style="z-index: 999"
                                      @click="decreaseQuantity">-
                              </button>
                              <input type="number" class="form-control" id="quantity" min="1"
                                     v-model="form.quantity">
                              <button type="button" class="btn btn-primary" style="z-index: 999"
                                      @click="increaseQuantity">+
                              </button>
                            </div>
                            <div class="error" v-if="form.errors.has('quantity')"
                                 v-html="form.errors.get('quantity')"/>
                            <span class="error-message"> {{ errors[0] }}</span>
                          </div>
                        </ValidationProvider>
                        <ValidationProvider name="Discount" mode="eager" rules="min_value:0|max_value:100" v-slot="{ errors }">
                          <div class="form-group">
                            <label for="discount">Discount (%)</label>
                            <input type="text" class="form-control" id="discount"
                                   v-model="form.discount" min="0" max="100" @keyup="changeDiscount">
                          </div>
                          <span class="error-message"> {{ errors[0] }}</span>
                        </ValidationProvider>
                        <hr>
                        <div class="form-group">
                          <label for="discountPrice">Discount Price</label>
                          <input type="text" class="form-control" id="discountPrice"
                                 v-model="Number(form.discountPrice).toFixed(2)" disabled>
                        </div>
                        <hr>
                        <div class="form-group">
                          <label for="total">Total</label>
                          <input type="text" class="form-control" id="total"
                                 v-model="Number(form.total).toFixed(2)" disabled>
                        </div>
                        <button type="submit" class="btn btn-primary float-right m-t-10">Add</button>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-5">
                    <div class="row m-b-15">
                      <div class="col-12 datatable" style="overflow-x:auto;height: 300px;">
                        <table
                            class="table table-bordered table-striped dt-responsive nowrap dataTable no-footer dtr-inline table-sm">
                          <tr class="thead-dark">
                            <th>Model</th>
                            <th>Unit Price</th>
                            <th>Quantity</th>
                            <th>Discount(%)</th>
                            <th>Discount Price</th>
                            <th>Total Price</th>
                            <th>Remove</th>
                          </tr>
                          <tbody v-if="fields.length > 0">
                          <tr v-for="(f,i) in fields" :key="i">
                            <td>{{ f.model.title }}</td>
                            <td style="text-align: right;">{{ Number(f.model.unitPrice).toFixed(2) }}</td>
                            <td style="text-align: right;">{{ f.quantity }}</td>
                            <td style="text-align: right;">{{ f.discount }}</td>
                            <td style="text-align: right;">{{ Number(f.discountPrice).toFixed(2) }}</td>
                            <td style="text-align: right;">{{ Number(f.total).toFixed(2) }}</td>
                            <td>
                              <button type="button" class="btn btn-danger btn-sm" @click="removeItem(i)">X</button>
                            </td>
                          </tr>
                          <tr>
                            <td colspan="4">Total</td>
                            <td style="text-align: right;">{{ totalDiscount }}</td>
                            <td style="text-align: right;">{{ total }}</td>
                            <td colspan="1"></td>
                          </tr>
                          <tr v-if="form.affiliatorDiscount > 0">
                            <td colspan="4">Affiliator Discount</td>
                            <td style="text-align: right;">{{ Number(form.affiliatorDiscount).toFixed(2) }}</td>
                            <td style="text-align: right;">{{ Number(totalAfterAffiliation).toFixed(2) }}</td>
                            <td><button type="button" class="btn btn-danger btn-sm" @click="removeAffiliation">X</button></td>
                          </tr>
                          </tbody>
                          <tbody v-else>
                          <tr>
                            <td colspan="7" style="text-align: center">No Items</td>
                          </tr>
                          </tbody>
                        </table>
                        <button v-if="fields.length > 0" type="button" class="btn btn-primary btn-sm m-t-10" @click="checkout">Checkout</button>
                      </div>
                    </div>
                  </div>
                </div>
              </form>
            </ValidationObserver>
            <br>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import {baseurl} from '../../base_url';
import {Common} from "../../mixins/common";
import moment from "moment";
import DatePicker from 'vue2-datepicker';
import 'vue2-datepicker/index.css';
import "vue-select/dist/vue-select.css";
import Dropdown from 'vue-simple-search-dropdown';
export default {
  name: "Invoice",
  mixins: [Common],
  components: {DatePicker, Dropdown},
  data() {
    return {
      fields: [],
      mechanics: [],
      affiliators: [],
      spareparts: [],
      form: new Form({
        customerName: '',
        customerAddress: '',
        customerMobile: '',
        sparePart: '',
        quantity: 1,
        unitPrice: 0,
        discount: 0,
        discountPrice: 0,
        total: 0,
        affiliator: '',
        reference: '',
        affiliatorDiscount: 0,
        rackName: ''
      }),
      totalDiscount: 0,
      total: 0,
      totalAfterAffiliation: 0
    }
  },
  created() {
    //
  },
  mounted() {
    document.title = 'Spare Parts Invoice Create | DMS';
    this.getData();
  },
  methods: {
    getData() {
      this.axiosGet('invoice-spare-parts/get-supporting-data', (response) => {
        this.mechanics = response.mechanics
        this.affiliators = response.affiliators
      }, (error) => {

      })
    },
    getCustomerByChassis() {
      this.axiosPost('invoice-spare-parts/get-customer-by-chassis',{
        chassisNo: this.form.chassis
      },(response) => {
        console.log(response)
      })
    },
    selectSpareParts(val) {
      if (val) {
        this.form.sparePart = val
        this.form.unitPrice = val.unitPrice
        this.form.total = val.unitPrice
        this.form.rackName = val.rackName
        this.form.hasChassis = val.brandCode === 'P001' || val.brandCode === 'P002'
        this.grandTotal()
      } else {
        this.clearForm()
      }
    },
    filterSpareParts(val, loading) {
      if (val.length > 2) {
        loading(true)
        this.axiosPost('invoice-spare-parts/search', {
          search: val
        }, (response) => {
          let data = []
          response.data.forEach((item) => {
            data.push({
              id: item.productcode,
              unitPrice: item.mrp,
              title: item.productname,
              rackName: item.rackname,
              brandCode: item.BrandCode,
              brandName: item.BrandName
            })
          })
          this.spareparts = data
          loading(false)
        })
      }
    },
    increaseQuantity() {
      this.form.quantity += 1
      this.calculate()
    },
    decreaseQuantity() {
      if (this.form.quantity > 1) {
        this.form.quantity -= 1
        this.calculate()
      }
    },
    changeDiscount() {
      this.form.discountPrice = (this.form.unitPrice * this.form.quantity) * (this.form.discount / 100)
      this.calculate()
    },
    calculate() {
      this.form.total = (this.form.unitPrice * this.form.quantity) - (this.form.unitPrice * this.form.quantity) * (this.form.discount / 100)
    },
    grandTotal() {
      let total = 0
      let totalDiscount = 0
      if (this.fields.length > 0) {
        this.fields.forEach((item) => {
          total += Number(item.total)
          totalDiscount += Number(item.discountPrice)
        })
        this.total = total.toFixed(2)
        this.totalDiscount = totalDiscount.toFixed(2)
        if (this.form.affiliatorDiscount > 0) {
          this.totalAfterAffiliation = this.total - this.form.affiliatorDiscount
        }
      }
    },
    removeItem(i) {
      this.fields.splice(i,1)
      this.grandTotal()
      if (this.fields.length === 0) {
        this.form.affiliator = ''
        this.form.affiliatorDiscount = 0
      }
    },
    removeAffiliation() {
      this.form.affiliator = ''
      this.form.affiliatorDiscount = 0
      this.grandTotal()

    },
    applyAffiliatorDiscount() {
      this.form.affiliatorDiscount = this.total * (10/100)
      this.totalAfterAffiliation = this.total - this.form.affiliatorDiscount
    },
    onSubmit() {
        console.log("ok")
      let exists = this.fields.find((item) => {
        return item.model.id === this.form.sparePart.id
      })
      if (!exists) {
        this.axiosPost('invoice-spare-parts/stock-check',{
          productCode: this.form.sparePart.id
        },(response) => {
          if (response.stock - this.form.quantity >= 0) {
            this.fields.push({
              model: this.form.sparePart,
              unitPrice: this.form.unitPrice,
              quantity: this.form.quantity,
              discount: this.form.discount,
              discountPrice: this.form.discountPrice,
              total: this.form.total,
              chassis: this.form.chassis
            })
            this.clearForm()
            this.grandTotal()
            $("input[type=search]").focus()
          } else {
            this.errorNoti('No stock available! Current stock '+response.stock)
          }
        },(error) => {

        })
      } else {
        this.errorNoti("Item already exists in the cart!")
      }
    },
    clearForm() {
      this.form.sparePart = ''
      this.form.unitPrice = 0.00
      this.form.quantity = 1
      this.form.discount = 0
      this.form.discountPrice = 0
      this.form.total = 0.00
      this.form.sparePart = ''
      this.spareparts = []
    },
    checkout() {
        console.log(this.fields)

      if (this.fields.length > 0) {
        this.axiosPost('invoice-spare-parts/checkout',{
          customerName: this.form.customerName,
          customerAddress: this.form.customerAddress,
          customerMobile: this.form.customerMobile,
          affiliator: this.form.affiliator,
          reference: this.form.reference,
          affiliatorDiscount: this.form.affiliatorDiscount,
          fields: this.fields
        },(response) => {
          this.clearForm()
          this.form.affiliator = ''
          this.form.affiliatorDiscount = 0
          this.form.reset()
          this.form.clear()
          this.fields = []
          this.successNoti(response.message)
          this.$router.push({name: 'InvoiceSparePartsPrint', params: {invoiceId: response.invoiceId}})
        },(error) => {
          this.errorNoti(error.response.data.message)
        })
      }
    }
  }
}
</script>

<style scoped>
#spare-parts #quantity {
  height: 50px;
  font-size: 20px;
  margin-left: -10px;
  margin-right: -10px;
  z-index: 0;
  text-align: center;
}

#customer_form .form-control {
  font-size: 10px;
  height: 29px;
}

#customer_form .form-group {
  margin-bottom: 0;
}

#customer_form label {
  font-size: 11px !important;
  margin-bottom: 0 !important;
  margin-top: 10px !important;
}

.form-divider {
  padding: 6px 5px 5px 5px;
  border: 1px solid #4d87f64f;
  border-radius: 13px;
  margin: 0 auto;
}

#invoice2 .auto-complete2 {
  position: relative;
  display: block;
}

#invoice2 .auto-complete2 ul {
  list-style: none;
  margin: 0;
  padding: 5px 0 0 0px;
  position: absolute;
  width: 100%;
  border: 1px solid #0000000d;
  background: #ffffff;
  max-height: 200px;
  overflow-y: scroll;
  z-index: 999;
}

#invoice2 .auto-complete2 ul li {
  border-bottom: 1px solid #b7b7b7;
  background: #cbc4c4;
  padding: 5px;
  cursor: pointer;
}

#invoice2 .auto-complete2 ul li a {
  color: #000000;
}

#invoice2 .auto-complete2 ul li:hover {
  background: #fff3cd;
}

#invoice2 :focus {
  background: #fff3cd;
}

.form-divider-title {
  position: relative;
  top: -20px;
}

.form-divider-title p {
  position: absolute;
  padding: 0 25px;
  background: #ffffff;
  text-transform: uppercase;
  font-weight: bold;
  color: #4d79f6b5 !important;
  font-size: 12px;
}

.tableFixHead {
  overflow-y: auto; /* make the table scrollable if height is more than 200 px  */
  height: 200px; /* gives an initial height of 200px to the table */
}

.tableFixHead thead th {
  position: sticky; /* make the table heads sticky */
  top: 0px; /* table head will be placed from the top of the table and sticks to it */
}

table {
  border-collapse: collapse; /* make the table borders collapse to each other */
  width: 100%;
}

th,
td {
  padding: 8px 16px;
  border: 1px solid #ccc;
}

</style>