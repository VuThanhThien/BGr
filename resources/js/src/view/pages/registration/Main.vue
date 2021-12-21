<template>
<div>
    <alert-note :type="'alert-white alert-shadow'">
    <span class="alert-heading" style="font-size: 15px; font-weight: 600;">Customize your affiliate registration page</span>
    <br/>
    To include Terms & Conditions and Privacy Policy in the registration form,
    <a class="font-weight-bold" href="#/settings/term_condition">go here.</a>
    <br/>
    Learn more about
    <a class="font-weight-bold" target="_blank" href="https://docs.bixgrow.com/manage/program/customize-affiliate-registration-page">
    Affiliate registration page.</a>
    </alert-note>
  <div class="card card-custom gutter-b">
    <!--begin::Header-->
      <div class="card-header border-0 py-5">
        <h3 class="card-title align-items-start flex-column">
          <span class="card-label font-weight-bolder text-bixgrow">
            Affiliate Registration
          </span>
          <span class="text-muted mt-3 font-weight-bold font-size-sm"> </span>
        </h3>
      </div>
    <div class="card-body pb-10 pt-0">
    <label class="font-weight-bolder">Select program: </label>
      <select
        class="form-control form-control-custom"
        v-model="groupSelected"
        @change="redirect($event)"
        >
        <option
            v-for="(o, index) in groups"
            :value="o.id"
            :o="o"
            :key="index"
        >
            {{ o.is_default==1 ? o.name + '( Program default)': o.name }}
        </option>
        </select>
      <router-view :key="$route.fullPath"/>
    </div>
  </div>
</div>
</template>

<script>
import { mapGetters, mapActions } from "vuex";
export default {
  data() {
    return {
        groupSelected:""
    };
  },
  methods: {
    ...mapActions(["loadGroup", "changeBaseObj", "updateRegistration"]),
    redirect(e){
        this.$router.push({
        name: "registration.edit",
        params: { id: e.target.value },
      });
    }
  },
  computed: {
    ...mapGetters([
      "groups",
    ]),
    defaultID(){
        let groupDefault =this.groups.filter(group=>(group.is_default == 1));
        return groupDefault[0].id;
    }
  },
  created(){
    if(this.$route.params.id == 0){
        this.groupSelected = this.defaultID;
        this.$router.push({
            name: "registration.edit",
            params: { id: this.defaultID},
        });
    }else{
        this.groupSelected = this.$route.params.id;
    }
  },
    watch: {
    '$route' (to, from) {
        if(this.$route.params.id == 0 || this.$route.params.id < 1){
        if(to != from){
            this.$router.push({
            name: "registration.edit",
            params: { id: this.defaultID},
        });
        }
    }else{
        this.groupSelected = this.$route.params.id;
    }
    }
},
};
</script>
