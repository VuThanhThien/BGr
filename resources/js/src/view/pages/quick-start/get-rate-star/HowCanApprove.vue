<template>
    <div class="how-approve">
        <b-modal
        no-close-on-backdrop
        no-close-on-esc
        centered
        size="md"
        ref="how-can-approve-modal"
        body-bg-variant="light"
        footer-bg-variant="light"
        header-bg-variant="light"
        header-class="border-bottom-0"
        footerClass= "border-top-0 pt-0"
        bodyClass="py-0"
        >
        <h1 class="text-center caption mb-5">How can we improve?</h1>
        <b-form-textarea
            id="required"
            v-model="comment"
            placeholder="Your review..."
            rows="4"
        ></b-form-textarea>
        <div class="d-flex justify-content-center mt-5">
            <section id="rate" class="rating">
            <!-- FIFTH STAR -->
            <input type="radio" id="star_5_improve" name="rate" value="5" v-model="changedStar"/>
            <label for="star_5_improve" title="Five">&#9733;</label>
            <!-- FOURTH STAR -->
            <input type="radio" id="star_4_improve" name="rate" value="4" v-model="changedStar"/>
            <label for="star_4_improve" title="Four">&#9733;</label>
            <!-- THIRD STAR -->
            <input type="radio" id="star_3_improve" name="rate" value="3" v-model="changedStar"/>
            <label for="star_3_improve" title="Three">&#9733;</label>
            <!-- SECOND STAR -->
            <input type="radio" id="star_2_improve" name="rate" value="2" v-model="changedStar"/>
            <label for="star_2_improve" title="Two">&#9733;</label>
            <!-- FIRST STAR -->
            <input type="radio" id="star_1_improve" name="rate" value="1" v-model="changedStar"/>
            <label for="star_1_improve" title="One">&#9733;</label>
            </section>
        </div>
        <template #modal-footer>
            <button class="btn btn-success" @click="onSubmit">Submit</button>
        </template>
        </b-modal>
        <b-modal
        no-close-on-backdrop
        no-close-on-esc
        size="md"
        ref="thanks"
        body-bg-variant="light"
        hide-footer
        hide-header
        centered
        >
        <div class="d-flex flex-column align-items-center thanks-feedback justify-content-center thanks-feedback">
            <span
            class="
            svg-icon-success svg-icon-6x
            font-weight-bolder
            svg-icon
            mb-10
            d-flex
            align-items-center">
                <inline-svg src="media/svg/icons/Code/Done-circle.svg"/>
            </span>
            <span class="font-size-h2 text-success ">Thanks for your feedback!</span>
        </div>
        </b-modal>
    </div>
</template>

<script>
import {
  BFormTextarea,
} from "bootstrap-vue";
import {  mapActions } from "vuex";
import ApiService from "@/core/services/api.service";
export default {
    props:["ratedStar"],
    data(){
        return{
            comment: "",
            newStar: null,
        }
    },
    components:{BFormTextarea},
    computed:{
        changedStar: {
            get: function () {
                if(this.newStar == null && this.ratedStar){
                    this.newStar = this.ratedStar;
                }
                return this.newStar
            },
            set: function (newValue) {
                this.newStar = newValue;
            }
        }
    },
    methods:{
        ...mapActions([
        "feed_back",
        ]),
        //Submit 4 sao trở xuống kèm review
        onSubmit(){
            this.$refs["how-can-approve-modal"].hide();
            let  params = {
                rating: this.newStar,
                comment: this.comment,
            };
            ApiService.post("/feedback",   params )
            .then(({ data }) => {
                let feed_back = true;
                this.feed_back(feed_back);
                if(this.newStar == 5){
                    this.submitFiveStar();
                }
                else{
                    this.$refs["thanks"].show();
                    setTimeout(() => {
                        this.$refs["thanks"].hide();
                    }, 1500);
                }
            })
            .catch(({ response }) => {
                console.log(response);
            });

        },
        //submit 5 sao redirect trang review
        submitFiveStar(){
            let  params = {
                rating: this.newStar,
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
            this.$refs["how-can-approve-modal"].hide();
            window.open("https://apps.shopify.com/bixgrow-affiliate-marketing?surface_detail=affiliate+marketing&surface_inter_position=1&surface_intra_position=13&surface_type=search#modal-show=ReviewListingModal", "_blank");
        },
    }
}
</script>

<style scoped>
.thanks-feedback{
    height: 200px;
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
