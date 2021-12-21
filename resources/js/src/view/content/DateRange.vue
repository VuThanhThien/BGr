<template>
  <div >
    <date-range-picker
        v-model="dateRange"
        opens="center"
        :ranges="ranges"
        @update="updateTimePicker"
        placeholder="Select date range"
    >
        <template
        :locale-data="locale"
        #input="picker"
        style=""
        v-if="showDateRange"
        >
        <p class="text-muted">
            <i
            class="icon-lg text-dark-50 far fa-calendar-alt mr-2"
            ></i>
            {{dateRangText}}
        </p>
        </template>
    </date-range-picker>
    <span @click="cancelFilterDate" v-if="isShowCancel" class="cancel-filter-date">x</span>
  </div>
</template>

<script>
import DateRangePicker from "vue2-daterange-picker";
import "vue2-daterange-picker/dist/vue2-daterange-picker.css";
import moment from "moment";
export default {
    components: {
        DateRangePicker
    },
    data() {
        let startDate = null;
        let endDate = null;
        let today = new Date();
        today.setHours(0, 0, 0, 0);
        let yesterday = new Date();
        yesterday.setDate(today.getDate() - 1);
        yesterday.setHours(0, 0, 0, 0);
    return {
        isShowCancel: false,
        dateRange: { startDate, endDate },
        dateRangText: 'Filter by date',
        showDateRange: true,
        ranges: {
            Today: [moment().toDate(), moment().toDate()],
            Yesterday: [
            moment().subtract(1, "days").toDate(),
            moment().subtract(1, "days").toDate(),
            ],
            "Last 7 Days": [
            moment().subtract(7, "days").toDate(),
            moment().toDate(),
            ],
            "Last 14 Days": [
            moment().subtract(14, "days").toDate(),
            moment().toDate(),
            ],
            "Last 30 Days": [
            moment().subtract(30, "days").toDate(),
            moment().toDate(),
            ],
            "Last 90 Days": [
            moment().subtract(90, "days").toDate(),
            moment().toDate(),
            ],
            "Last Week": [
            moment().subtract(1, "week").startOf("week").toDate(),
            moment().subtract(1, "week").endOf("week").toDate(),
            ],
            "This Month": [
            moment().startOf("month").toDate(),
            moment().endOf("month").toDate(),
            ],
            "Last Month": [
            moment().subtract(1, "month").startOf("month").toDate(),
            moment().subtract(1, "month").endOf("month").toDate(),
            ],
            "This Year": [
            moment().startOf("year").toDate(),
            moment().endOf("year").toDate(),
            ],
        },
    }
    },
    methods: {
        updateTimePicker(value) {
            this.selectTimePicker(value);
            this.$emit('updateTimePicker', value);
            this.updatedateRangText();
            this.isShowCancel = true
        },
        updatedateRangText(){
            this.$emit('updatedateRangText', this.dateRangText);
        },
        cancelFilterDate() {
            this.$emit('cancelFilterDate');
            this.dateRangText = "Filter by Date";
            this.showDateRange = true;
            this.isShowCancel = false;
        },
        /**Hàm lấy tên range (today, yesterday thay cho range date thông thường) */
        selectTimePicker(value) {
            /**Lấy unix của value
             * startdate startDate
             * enddate endDate
             */
            var startdate = moment(value.startDate).unix();
            var enddate = moment(value.endDate).unix();
            /**Unix của today */
            var todaystart = moment(this.ranges.Today[0]).unix();
            var todayend = moment(this.ranges.Today[1]).unix();
            /**unix cua yesterday */
            var yesterdaystart = moment(this.ranges.Yesterday[0]).unix();
            var yesterdayend = moment(this.ranges.Yesterday[1]).unix();
            /**unix ThisMonth*/
            var thismonthstart = moment(this.ranges["This Month"][0]).unix();
            var thismonthend = moment(this.ranges["This Month"][1]).unix();
            /**unix ThisYear*/
            var thisyearstart = moment(this.ranges["This Year"][0]).unix();
            var thisyearend = moment(this.ranges["This Year"][1]).unix();
            /**unix LastWeek*/
            var lastweekstart = moment(this.ranges["Last Week"][0]).unix();
            var lastweekend = moment(this.ranges["Last Week"][1]).unix();
            /**unix LastMonth*/
            var lastmonthstart = moment(this.ranges["Last Month"][0]).unix();
            var lastmonthend = moment(this.ranges["Last Month"][1]).unix();
            var last7daystart = moment(this.ranges["Last 7 Days"][0]).unix();
            var last7dayend = moment(this.ranges["Last 7 Days"][1]).unix();
            /**unix ThisYear*/
            var last14daystart = moment(this.ranges["Last 14 Days"][0]).unix();
            var last14dayend = moment(this.ranges["Last 14 Days"][1]).unix();
            /**unix LastWeek*/
            var last30daystart = moment(this.ranges["Last 30 Days"][0]).unix();
            var last30dayend = moment(this.ranges["Last 30 Days"][1]).unix();
            /**unix LastMonth*/
            var last90daystart = moment(this.ranges["Last 90 Days"][0]).unix();
            var last90dayend = moment(this.ranges["Last 90 Days"][1]).unix();
            if (startdate == todaystart && enddate == todayend) {
                (this.dateRangText = "Today"), (this.showDateRange = true);
            } else if (startdate == yesterdaystart && enddate == yesterdayend) {
                (this.dateRangText = "Yesterday"), (this.showDateRange = true);
            } else if (startdate == thismonthstart && enddate == thismonthend) {
                (this.dateRangText = "This Month"), (this.showDateRange = true);
            } else if (startdate == thisyearstart && enddate == thisyearend) {
                (this.dateRangText = "This Year"), (this.showDateRange = true);
            } else if (startdate == lastweekstart && enddate == lastweekend) {
                (this.dateRangText = "Last Week"), (this.showDateRange = true);
            } else if (startdate == lastmonthstart && enddate == lastmonthend) {
                (this.dateRangText = "Last Month"), (this.showDateRange = true);
            } else if (startdate == last7daystart && enddate == last7dayend) {
                (this.dateRangText = "Last 7 Days"), (this.showDateRange = true);
            } else if (startdate == last14daystart && enddate == last14dayend) {
                (this.dateRangText = "Last 14 Days"), (this.showDateRange = true);
            } else if (startdate == last30daystart && enddate == last30dayend) {
                (this.dateRangText = "Last 30 Days"), (this.showDateRange = true);
            } else if (startdate == last90daystart && enddate == last90dayend) {
                (this.dateRangText = "Last 90 Days"), (this.showDateRange = true);
            } else {
                this.showDateRange = true;
                this.dateRangText = `${this.formatTimeRangeDatePicker(
                value.startDate
                )}  -  ${this.formatTimeRangeDatePicker(value.endDate)}`;
            }
        },
    }
}
</script>
<style >
.calendars{
    height: 325px ;
}
</style>
