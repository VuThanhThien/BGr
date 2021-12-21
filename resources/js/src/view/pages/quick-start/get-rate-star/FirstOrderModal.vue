<template>
<div class="rate-star">
    <b-modal
    no-close-on-backdrop
    no-close-on-esc
    size="md"
    ref="rate-star"
    body-bg-variant="light"
    footer-bg-variant="light"
    header-bg-variant="light"
    header-class="border-bottom-0"
    footerClass= "border-top-0 pt-0"
    bodyClass="pb-0"
    centered
    >
    <div class="d-flex justify-content-center">
        <img class="fire-work-img"  src="media/getstarted/fire-work.png"/>
    </div>
    <h1 class="text-center caption mt-5">Congratulations!</h1>
    <h3 class="text-center mt-10">You’ve just got your very first order on BixGrow. </h3>
    <p class="text-center">Could you tell us about your current experience? It will just take a minute and you’ll help the app to go forward.</p>
    <template #modal-footer>
        <div class="w-100 mx-auto">
            <div class="d-flex justify-content-center">
                <section id="rate" class="rating">
                <!-- FIFTH STAR -->
                <input type="radio" id="star_5" name="rate" value="5" @change="onClickStar($event)"/>
                <label for="star_5" title="Five">&#9733;</label>
                <!-- FOURTH STAR -->
                <input type="radio" id="star_4" name="rate" value="4" @change="onClickStar($event)"/>
                <label for="star_4" title="Four">&#9733;</label>
                <!-- THIRD STAR -->
                <input type="radio" id="star_3" name="rate" value="3" @change="onClickStar($event)"/>
                <label for="star_3" title="Three">&#9733;</label>
                <!-- SECOND STAR -->
                <input type="radio" id="star_2" name="rate" value="2" @change="onClickStar($event)"/>
                <label for="star_2" title="Two">&#9733;</label>
                <!-- FIRST STAR -->
                <input type="radio" id="star_1" name="rate" value="1" @change="onClickStar($event)"/>
                <label for="star_1" title="One">&#9733;</label>
                </section>
            </div>
        </div>
    </template>
    </b-modal>
    <HowCanApprove :ratedStar="ratedStar" ref="how-can-approve"/>
</div>
</template>

<script>
import HowCanApprove from "./HowCanApprove.vue";
import ApiService from "@/core/services/api.service";
import {  mapActions } from "vuex";
export default {
    data(){
        return {
            ratedStar: 0,
        }
    },
    components:{HowCanApprove},
    methods:{
        ...mapActions([
        "feed_back",
        ]),
      onClickStar(e){
        this.ratedStar = e.target.value;
        if(this.ratedStar == 5){
            this.submitFiveStar();
            this.$refs["rate-star"].hide();
        }
        else{
            this.$refs["rate-star"].hide();
            this.$refs["how-can-approve"].$refs["how-can-approve-modal"].show();
        }

      },
      //submit 5 sao redirect trang review
        submitFiveStar(){
            let  params = {
                rating: this.ratedStar,
                comment: "5 star",
            };
            ApiService.post("/feedback",   params )
            .then(({ data }) => {
                let feed_back = true;
                this.feed_back(feed_back);
            })
            .catch(({ response }) => {
                console.log(response);
            });
            window.open("https://apps.shopify.com/bixgrow-affiliate-marketing?surface_detail=affiliate+marketing&surface_inter_position=1&surface_intra_position=13&surface_type=search#modal-show=ReviewListingModal", "_blank");
        },
    }
}
</script>

<style scoped>
.fire-work-img{
    width: 100px;
    height: auto;
}
.rating {
  font-size: xx-large;
  unicode-bidi: bidi-override;
  direction: rtl;
}
.rating:not(:checked) > input {
  display: none;
}
.caption{
    font-weight: 600;
}
/* - - - - - RATE */
#rate:not(:checked) > label {
  cursor:pointer;
  float: right;
  width: 40px;
  height: 40px;
  display: block;
  user-select: none;
  color: rgba(102, 102, 102, 0.4);
  line-height: 33px;
  text-align: center;
}
#rate:not(:checked) > label:hover,
#rate:not(:checked) > label:hover ~ label {
  color: #ff9900;
}
#rate > input:checked + label:hover,
#rate > input:checked + label:hover ~ label,
#rate > input:checked ~ label:hover,
#rate > input:checked ~ label:hover ~ label,
#rate > label:hover ~ input:checked ~ label {
  color: rgba(235, 172, 57, 0.6);
}
#rate > input:checked ~ label {
  color: #ff9900;
  user-select: none;
}
</style>
