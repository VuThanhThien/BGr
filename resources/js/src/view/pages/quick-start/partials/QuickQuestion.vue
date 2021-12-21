<template>
    <div class="col-lg-5 mx-auto mt-30">
        <div class="card card-custom">
        <div class="card-body d-flex flex-column align-items-center">
         <span class="svg-icon-success svg-icon-3x font-weight-bolder svg-icon mb-10">
            <inline-svg src="media/svg/icons/Code/Question-circle.svg" />
            <span class="font-size-h3">Quick question</span>
          </span>
         <span class="font-weight-bold font-size-h4 mb-20 text-center">
             Are you familiar with Afiliate Marketing ?
         </span>
         <div class="form-group">
            <!-- <label>Default radios</label> -->
            <div class="radio-list">
                <label class="radio">
                    <input type="radio" value="NO" v-model="picked" name="radios1"/>
                    <span></span>
                    No, I know nothing about Affiliate Marketing
                </label>
                <label class="radio">
                    <input type="radio" value="YES" v-model="picked"  name="radios1"/>
                    <span></span>
                    Yes, I know what Affiliate Marketing is
                </label>
            </div>
        </div>
        </div>
            <div class="card-footer d-flex justify-content-end">
                <a :href="'#/quick-start/'+ this.picked" class="btn btn-primary font-weight-bold">Next</a>
        </div>
    </div>
    </div>
</template>

<script>
export default {
    data(){
        return{
            picked:"",
        }
    },
}
</script>
