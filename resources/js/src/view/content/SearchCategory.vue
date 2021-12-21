<template>
    <v-select
    ref="v_select"
    @search="onSearchCategory"
    :options="categoryOptions"
    label="name"
    :filterable="false"
    :value="categoryFilterSelected"
    @input="setCategorySelected"
    placeholder="Type to search or create category"
    >
    <template slot="option" slot-scope="option">
        <div class="d-center" v-html="option.name">
        </div>
    </template>
    </v-select>
</template>

<script>
import ApiService from "@/core/services/api.service";
import vSelect from "vue-select";
export default {
props:[],
components:{vSelect},
data() {
    return {
      categoryOptions: [],
      categoryFilterSelected: null,
    }
},
methods:{
    onSearchCategory(search, loading) {
      if (search.length){
          loading(true);
          this.searchCategory(loading, search, this);
      }
    },
    setCategorySelected(value) {
        if(value){
        let findIndex=value.name.indexOf(`Create "`);
      if(findIndex>=0)
      {
         let finallyIndex= value.name.lastIndexOf('"');
         value.name=value.name.slice(8,finallyIndex);
      }  
        }

      this.categoryFilterSelected = value;
      this.$emit('input', value);
    },
    searchCategory: _.debounce((loading, search, vm) => {
      let resource = "categorys/search?query=" + escape(search);
      ApiService.query(resource)
      .then(({ data }) => {
          if(data.length>0)
          {
              let index=data.findIndex(i=>i.name==search);
              if(index<0)
              {
                data.push({id:0,name:`Create "${search}"`});
              }
          }
          else{
          data.push({id:0,name:`Create "${search}"`});
          }
        vm.categoryOptions = data;
        loading(false);
      }).catch((response)=>{
        // console.log(response);
        setTimeout(() => {
          loading(false);
        }, 2000);
      });
    }),
}
}
</script>
<style lang="scss" >
.vs__dropdown-toggle {
  padding: 0!important;
  height: calc(1.5em + 1.65rem + 2px);
}

</style>
