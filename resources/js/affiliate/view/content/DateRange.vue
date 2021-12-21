<template>
  <div >
    <date-range-picker
        v-model="dateRange"
        opens="center"
        :ranges="ranges"
        :locale-data="localeData"
        @update="updateTimePicker"
        placeholder="Select date range"
    >
        <template
        #input="picker"
        style=""
        v-if="showDateRange"
        >
        <p class="text-muted">
            <i
            class="icon-lg text-dark-50 far fa-calendar-alt mr-2"
            ></i>
            {{getDateRageText}}
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
    // props:["defaultFilterText"],
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
            dateRangText: "",
            showDateRange: true,
            // #region unix
            /**Unix của today */
            todaystart : moment().toDate(),
            todayend : moment().toDate(),
                /**unix cua yesterday */
            yesterdaystart : moment().subtract(1, "days").toDate(),
            yesterdayend : moment().subtract(1, "days").toDate(),
                /**unix ThisMonth*/
            thismonthstart : moment().startOf("month").toDate(),
            thismonthend : moment().endOf("month").toDate(),
                /**unix ThisYear*/
            thisyearstart : moment().startOf("year").toDate(),
            thisyearend : moment().endOf("year").toDate(),
                /**unix LastWeek*/
            lastweekstart : moment().subtract(1, "week").startOf("week").toDate(),
            lastweekend : moment().subtract(1, "week").endOf("week").toDate(),
                /**unix LastMonth*/
            lastmonthstart : moment().subtract(1, "month").startOf("month").toDate(),
            lastmonthend : moment().subtract(1, "month").endOf("month").toDate(),
            last7daystart : moment().subtract(7, "days").toDate(),
            last7dayend : moment().subtract(1, "days").toDate(),
                /**unix ThisYear*/
            last14daystart : moment().subtract(14, "days").toDate(),
            last14dayend : moment().subtract(1, "days").toDate(),
                /**unix LastWeek*/
            last30daystart : moment().subtract(30, "days").toDate(),
            last30dayend : moment().subtract(1, "days").toDate(),
                /**unix LastMonth*/
            last90daystart : moment().subtract(90, "days").toDate(),
            last90dayend : moment().subtract(1, "days").toDate(),
            //#endregion unix
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
            this.dateRangText = this.$t('filter_by_date');
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

            if (startdate == moment(this.todaystart).unix() && enddate == moment(this.todayend).unix()) {
                (this.dateRangText = this.$t('today')), (this.showDateRange = true);
            } else if (startdate == moment(this.yesterdaystart).unix() && enddate == moment(this.yesterdayend).unix()) {
                (this.dateRangText = this.$t('yesterday')), (this.showDateRange = true);
            } else if (startdate == moment(this.thismonthstart).unix() && enddate == moment(this.thismonthend).unix()) {
                (this.dateRangText = this.$t('this_month')), (this.showDateRange = true);
            } else if (startdate == moment(this.thisyearstart).unix() && enddate == moment(this.thisyearend).unix()) {
                (this.dateRangText = this.$t('this_year')), (this.showDateRange = true);
            } else if (startdate == moment(this.lastweekstart).unix() && enddate == moment(this.lastweekend).unix()) {
                (this.dateRangText = this.$t('last_week')), (this.showDateRange = true);
            } else if (startdate == moment(this.lastmonthstart).unix() && enddate == moment(this.lastmonthend).unix()) {
                (this.dateRangText = this.$t('last_month')), (this.showDateRange = true);
            } else if (startdate == moment(this.last7daystart).unix() && enddate == moment(this.last7dayend).unix()) {
                (this.dateRangText = this.$t('last_7_days')), (this.showDateRange = true);
            } else if (startdate == moment(this.last14daystart).unix() && enddate == moment(this.last14dayend).unix()) {
                (this.dateRangText = this.$t('last_14_days')), (this.showDateRange = true);
            } else if (startdate == moment(this.last30daystart).unix() && enddate == moment(this.last30dayend).unix()) {
                (this.dateRangText = this.$t('last_30_days')), (this.showDateRange = true);
            } else if (startdate == moment(this.last90daystart).unix() && enddate == moment(this.last90dayend).unix()) {
                (this.dateRangText = this.$t('last_90_days')), (this.showDateRange = true);
            } else {
                this.showDateRange = true;
                this.dateRangText = `${this.formatTimeRangeDatePicker(
                value.startDate
                )}  -  ${this.formatTimeRangeDatePicker(value.endDate)}`;
            }
        },

    },
    computed:{
        //custom range theo ngôn ngữ
        ranges(){
            let lang = this.$i18n.locale;
            switch (lang) {
                case 'en':
                    return {
                        "Today": [this.todaystart, this.todayend],
                        "Yesterday": [this.yesterdaystart,this.yesterdayend],
                        "Last 7 Days": [this.last7daystart,this.last7dayend],
                        "Last 14 Days": [this.last14daystart,this.last14dayend],
                        "Last 30 Days": [this.last30daystart,this.last30dayend],
                        "Last 90 Days": [this.last90daystart,this.last90dayend],
                        "Last Week": [this.lastweekstart,this.lastweekend],
                        "This Month": [this.thismonthstart,this.thismonthend],
                        "Last Month": [this.lastmonthstart,this.lastmonthend],
                        "This Year": [this.thisyearstart,this.thisyearend],
                    }
                case 'de':
                    return {
                        "Heute": [this.todaystart, this.todayend],
                        "Gestern": [this.yesterdaystart,this.yesterdayend],
                        "Letzten 7 Tage": [this.last7daystart,this.last7dayend],
                        "Letzten 14 Tage": [this.last14daystart,this.last14dayend],
                        "Letzten 30 Tage": [this.last30daystart,this.last30dayend],
                        "Letzten 90 Tage": [this.last90daystart,this.last90dayend],
                        "letzte Woche": [this.lastweekstart,this.lastweekend],
                        "Diesen Monat": [this.thismonthstart,this.thismonthend],
                        "Letzten Monat": [this.lastmonthstart,this.lastmonthend],
                        "Dieses Jahr": [this.thisyearstart,this.thisyearend],
                    }
                case 'it':
                    return {
                        "Oggi": [this.todaystart, this.todayend],
                        "Ieri": [this.yesterdaystart,this.yesterdayend],
                        "Ultimi 7 giorni": [this.last7daystart,this.last7dayend],
                        "Ultimi 14 giorni": [this.last14daystart,this.last14dayend],
                        "Ultimi 30 giorni": [this.last30daystart,this.last30dayend],
                        "Ultimi 90 giorni": [this.last90daystart,this.last90dayend],
                        "la settimana scorsa": [this.lastweekstart,this.lastweekend],
                        "Questo mese": [this.thismonthstart,this.thismonthend],
                        "Lo scorso mese": [this.lastmonthstart,this.lastmonthend],
                        "Quest'anno": [this.thisyearstart,this.thisyearend],
                    }
                case 'zh':
                    return {
                        "今天": [this.todaystart, this.todayend],
                        "昨天": [this.yesterdaystart,this.yesterdayend],
                        "过去 7 天": [this.last7daystart,this.last7dayend],
                        "过去 14 天": [this.last14daystart,this.last14dayend],
                        "过去 30 天": [this.last30daystart,this.last30dayend],
                        "过去 90 天": [this.last90daystart,this.last90dayend],
                        "上个星期": [this.lastweekstart,this.lastweekend],
                        "这个月": [this.thismonthstart,this.thismonthend],
                        "上个月": [this.lastmonthstart,this.lastmonthend],
                        "今年": [this.thisyearstart,this.thisyearend],
                    }
                case 'es':
                    return {
                        "Hoy dia": [this.todaystart, this.todayend],
                        "El dia de ayer": [this.yesterdaystart,this.yesterdayend],
                        "Los últimos 7 días": [this.last7daystart,this.last7dayend],
                        "Últimos 14 días": [this.last14daystart,this.last14dayend],
                        "Últimos 30 días": [this.last30daystart,this.last30dayend],
                        "Últimos 90 días": [this.last90daystart,this.last90dayend],
                        "La semana pasada": [this.lastweekstart,this.lastweekend],
                        "Este mes": [this.thismonthstart,this.thismonthend],
                        "El mes pasado": [this.lastmonthstart,this.lastmonthend],
                        "Este año": [this.thisyearstart,this.thisyearend],
                    }
                case 'fr':
                    return {
                        "Aujourd'hui": [this.todaystart, this.todayend],
                        "Hier": [this.yesterdaystart,this.yesterdayend],
                        "Les 7 derniers jours": [this.last7daystart,this.last7dayend],
                        "14 derniers jours": [this.last14daystart,this.last14dayend],
                        "Les 30 derniers jours": [this.last30daystart,this.last30dayend],
                        "90 derniers jours": [this.last90daystart,this.last90dayend],
                        "La semaine dernière": [this.lastweekstart,this.lastweekend],
                        "Ce mois-ci": [this.thismonthstart,this.thismonthend],
                        "Le mois dernier": [this.lastmonthstart,this.lastmonthend],
                        "Cette année": [this.thisyearstart,this.thisyearend],
                    }
                case 'pt':
                    return {
                        "Hoje": [this.todaystart, this.todayend],
                        "Ontem": [this.yesterdaystart,this.yesterdayend],
                        "Últimos 7 dias": [this.last7daystart,this.last7dayend],
                        "Últimos 14 dias": [this.last14daystart,this.last14dayend],
                        "Últimos 30 dias": [this.last30daystart,this.last30dayend],
                        "Últimos 90 dias": [this.last90daystart,this.last90dayend],
                        "Semana Anterior": [this.lastweekstart,this.lastweekend],
                        "Este mês": [this.thismonthstart,this.thismonthend],
                        "Mês passado": [this.lastmonthstart,this.lastmonthend],
                        "Este ano": [this.thisyearstart,this.thisyearend],
                    }
                default:
                    return {
                        Today: [this.todaystart, this.todayend],
                        Yesterday: [this.yesterdaystart,this.yesterdayend],
                        "Last 7 Days": [this.last7daystart,this.last7dayend],
                        "Last 14 Days": [this.last14daystart,this.last14dayend],
                        "Last 30 Days": [this.last30daystart,this.last30dayend],
                        "Last 90 Days": [this.last90daystart,this.last90dayend],
                        "Last Week": [this.lastweekstart,this.lastweekend],
                        "This Month": [this.thismonthstart,this.thismonthend],
                        "Last Month": [this.lastmonthstart,this.lastmonthend],
                        "This Year": [this.thisyearstart,this.thisyearend],
                    }
            }
        },
        //locale calendar theo ngôn ngữ
        localeData(){
            let lang = this.$i18n.locale;
            switch (lang) {
                case 'en':
                    return {
                        direction: 'ltr',
                        format: 'mm/dd/yyyy',
                        separator: ' - ',
                        applyLabel: 'Apply',
                        cancelLabel: 'Cancel',
                        weekLabel: 'W',
                        customRangeLabel: 'Custom Range',
                        daysOfWeek: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
                        monthNames: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                        firstDay: 0
                    }
                case 'de':
                    return {
                        direction: 'ltr',
                        format: 'mm/dd/yyyy',
                        separator: ' - ',
                        applyLabel: 'Anwenden',
                        cancelLabel: 'Abbrechen',
                        weekLabel: 'W',
                        customRangeLabel: 'Custom Range',
                        daysOfWeek: ['Son', 'Mon', 'Die', 'Mit', 'Don', 'Frei', 'Sam'],
                        monthNames: ['Jan', 'Feb', 'März', 'Apr', 'Mai', 'Juni', 'Juli', 'Aug', 'Sep', 'Okt', 'Nov', 'Dez'],
                        firstDay: 0
                    }
                case 'it':
                    return {
                        direction: 'ltr',
                        format: 'mm/dd/yyyy',
                        separator: ' - ',
                        applyLabel: 'Applicare',
                        cancelLabel: 'Annulla',
                        weekLabel: 'W',
                        customRangeLabel: 'Custom Range',
                        daysOfWeek: ['Dom', 'lun', 'Mar', 'Mer', 'Gio', 'Ven', 'Sab'],
                        monthNames: ['Gen', 'Feb', 'Mar', 'Apr', 'Mag', 'Giu', 'Lug', 'Ago', 'Set', 'Ott', 'Nov', 'Dic'],
                        firstDay: 0
                    }
                case 'zh':
                    return {
                        direction: 'ltr',
                        format: 'yyyy/mm/dd',
                        separator: ' - ',
                        applyLabel: '申请',
                        cancelLabel: '取消',
                        weekLabel: 'W',
                        customRangeLabel: 'Custom Range',
                        daysOfWeek: ['一', '二', '三', '四', '五', '六', '日'],
                        monthNames: ['01月', '02月', '03月', '04月', '05月', '06月', '07月', '08月', '09月', '10月', '11月', '12月'],
                        firstDay: 0
                    }
                case 'es':
                    return {
                        direction: 'ltr',
                        format: 'mm/dd/yyyy',
                        separator: ' - ',
                        applyLabel: 'Solicitar',
                        cancelLabel: 'Cancelar',
                        weekLabel: 'W',
                        customRangeLabel: 'Custom Range',
                        daysOfWeek: ['Dom', 'Lun', 'Mar', 'Mié', 'Ju', 'Vie', 'Sáb'],
                        monthNames: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                        firstDay: 0
                    }
                case 'fr':
                    return {
                        direction: 'ltr',
                        format: 'mm/dd/yyyy',
                        separator: ' - ',
                        applyLabel: 'Apply',
                        cancelLabel: 'Cancel',
                        weekLabel: 'W',
                        customRangeLabel: 'Custom Range',
                        daysOfWeek: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
                        monthNames: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                        firstDay: 0
                    }
                case 'pt':
                    return {
                        direction: 'ltr',
                        format: 'mm/dd/yyyy',
                        separator: ' - ',
                        applyLabel: 'Aplicar',
                        cancelLabel: 'Cancelar',
                        weekLabel: 'W',
                        customRangeLabel: 'Custom Range',
                        daysOfWeek: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Se', 'Sáb'],
                        monthNames: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
                        firstDay: 0
                    }
                default:
                    return {
                        direction: 'ltr',
                        format: 'mm/dd/yyyy',
                        separator: ' - ',
                        applyLabel: 'Appliquer',
                        cancelLabel: 'Annuler',
                        weekLabel: 'W',
                        customRangeLabel: 'Custom Range',
                        daysOfWeek: ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'],
                        monthNames: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Aou', 'Sep', 'Oct', 'Nov', 'Déc'],
                        firstDay: 0
                    }
            }
        },
        // Lấy dòng hiển thị text daterange
        getDateRageText(){
            if(this.dateRangText == "")
            {
                return this.$t('filter_by_date');
            }
            else
            {
                return this.dateRangText;
            }
        }
    },
}
</script>
<style >
