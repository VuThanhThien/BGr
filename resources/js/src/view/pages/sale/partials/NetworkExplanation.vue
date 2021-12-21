<template>
    <div>
      <div class="table-responsive">
        <table
          class="
            table
            table-head-custom
            table-vertical-center
            table-head-bg
            table-bordered
          "
        >
          <thead>
            <tr class="text-left">
              <th class="pl-7" style="min-width: 100px">
                <span class="text-dark-75">Affiliate</span>
              </th>
              <th style="min-width: 120px">Source</th>
              <th style="min-width: 100px">Commission</th>
            </tr>
          </thead>
          <tbody>
              <template v-if="network.length>0">
              <tr v-for="(net,i) in network" :key="i">
                  <td style="min-width: 100px">
                       <span >
                      {{net.first_name+ " "+net.last_name}}
                       </span>
                  </td>
                  <td>
                       <span>
                       {{getLevel(net.level)}}
                       </span>
                  </td>
                  <td>
                       <span >
                            {{formatMoney(net.commission, format)}}
                       </span>
                  </td>            
              </tr>
                            <tr class="bg-primary-o-50 font-weight-bolder font-size-lg text-dark-75">
                  <td colspan="2">
                      <span >
                        GRAND TOTAL
                       </span>
                  </td>
                  <td>
                     {{formatMoney(totalCommission(), format)}}
                  </td>
              </tr>
              </template>
              <template v-else>
                <tr>
                  <td colspan="3" style="text-align: center;color: #B5B5C3 !important;">
                    <i class="far fa-folder-open icon-3x"></i>
                    <h6>No Data</h6>
                  </td>
                </tr>
              </template>
          </tbody>
        </table>
      </div>
    </div>
</template>
<script>
import ApiService from '@/core/services/api.service';
export default {
    props:['id'],
    data(){
        return{
            network:[]
        }
    },
    methods:{
        getNetworkExplanation(){
            let resource=`conversions/${this.id}/network-explanation`;
            ApiService.query(resource).then(({data})=>{
                this.network=data.data;
            })
        },
        totalCommission(){
              let lengthCommission=this.network.length;
                              let total=0;
            if(lengthCommission)
            {
                for(let i=0;i<lengthCommission;i++)
                {
                    total+=parseFloat(this.network[i].commission);
                }
            }
            return total;
        },
    getLevel(level){
        if(level)
        {
            return 'Level '+level;
        }
        else
            return 'Sale';
    }
    },
    mounted(){
        this.getNetworkExplanation();
    }
}
</script>
