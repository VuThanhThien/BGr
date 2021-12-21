import ApiService from "@/core/services/api.service";

const state={
    status:{
        affiliate_review:1,
        approved_affiliate:1,
        denied_affiliate:1,
        new_conversion:1,
        approved_conversion:1,
        denied_conversion:1,
        commission_payout:1
    }
}
const mutations={
    getListStatus(state,payload){
        payload.forEach(element => {
            if(element.slug=='affiliate_review')
                state.status.affiliate_review=element.status;
            if(element.slug=='denied_affiliate')
                state.status.denied_affiliate=element.status;
            if(element.slug=='approved_affiliate')
                state.status.approved_affiliate=element.status;
            if(element.slug=='new_conversion')
                state.status.new_conversion=element.status;
            if(element.slug=='approved_conversion')
                state.status.approved_conversion=element.status;
            if(element.slug=='denied_conversion')
                state.status.denied_conversion=element.status;
             if(element.slug=='commission_payout')
                state.status.commission_payout=element.status;
        });
       
    }
}
const actions={
    getListStatus(context)
    {
        ApiService.query('emailtemplates/getEmailTemplateStatusByShopId').
        then(({data})=>{
            context.commit('getListStatus',data.data);
        })
    }
}
export default {
    state,
    mutations,
    actions
}