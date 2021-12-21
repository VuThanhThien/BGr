<template>
  <div>
    <!-- #region todo-list -->
    <div class="md-screen">
        <TodoList/>
    </div>
    <!-- #endregion todolist -->
    <!-- #region filter date  -->
    <div id="myTabContent" class="tab-content">
      <b-overlay
        class="d-flex flex-column"
        opacity="0"
        :show="false"
        rounded="sm"
      >
        <div class="row">
          <div class="mb-5 col-xl-3">
            <DateRange ref="dateRange" @updateTimePicker="updateTimePicker" @updatedateRangText="updatedateRangText" @cancelFilterDate="cancelFilterDate"/>
          </div>
        </div>
        <div class="row mb-5">
          <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card card-custom">
              <div class="card-body p-5">
                <div class="row">
                  <div class="col-8">
                    <div class="numbers">
                        <div class="card-toolbar mb-2">
                            <span class="text-muted">{{dateRangText}}</span>
                        </div>
                        <span class="text-bixgrow text-md font-weight-bolder">Clicks</span>
                      <h3 class="font-weight-bolder mb-0 mt-1">
                        {{ statistics.clicks }}
                      </h3>
                    </div>
                  </div>
                  <div class="col-4 text-end d-flex align-items-center justify-content-end justify-content-end">
                    <div class="symbol symbol-50 symbol-bixgrow">
                      <span class="symbol-label symbol-label-custom shadow">
                        <span class="svg-icon svg-icon-2x svg-icon-white">
                          <inline-svg
                            src="media/svg/icons/General/Cursor.svg"
                          />
                        </span>
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card card-custom">
              <div class="card-body p-5">
                <div class="row">
                  <div class="col-8">
                    <div class="numbers">
                        <div class="card-toolbar mb-2">
                            <span class="text-muted">{{dateRangText}}</span>
                        </div>
                        <span class="text-bixgrow text-md font-weight-bolder">Orders</span>
                      <h3 class="font-weight-bolder mb-0 mt-1">
                        {{ statistics.orders }}
                      </h3>
                    </div>
                  </div>
                  <div class="col-4 text-end d-flex align-items-center justify-content-end justify-content-end">
                    <div class="symbol symbol-50 symbol-bixgrow">
                      <span class="symbol-label symbol-label-custom shadow">
                        <span class="svg-icon svg-icon-2x svg-icon-white">
                          <inline-svg
                            src="media/svg/icons/Shopping/Cart2.svg"
                          />
                        </span>
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card card-custom">
              <div class="card-body p-5">
                <div class="row">
                  <div class="col-8">
                    <div class="numbers">
                        <div class="card-toolbar mb-2">
                            <span class="text-muted">{{dateRangText}}</span>
                        </div>
                        <span class="text-bixgrow text-md font-weight-bolder">Revenue</span>
                      <h3 class="font-weight-bolder mb-0 mt-1">
                        {{ formatMoney(statistics.revenue, format) }}
                      </h3>
                    </div>
                  </div>
                  <div class="col-4 text-end d-flex align-items-center justify-content-end justify-content-end">
                    <div class="symbol symbol-50 symbol-bixgrow">
                      <span class="symbol-label symbol-label-custom shadow">
                        <span class="svg-icon svg-icon-2x svg-icon-white">
                          <inline-svg
                            src="media/svg/icons/Shopping/Chart-line1.svg"
                          />
                        </span>
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-3 col-sm-6 ">
            <div class="card card-custom">
              <div class="card-body p-5">
                <div class="row">
                  <div class="col-8">
                    <div class="numbers">
                        <div class="card-toolbar mb-2 ">
                            <span class="text-muted">{{dateRangText}}</span>
                        </div>
                        <span class="text-bixgrow text-md font-weight-bolder">Commission</span>
                      <h3 class="font-weight-bolder mb-0 mt-1">
                        {{ formatMoney(statistics.commission, format) }}
                      </h3>
                    </div>
                  </div>
                  <div class="col-4 text-end d-flex align-items-center justify-content-end justify-content-end">
                    <div class="symbol symbol-50 symbol-bixgrow">
                      <span class="symbol-label symbol-label-custom shadow">
                        <span class="svg-icon svg-icon-2x svg-icon-white">
                          <i
                            class="fas fa-hand-holding-usd icon-2x"
                            style="color: #fff"
                          ></i>
                        </span>
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <template #overlay>
          <TableLoader1></TableLoader1>
        </template>
      </b-overlay>
    </div>
    <!-- #endregion -->
    <!-- #region Usefull link and new -->
    <div class="row">
      <!-- Useful links -->
      <div :class="[showReviewCard == true ? 'col-xl-8' : 'col-xl-12']">
        <div class="card card-custom card-stretch gutter-b ">
          <div class="card-header border-0 pt-5">
            <div class="card-title">
                <h3 class="card-title font-weight-bolder text-bixgrow">
                  Promote your affiliate sign-up page
                </h3>
            </div>
          </div>
          <div class="card-body d-flex flex-column">
            <!-- link signup -->
            <div
              class="card card-custom mb-5"
              style="border: 1px dashed rgb(139 139 139);"
            >
              <div class="card-body" >

                <select
                v-model="slugOfSelectedGroup"
                class="form-control form-control-custom mb-1"
                >
                <option
                    v-for="(o, index) in groupSelectOption"
                    :value="o.slug"
                    :o="o"
                    :key="index"
                >
                    {{ o.text }}
                </option>
                </select>
                <span style="font-size:0.9rem;" class="text-muted form-text mb-5 ml-1">Select the program that you want to get the affiliate sign up link</span>
                <div class="mb-3">Affiliate Signup Page:</div>
                <div class="d-flex">
                  <input
                    type="text"
                    disabled
                    class="form-control form-control-solid me-3 flex-grow-1"
                    ref="aff_link_input"
                    name="search"
                    v-model="GroupSignupPage"
                  />
                <button
                    class="btn btn-light fw-bolder flex-shrink-0 ml-1"
                    @click="copy('aff_link_input', $event)"
                  >
                    Copy
                  </button>
                </div>
              </div>
            </div>
            <!-- tip signup -->
            <div class="d-flex tip-alert-dashboard">
              <Tips class="w-100 mb-0" :type="'alert-white'">
                Tip: To embed the affiliate signup page into your store, go
                  <a class="font-weight-bold" href="#/settings/embedded-portal"
                    >here</a>.
              </Tips>
            </div>
            <!-- tip login -->
            <div class="d-flex tip-alert-dashboard">
                <Tips class="w-100 mb-0" :type="'alert-white'">
                    Tip: To customize the affiliate signup form, visit
                    <a class="link-primary font-weight-bold" @click="openlink"
                    >here</a>.
              </Tips>
            </div>
            <div class="d-flex tip-alert-dashboard">
                <Tips class="w-100 mb-0" :type="'alert-white'">
                    Tip: To visit the affiliate login page, go
                    <a class="link-primary font-weight-bold" target="_blank" :href="affiliateLoginDefault"
                    >here</a>.
              </Tips>
            </div>
          </div>
        </div>
      </div>
      <!-- Get Review -->
      <div :class="[showReviewCard == true ? 'col-xl-4' : 'd-none']">
        <div class="card card-custom card-stretch gutter-b ">
          <div class="card-header border-0 pt-5">
            <div class="card-title">
                <h3 class="card-title font-weight-bolder text-bixgrow">
                  Feedback
                </h3>
            </div>
          </div>
          <div class="card-body d-flex flex-column">
              <div class="text-center text-bixgrow text-md font-weight-bold mb-10 mt-20">
                  How is your experience with our app?
                  <br>
                  Your honest feedback can motivate us and make developments to our app.
              </div>
            <ListStar/>
          </div>
        </div>
      </div>
    </div>
    <!-- #endregion -->
    <!-- #region performance and commission status -->
    <b-overlay
      class="d-flex flex-column"
      opacity="0"
      :show="loadPerformanceOverplay"
      rounded="sm"
    >
      <div class="row">
        <div class="col-xl-8">
          <div class="card card-custom card-stretch gutter-b">
            <div class="card-header border-0">
              <div class="card-title">
                <i class="fas icon-2x mr-2 fa-chart-line text-bixgrow"></i>
                <h3 class="card-title font-weight-bolder text-bixgrow">
                  Performance
                </h3>
              </div>
              <div class="card-toolbar">
                <span class="label label-lg label-inline text-muted">{{dateRangText}}</span>
            </div>
            </div>
            <div class="card-body d-flex flex-column">
              <div class="row mt-2">
                <div class="col-xl-12">
                  <div class="row">
                    <div class="col-md-7 mb-2">
                      <ul
                        class="
                          nav nav-pills nav-pills-sm nav-dark-75
                          float-left
                          pl-2
                          bg-white
                          rounded
                        "
                        id="myTab"
                        role="tablist"
                      >
                        <li class="nav-item nav-item-bixgrow">
                          <a
                            class="nav-link py-2 px-4"
                            data-toggle="tab"
                            :class="[type == 'clicks' ? 'active' : '']"
                            href="#kt_tab_pane_11_1"
                            @click.prevent="loadType('clicks')"
                            >Clicks
                            <span class="ml-2" v-if="type == 'clicks'">{{
                              total
                            }}</span>
                          </a>
                        </li>
                        <li class="nav-item nav-item-bixgrow">
                          <a
                            class="nav-link py-2 px-4"
                            :class="[type == 'orders' ? 'active' : '']"
                            data-toggle="tab"
                            href="#kt_tab_pane_11_2"
                            @click.prevent="loadType('orders')"
                            >Conversions
                            <span class="ml-2" v-if="type == 'orders'">{{
                              total
                            }}</span></a
                          >
                        </li>
                        <li class="nav-item nav-item-bixgrow">
                          <a
                            class="nav-link py-2 px-4"
                            :class="[type == 'sales' ? 'active' : '']"
                            data-toggle="tab"
                            href="#kt_tab_pane_11_3"
                            @click.prevent="loadType('sales')"
                            >Sales
                            <span class="ml-2" v-if="type == 'sales'">{{
                              formatMoney(total, format)
                            }}</span></a
                          >
                        </li>
                      </ul>
                    </div>
                    <div class="col-md-5">
                      <select
                        v-model="groupSelected"
                        @change="changeSelectGroup"
                        class="form-control form-control-custom"
                      >
                        <option value="" selected class="text-muted">
                          Filter by program
                        </option>

                        <option
                          v-for="(o, index) in groupSelectOption"
                          :value="o.value"
                          :o="o"
                          :key="index"
                        >
                          {{ o.text }}
                        </option>
                      </select>
                    </div>
                  </div>
                  <div class="justify-content-center">
                    <apexchart
                      type="area"
                      height="300"
                      :options="chartOptions1"
                      :series="series1"
                    ></apexchart>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-4">
          <div class="card card-custom card-stretch gutter-b">
                <div class="card-header border-0 py-5">
              <div class="card-title">
                <h3 class="card-title font-weight-bolder text-bixgrow">
                  Commission status
                </h3>
              </div>
            </div>
                <div class="card-body border-top p-0 position-relative overflow-hidden">
                  <div class="card-spacer">
                    <div
                      id="chart"
                      class="d-flex justify-content-center"
                      v-if="chartHaveData"
                    >
                      <apexchart
                        type="pie"
                        width="300"
                        :options="chartOptions"
                        :series="series"
                      ></apexchart>
                    </div>
                    <div class="mt-10 mb-5">
                      <div class="row row-paddingless">
                        <div class="col">
                          <div class=" border-dash">
                            <div>
                              <div
                                class="
                                  font-size-h4
                                  text-warning
                                  font-weight-bolder
                                "
                              >
                                {{ formatMoney(series[0], format) }}
                              </div>
                              <div
                                class="
                                  font-size-sm
                                  text-warning
                                  font-weight-bold
                                  mt-1
                                "
                              >
                                Pending
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col">
                          <div class=" border-dash">
                            <div>
                              <div
                                class="
                                  font-size-h4
                                  text-primary
                                  font-weight-bolder
                                "
                              >
                                {{ formatMoney(series[1], format) }}
                              </div>
                              <div
                                class="
                                  font-size-sm
                                  text-primary
                                  font-weight-bold
                                  mt-1
                                "
                              >
                                Approved
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row row-paddingless">
                        <div class="col">
                          <div class=" border-dash">
                            <div>
                              <div
                                class="
                                  font-size-h4
                                  text-success
                                  font-weight-bolder
                                "
                              >
                                {{ formatMoney(series[2], format) }}
                              </div>
                              <div
                                class="
                                  font-size-sm
                                  text-success
                                  font-weight-bold
                                  mt-1
                                "
                              >
                                Paid
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col">
                          <div class=" border-dash">
                            <div>
                              <div
                                class="
                                  font-size-h4
                                  text-danger
                                  font-weight-bolder
                                "
                              >
                                {{ formatMoney(series[3], format) }}
                              </div>
                              <div
                                class="
                                  font-size-sm
                                  text-danger
                                  font-weight-bold
                                  mt-1
                                "
                              >
                                Rejected
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                    <div class="d-flex justify-content-end mr-10 mb-5">
                        <a class="text-primary font-weight-bold mb-0 icon-move-right mt-auto ml-5" href="#/payouts" style="cursor: pointer;">
                            Pay Now
                            <i class="text-primary fas fa-arrow-right ms-1" aria-hidden="true"></i>
                        </a>
                    </div>
              </div>
        </div>
      </div>
      <template #overlay>
        <TableLoader1></TableLoader1>
      </template>
    </b-overlay>
    <!-- #endregion -->
    <!-- #region topaffiliate and recent sale -->
    <div class="row">
      <div class="col-xl-6">
        <TopAffiliates
          :startDate="startDate"
          :endDate="endDate"
          :dateRangText="dateRangText"
        />
      </div>
      <div class="col-xl-6">
        <RecentSale
          :startDate="startDate"
          :endDate="endDate"
          :dateRangText="dateRangText"
        />
      </div>
    </div>
    <!-- #endregion -->
  </div>
</template>

<script>
import { mapGetters } from "vuex";
import ApiService from "../../../core/services/api.service";
import moment from "moment";
import TableLoader1 from "@/view/content/TableLoader1.vue";
import { BOverlay } from "bootstrap-vue";
import DateRange from "@/view/content/DateRange.vue";
import TopAffiliates from "./TopAffiliates.vue";
import TopProduct from "./TopProduct.vue";
import RecentSale from "./RecentSale.vue";
import ListStar from "../quick-start/get-rate-star/ListStar.vue";
import TodoList from "../todo-list/DashboardTodo.vue";
export default {
  name: "dashboard",
  data() {
    let endDate = moment( moment().subtract(1, "days").toDate()).unix() + 86399;
    let startDate = moment(moment().subtract(14, "days").toDate()).unix();
    return {
      total: 0,
      startDate: startDate,
      endDate: endDate,
      dateRangText: "Last 14 Days",
      showDateRange: true,
      loadPerformanceOverplay: false,
      type: "clicks",
      dateRange: {
        startDate: null,
        endDate: null,
      },
      groupSelected: "",
      slugOfSelectedGroup: "",
      loadingEmailTemplateTable: false,
      statistics: {
        commission: 0,
        clicks: 0,
        revenue: 0,
        orders: 0,
      },
      chartHaveData: false,
      series: [0, 0, 0, 0],
      chartOptions: {
        chart: {
          width: 380,
          type: "pie",
        },
        labels: ["Pending", "Approved", "Paid", "Rejected"],
        colors: ["#ff9900", "#3699FF", "#1BC5BD", "#F64E60"],
        responsive: [
          {
            options: {
              chart: {
                width: 200,
              },
              legend: {
                position: "bottom",
              },
            },
          },
        ],
      },
      series1: [
        {
          name: "clicks",
          data: [],
        },
      ],
      chartOptions1: {
        chart: {
          toolbar: {
            show: false,
          },
          height: 350,
          type: "area",
        },
        dataLabels: {
          enabled: false,
        },
        colors: ["#ff9900"],
        stroke: {
          curve: "smooth",
        },
        tooltip: {
          x: {
            format: "dd/MM/yy",
          },
        },
      },
    };
  },
  computed: {
    groupSelectOption() {
      let options = [];
      let groups = this.groups;
      for (var i = 0; i < groups.length; i++) {
        if(groups[i].is_default==1)
        {
          this.slugOfSelectedGroup=groups[i].slug;
           options.push({
          default:groups[i].is_default,
          value: groups[i].id,
          text: this.groups[i].name +' (Default Program)',
          slug: groups[i].slug,
        });
        }
        else{
           options.push({
           default:groups[i].is_default,
          value: groups[i].id,
          text: groups[i].name,
          slug: groups[i].slug,
        });
        }

      }
      return options;
    },
    idOfSlug() {
      let groups = this.groups;
      let defaultId = groups[0].id;
      for (var i = 0; i < groups.length; i++) {
        if (groups[i].is_default == 1) {
          defaultId = groups[i].id;
        }
        if (groups[i].slug == this.slugOfSelectedGroup) {
          return groups[i].id;
        }
      }
      return defaultId;
    },
    ...mapGetters([
      "subDomain",
      "shopName",
      "shopDomain",
      "shopShopifyDomain",
      "groups",
      "shopSettings",
      "is_feedback"
    ]),
    GroupSignupPageDefault() {
      return (
        "https://" +
        this.subDomain +
        "." +
        process.env.MIX_BASE_DOMAIN +
        "/#/register"
      );
    },
    GroupSignupPage() {
      let groupSelected=this.groups.filter(group=>(group.slug==this.slugOfSelectedGroup));
      if(groupSelected.length>0)
      {
        if(groupSelected[0].is_default)
      {
         return this.GroupSignupPageDefault;
      }
       else{
         return this.GroupSignupPageDefault + "/" + this.slugOfSelectedGroup;
        }
      }
      return this.GroupSignupPageDefault;
    },
    affiliateLoginDefault(){
        return (
        "https://" +
        this.subDomain +
        "." +
        process.env.MIX_BASE_DOMAIN +
        "/#/login"
      );
    },
    format() {
      return this.$store.getters.moneyFormat;
    },
    showReviewCard(){
        if(this.shopSettings.is_review == 1 || this.is_feedback == true){
            return false;
        }else{
            return true;
        }
    }
  },
  components: {
    TableLoader1,
    BOverlay,
    TopAffiliates,
    TopProduct,
    RecentSale,
    DateRange,
    ListStar,
    TodoList
  },
  mounted() {
    this.dateRange.startDate = moment(moment().subtract(14, "days").toDate());
    this.dateRange.endDate = moment(moment().toDate());
    // this.$refs.dateRange.dateRangText = "Last 14 Days";
    // this.endDate = moment( moment().subtract(1, "days").toDate()).unix() + 86399;
    // this.startDate = moment(moment().subtract(14, "days").toDate()).unix();
    // this.loadStatistics({
    //   startDate: this.startDate,
    //   endDate: this.endDate,
    // });
    // this.loadPerformance();
    this.loadCommissionStatistics();
    let a = {};
    a.startDate = moment().subtract(14, "days").toDate();
    a.endDate = moment().toDate();
    this.updateTimePicker(a);
    this.$refs.dateRange.dateRangText = "Last 14 Days";
  },
  methods: {
    openlink() {
      this.$router.push({
        name: "registration.customize",
        params: { id: this.idOfSlug},
      });
    },
    loadType(type) {
      this.type = type;
      this.loadPerformance();
    },
    loadPerformance() {
      this.loadPerformanceOverplay = true;
      let params = {
        type: this.type,
      };
      if (this.groupSelected) {
        params["group"] = this.groupSelected;
      }
      if (this.startDate) {
        params["start_date"] = this.startDate;
      }
      if (this.endDate) {
        params["end_date"] = this.endDate;
      }
      let resource = `/dashboard/performance`;
      ApiService.query(resource, {
        params: params,
      })
        .then(({ data }) => {
          let dataSeries = [];
          let totalCount = 0;
          while (params["start_date"] <= params["end_date"] - 86399) {
            let dateString = moment
              .unix(params["start_date"])
              .format("YYYY/MM/DD");
            dataSeries.push({ x: dateString, y: 0 });
            params["start_date"] += 86400;
          }
          let lengthDraw = dataSeries.length;
          let lengthLine = data.data.line.length;
          for (let i = 0; i < lengthDraw; i++) {
            for (let j = 0; j < lengthLine; j++) {
              if (
                this.convertUTCToLocalTime(data.data.line[j].times) ==
                dataSeries[i].x
              ) {
                if (data.data.type == "clicks") {
                  totalCount += parseInt(data.data.line[j].clicks);
                  dataSeries[i].y = data.data.line[j].clicks;
                } else {
                  if (data.data.type == "orders") {
                    totalCount += parseInt(data.data.line[j].orders);
                    dataSeries[i].y = data.data.line[j].orders;
                  } else {
                    totalCount += parseFloat(data.data.line[j].sales);
                    dataSeries[i].y = parseFloat(data.data.line[j].sales);
                  }
                }
              }
            }
          }
          for (let i = 0; i < lengthDraw; i++) {
            dataSeries[i].x = this.convertToShortMonth(
              moment(dataSeries[i].x, "YYYY/MM/DD").format("MMMM DD")
            );
          }

          let startTime = moment.unix(this.startDate).toDate();
          let endTime = moment.unix(this.endDate).toDate();
          if (this.monthDiff(startTime, endTime) >= 11) {
            dataSeries = [];
            totalCount = 0;
            let yearTime = startTime.getFullYear();
            for (let i = 0; i <= 11; i++) {
              dataSeries.push({
                x: new Date(yearTime, i, 1).toDateString().slice(4, 7),
                y: 0,
              });
            }
            let lengthSeries = dataSeries.length;
            for (let i = 0; i < lengthSeries; i++) {
              for (let j = 0; j < lengthLine; j++) {
                let month = this.convertToMonth(
                  this.convertUTCToLocalTime(data.data.line[j].times)
                );
                if (month == dataSeries[i].x) {
                  if (data.data.type == "clicks") {
                    totalCount += parseInt(data.data.line[j].clicks);
                    dataSeries[i].y += parseInt(data.data.line[j].clicks);
                  } else {
                    if (data.data.type == "orders") {
                      totalCount += parseInt(data.data.line[j].orders);
                      dataSeries[i].y += parseInt(data.data.line[j].orders);
                    } else {
                      totalCount += parseFloat(data.data.line[j].sales);
                      dataSeries[i].y += parseFloat(data.data.line[j].sales);
                    }
                  }
                }
              }
            }
          }
          this.type = data.data.type;
          this.total = totalCount;
          this.series1 = [
            {
              name: data.data.type,
              data: dataSeries,
            },
          ];
        })
        .finally(() => {
          this.loadPerformanceOverplay = false;
        });
    },
    convertToShortMonth(timeString) {
      let month = timeString.slice(0, 3);
      let day = timeString.slice(-2);
      return day + " " + month;
    },
    convertToMonth(timeString) {
      let month = moment(timeString, "YYYY/MM/DD").format("MMMM").slice(0, 3);
      return month;
    },
    monthDiff(d1, d2) {
      let months;
      months = (d2.getFullYear() - d1.getFullYear()) * 12;
      months -= d1.getMonth();
      months += d2.getMonth();
      return months <= 0 ? 0 : months;
    },
    convertUTCToLocalTime(time) {
      let stillUtc = moment.utc(time).toDate();
      let local = moment(stillUtc).local().format("YYYY/MM/DD");
      return local; // 2015-09-13 09:39:27
    },
    updateTimePicker(value) {
      this.startDate = moment(
        this.formatTimeRangeDatePicker(value.startDate),
        "YYYY-MM-DD"
      ).unix();
      this.endDate =
        moment(
          this.formatTimeRangeDatePicker(value.endDate),
          "YYYY-MM-DD"
        ).unix() + 86399;
      this.loadPerformance();
      this.loadStatistics({
        startDate: this.startDate,
        endDate: this.endDate,
      });
    },
    updatedateRangText(e){
        this.dateRangText = e;
    },
    /**clear thì gán mặc định 14days last */
    cancelFilterDate() {
        let a = {};
        a.startDate = moment().subtract(14, "days").toDate();
        a.endDate = moment().toDate();
        this.updateTimePicker(a);
        this.dateRangText = "Last 14 Days";
    },
    changeSelectGroup() {
      this.loadPerformance();
    },
    loadDashboardData() {},
    loadStatistics(value) {
      this.loadingEmailTemplateTable = true;

      let params = {
        end_date: value.endDate,
        start_date: value.startDate,
      };
      let resource = "conversions/get-statistics";
      ApiService.query(resource, { params: params }).then(({ data }) => {
        this.statistics = data.data;
        this.loadingEmailTemplateTable = false;
      });
    },
    loadCommissionStatistics() {
      let resource = "conversions/commission-statistics";
      ApiService.query(resource).then(({ data }) => {
        let newData = [];
        let value = 0;
        for (const key in data.data) {
          if (key == "timestamp") {
            break;
          }
          newData.push(parseFloat(data.data[key]));
          value += parseFloat(data.data[key]);
        }
        this.series = newData;
        if (!value) {
          this.chartHaveData = false;
        } else {
          this.chartHaveData = true;
        }
      });
    },
  },
};
</script>

<style scoped>
.banner-bixgrow{
    background-color: #FFF4DE !important;
    border: solid 1px#FFA800 !important;
}
.alert.alert-custom{
    padding: 0.75rem 1rem !important;
}
.icon-move-right i {
    transition: all .2s cubic-bezier(.34,1.61,.7,1.3);
}
.icon-move-right:hover i{
    margin-left: 5px;
}
.text-sm {
  font-size: 0.875rem !important;
  line-height: 1.5;
}
.card-custom {
  position: relative;
  display: flex;
  flex-direction: column;
  min-width: 0;
  word-wrap: break-word;
  background-color: #fff;
  background-clip: border-box;
  border: 0 solid rgba(0, 0, 0, 0.125);
  border-radius: 1rem;
  box-shadow: 0 20px 27px 0 rgb(0 0 0 / 5%);
}
.symbol-label-custom {
  border: solid 1px #3f4254;
}
.link-primary {
  cursor: pointer;
}
.border-dash{
    display: flex;
    align-items: center;
    margin-right: 10px;
    margin-bottom: 10px;
    padding: 10px;
    border: 1px dashed rgb(139, 139, 139) !important;
    border-radius: 5px;
}
.symbol.symbol-bixgrow .symbol-label {
    background-color: #3f4254;
    color: #fbfbfb;
}
</style>
<style>
.tip-alert-dashboard .alert-custom{
    padding: 0.75rem 1rem !important;
}
</style>
