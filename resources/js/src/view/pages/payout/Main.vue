<template>
<div>
    <alert-note :type="'alert-white alert-shadow'">
        If you have not selected a payment method, click <a class="font-weight-bold" href="#/settings/payment">here</a>.
        <br>
        Learn more about
        <a class="font-weight-bold" target="_blank" href="https://docs.bixgrow.com/manage/payouts">payouts.</a>
    </alert-note>

    <div class="card card-custom  gutter-b">
        <b-tabs card nav-class="nav-tabs-line group-nav">
          <b-tab title="Pending Payments" lazy><PendingPaymentsTab></PendingPaymentsTab></b-tab>
          <b-tab title="Payment History" lazy>
            <PaymentHistoryTab  :showAffiliate="true"></PaymentHistoryTab>
          </b-tab>
        </b-tabs>
    </div>
</div>
</template>

<script>
import PendingPaymentsTab from "@/view/pages/payout/partials/PendingPaymentsTab.vue";
import PaymentHistoryTab from "@/view/pages/payout/partials/PaymentHistoryTab.vue";
import { BTabs,	BTab } from 'bootstrap-vue'
export default {
    components: {
        PendingPaymentsTab,
        PaymentHistoryTab,
        BTabs,	BTab
    },
}
</script>

<style>
.group-nav > .nav-item > .nav-link.active, .nav-link:hover{
    color: #ff9900;
}
.group-nav .nav-link:hover:not(.disabled), .nav.nav-tabs.nav-tabs-line .nav-link.active, .nav.nav-tabs.nav-tabs-line .show > .nav-link{
    border-bottom: 1px solid #ff9900 !important;
    color : #ff9900 !important;
}
</style>
