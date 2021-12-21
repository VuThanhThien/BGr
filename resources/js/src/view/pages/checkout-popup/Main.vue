<template>
  <div>
    <!-- alert -->
    <div
      class="alert alert-custom alert-white alert-shadow fade show gutter-b"
      role="alert"
    >
      <div class="alert-icon">
        <span class="svg-icon svg-icon-primary svg-icon-xl">
          <!--begin::Svg Icon | path:/metronic/theme/html/demo1/dist/assets/media/svg/icons/Tools/Compass.svg-->
          <svg
            xmlns="http://www.w3.org/2000/svg"
            xmlns:xlink="http://www.w3.org/1999/xlink"
            width="24px"
            height="24px"
            viewBox="0 0 24 24"
            version="1.1"
          >
            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
              <rect x="0" y="0" width="24" height="24"></rect>
              <path
                d="M7.07744993,12.3040451 C7.72444571,13.0716094 8.54044565,13.6920474 9.46808594,14.1079953 L5,23 L4.5,18 L7.07744993,12.3040451 Z M14.5865511,14.2597864 C15.5319561,13.9019016 16.375416,13.3366121 17.0614026,12.6194459 L19.5,18 L19,23 L14.5865511,14.2597864 Z M12,3.55271368e-14 C12.8284271,3.53749572e-14 13.5,0.671572875 13.5,1.5 L13.5,4 L10.5,4 L10.5,1.5 C10.5,0.671572875 11.1715729,3.56793164e-14 12,3.55271368e-14 Z"
                fill="#000000"
                opacity="0.3"
              ></path>
              <path
                d="M12,10 C13.1045695,10 14,9.1045695 14,8 C14,6.8954305 13.1045695,6 12,6 C10.8954305,6 10,6.8954305 10,8 C10,9.1045695 10.8954305,10 12,10 Z M12,13 C9.23857625,13 7,10.7614237 7,8 C7,5.23857625 9.23857625,3 12,3 C14.7614237,3 17,5.23857625 17,8 C17,10.7614237 14.7614237,13 12,13 Z"
                fill="#000000"
                fill-rule="nonzero"
              ></path>
            </g>
          </svg>
          <!--end::Svg Icon-->
        </span>
      </div>
      <div class="alert-text">
        With this feature, customers can be converted into affiliates. A pop up
        will appear on the "thank you" page after a consumer successfully
        completes an order on your shop (after check out). This pop-up will show
        them about your affiliate program and ask them to join.
      </div>
    </div>
    <!-- main -->
    <div class="card card-custom gutter-b">
      <div class="d-flex mt-5" style="color: #7e8299; padding-left: 30px">
        <h5 class="mr-10 mt-1 text-dark-75">Active</h5>
        <b-form-checkbox
          id="exclude_taxes"
          switch
          size="lg"
          value="1"
          :disabled="loaded"
          unchecked-value="0"
          @change="activatePopup()"
          v-model="activePopup"
        ></b-form-checkbox>
        <span class="form-text text-muted mt-13 mb-5" style="margin-left: -35px">
          When enabled, customers will see the checkout pop-up on the thank you page after successfully placing an order.
        </span>
      </div>
        <!-- choose popup template -->
      <h5 class="text-dark-75" style="color: #7e8299; padding-left: 30px">Choose Popup Template :</h5>
      <div class="card-body">
        <div class="form-group d-lg-flex">
          <div
            class="carousel-main col-lg-4 col-sm-12 mx-1"
            v-for="item in carouselObj"
            :key="item.id"
          >
            <div
              class="card"
              @click="choosePopup(item.id)"
              @mouseover="whenHover = item.id"
              @mouseleave="whenHover = -1"
              :class="{ active: item.popupType == active_el }"
            >
              <div class="checkmark" v-if="item.popupType == active_el">
                <span class="label label-success label-inline font-weight-bolder mr-2">Selected</span>
              </div>
              <div class="d-flex flex-column align-items-center content">
                <span
                  class="templatePopupPreview"
                  v-html="item.popupPreview"
                ></span>
                <div
                  v-if="whenHover == item.id"
                  class="cardSelectText w-100 pr-3"
                >
                  <p class="cardSelectHeadline font-size-h2 text-center">
                    {{ item.headLine }}
                  </p>
                  <span class="cardSelectCaption" v-html="item.caption"></span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-xl-6">
        <Setting />
      </div>
      <div class="col-xl-6">
        <Preview />
      </div>
    </div>
  </div>
</template>
<script>
import { BFormCheckbox, BSpinner, BOverlay, BFormGroup } from "bootstrap-vue";
import {  mapActions, mapGetters } from "vuex";
import Setting from "./Setting.vue";
import Preview from "./Preview.vue";
import ApiService from "@/core/services/api.service";
export default {
  components: {
    Setting,
    Preview,
    BFormCheckbox,
    BSpinner,
    BOverlay,
    BFormGroup,
  },
  data() {
    return {
      active_el: "",
      loaded: false,
      whenHover: -1,
      carouselObj: [
        {
          id: 0,
          popupType: "basic",
          popupPreview:
            '<img src="media/image-template-form/popup1.jpg" style="height: auto;width: 100%;border-radius: 1rem;" alt=""/>',
          headLine: "Basic",
          caption:
            '<ol>\n<li><span">Just an image that points to your affiliate portal</span></li></ol>',
        },
        {
          id: 1,
          popupType: "simple",
          popupPreview:
            '<div border-radius: 1rem;">' +
            '<img style="border-top-left-radius: 10px;border-top-right-radius: 10px;height: 30%; width: 100%; object-fit: cover;" src="media/image-template-form/popup1.png" alt="" />' +
            '<h3 style="text-align: center; color: #ff9900; font-size:15px;font-weight: 700;margin-top: 10px;width: 80%;margin-left: auto;margin-right: auto;">Invite your friends and get a reward</h3>' +
            '<h6 style="text-align: center; color: #4f4f4f;width: 80%;font-size: 10px;margin-left: auto;margin-right: auto;margin-bottom: 8px;">Share your link with your friends and earn 5% commission whenever they make a purchase !</h6>' +
            '<div style="display: flex; justify-content: center; padding-bottom: 15px; margin-top: 13px;">' +
            '<button style="font-size: 10px; margin-left: auto; margin-right: auto; border-radius: 4px; padding: 5px 10px 5px 10px; height: auto; color: #fff; border: none; background-color: #ff9900; width: 40%; font-weight: bold; transition: .3s;">Get Started</button></div></div>',
          headLine: "Simple",
          caption:
            "<ol><li>Easily customizable</li><li>Elements Include<ul><li>Header Image</li><li>Content (title, subtitle)</li><li>Button</li><li>Other configuration</li></ul></li></ol>",
        },
        {
          id: 2,
          popupType: "advanced",
          popupPreview:
            '<div border-radius: 1rem;">' +
            '<img style="border-top-left-radius: 10px;border-top-right-radius: 10px;height: 30%; width: 100%; object-fit: cover;" src="media/image-template-form/popup3.png" alt="" />' +
            '<h3 style="text-align: center; color: #ff9900; font-size:15px;font-weight: 700;margin-top: 10px;width: 80%;margin-left: auto;margin-right: auto;">Invite your friends and get a reward</h3>' +
            '<h6 style="text-align: center;color: #4f4f4f;width: 80%;font-size: 10px;margin-left: auto;margin-right: auto;margin-bottom: 8px;">Share your link with your friends and earn 5% commission whenever they make a purchase !</h6>' +
            '<h6  style="font-weight: 700; color: #4f4f4f; margin-left: 30px; margin-bottom: 5px;font-size: 10px;">Referral Link</h6>' +
            '<div style="margin-left:20px; display: flex; justify-content: center;">' +
            '<input style="width: 80%;border: solid 0.3px #b3b3b3;outline: none;border-top-left-radius: 5px;border-bottom-left-radius: 5px;font-size: 10px;" type="text" value="https://howardleo.myshopify.com/?bg_ref=1:0SXYk31dGd">' +
            '<div style="font-size: 8px;padding: 8px ;height: 26px; width: 40px; background-color: #d9d9d9;border-top-right-radius: 4px;border-bottom-right-radius: 4px;">Copy</div>' +
            "</div>" +
            '<h6  style="font-weight: 700; color: #4f4f4f; margin-left: 30px; margin-bottom: 5px;font-size: 10px;margin-top: 10px;">Coupon Code</h6>' +
            '<div style="margin-left:20px; display: flex; justify-content: center;">' +
            '<input style="width: 80%;border: solid 0.3px #b3b3b3;outline: none;border-top-left-radius: 5px;border-bottom-left-radius: 5px;font-size: 10px;" type="text" value="DISCOUNT">' +
            '<div style="font-size: 8px;padding: 8px ;height: 26px; width: 40px; background-color: #d9d9d9;border-top-right-radius: 4px;border-bottom-right-radius: 4px;">Copy</div>' +
            "</div>" +
            '<div style="display: flex; justify-content: center; margin-top: 5px;">' +
            '<button style="margin-top: 15px;font-size: 10px; margin-left: auto; margin-right: auto; border-radius: 4px; padding: 5px 10px 5px 10px; height: auto; color: #fff; border: none; background-color: #ff9900; width: 40%; font-weight: bold; transition: .3s;">Get Started</button>' +
            "</div>" +
            '<div style="text-align: center;height: 30px;display: flex !important;justify-content: center;margin-top: 5px;">' +
            '<a style="margin: 4px; height: 15px; width:15px"><img style="margin: 2px; height: 15px; width:15px" src="media/svg/social-icons/facebook.svg"></a>' +
            '<a style="margin: 4px; height: 15px; width:15px"><img style="margin: 2px; height: 15px; width:15px" src="media/svg/social-icons/twitter.svg"></a>' +
            '<a style="margin: 4px; height: 15px; width:15px"><img style="margin: 2px; height: 15px; width:15px" src="media/svg/social-icons/pinterest.svg"></a>' +
            '<a style="margin: 4px; height: 15px; width:15px"><img style="margin: 2px; height: 15px; width:15px" src="media/svg/social-icons/linkedin.svg"></a>' +
            "</div>" +
            "</div>",
          headLine: "Advanced",
          caption:
            "<ol><li>Customer is automatically registered as an affiliate</li><li>Sends a welcome and invitation email with a referral link to customers.</li><li>Their referral link and coupon code is shown</li><li>Popup can be shared to social media (Facebook, Instagram, etc)</li><li>Custom CSS can be added</li></ol>",
        },
      ],
    };
  },
  computed: {
    ...mapGetters(["checkout_popup_data", "activate_popup_checkout"]),
    activePopup: {
      get: function () {
        return this.activate_popup_checkout;
      },
      set: function (obj) {
        this.updateActivePopupSetting(obj);
      },
    },
  },

  methods: {
    ...mapActions([
      "updatePopupSetting",
      "updateActivePopupSetting",
      "chooseTemplatePopup",
    ]),
    activatePopup() {
      this.loaded = true;
      var payload = { activate_popup_checkout: this.activate_popup_checkout };
      ApiService.put("/settings/active-popup-checkout", payload).then(() => {
        this.loaded = false;
        this.onSwitch(this.activate_popup_checkout);
      });
    },
    choosePopup(carouselid) {
      this.active_el = this.carouselObj[carouselid].popupType;
      this.checkout_popup_data.popupType =
        this.carouselObj[carouselid].popupType;
      var obj = this.checkout_popup_data;
      this.chooseTemplatePopup(obj);
    },
    onSwitch(obj) {
      if (obj == 1) {
        this.$toast.success("Activated", {
          position: "bottom",
        });
      }
      if (obj == 0) {
        this.$toast.success("Deactivated", {
          position: "bottom",
        });
      }
    },
  },
  created() {
    this.active_el = this.checkout_popup_data.popupType;
  },
  watch: {
    checkout_popup_data() {
      this.active_el = this.checkout_popup_data.popupType;
    },
  },
};
</script>

<style scoped>
.card {
  box-shadow: 0 0 20px 0 rgb(0 0 0 / 35%);
}
.active {
  border: 1px solid #1BC5BD !important;
}
.carousel-main {
  min-height: 200px;
}
.img-template {
  height: auto;
  width: 100%;
  border: 1px solid #d9d9d9;
  border-radius: 1rem;
}
.content {
  border-radius: inherit;
  position: relative;
  padding: 0;
}
.content:hover {
  background-color: #e2e2e2;
  transition: 0 0.3s ease;
}
.card {
  cursor: pointer;
  position: relative;
}
.checkmark {
  position: absolute;
  right: 5px;
  top: 5px;
  z-index: 1;
}
.cardSelectText {
  top: 0;
  background-color: #000000b3;
  transition: 0.5s;
  left: 0;
  position: absolute;
  height: 100%;
  overflow: hidden;
  border-radius: 10px;
}
.cardSelectHeadline {
  font-size: 1.2rem;
  color: #fff;
  margin: 6px;
  margin-left: 0;
}
.cardSelectCaption {
  color: #fff;
}
@media screen and (max-width: 600px) {
  .img-template {
    display: none !important;
  }
}
@media screen and (max-width: 992px){
    .card{
        margin-bottom: 5px;
    }
}
</style>
