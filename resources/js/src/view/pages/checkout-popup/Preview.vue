<template>
  <div>
    <component is="style">
      .BIXGROW_image
      {margin-left: auto;margin-right: auto;background-repeat:no-repeat;background-size: cover;z-index: 5;border-top-left-radius:
      12px;background-position: 50% 50%; border-top-right-radius:
      12px;height:{{ this.checkout_popup_data.imgHeight }}px;width:
      {{ this.checkout_popup_data.imgWidth }}px; background-image: url({{
        this.checkout_popup_data.pathCDN}})}
      .BIXGROW_submit_btn {font-size: 15px;margin-left: auto;margin-right:
      auto;border-radius: {{ this.checkout_popup_data.btnConer }}px;padding:
      10px 15px 10px 15px; height: auto; color:{{this.checkout_popup_data.btnColor}}; border: none;
      background-color:{{this.checkout_popup_data.btnBg}};width: 58%;font-weight: 700; transition: .3s;}
      #bg_referral_link{display: {{ showReferral }}}
      #bg_coupon_code{display:{{ showCoupon }}}
      #BIXGROW_share_wrapper{display:{{ showShare }}}
      #bg_fb_icon{display: {{ showFb }}}
      #bg_tw_icon{display: {{ showTw }}}
      #bg_ptr_icon{display: {{ showPtr }}}
      #bg_ln_icon{display: {{ showLn }}}
      .BIXGROW_card{margin-left: auto;margin-right: auto;position: relative;top:
      5%; border-radius: 10px; height: max-content; width:
      {{ this.checkout_popup_data.imgWidth }}px;
      background-color:#fff;box-shadow: 0 0 20px 0 rgb(0 0 0 / 35%);}
      .BIXGROW_title {text-align: center;margin-top: 20px;margin-bottom:
      10px;font-size: 18px; font-weight: 900;max-width: 80%;color:
      {{ this.checkout_popup_data.titleColor }};margin-left: auto;margin-right:
      auto;font-size: {{this.checkout_popup_data.titleFontSize}}px;}
      .BIXGROW_content { width: 60%; text-align:
      center; font-weight: 400; margin-left: auto; margin-right: auto;
      font-size: {{this.checkout_popup_data.contentFontSize}}px; color: {{ this.checkout_popup_data.contentColor }}
      }
      {{ this.checkout_popup_data.ExtraCss }}
      @media screen and (max-width: 600px){
          .BIXGROW_card{
              transform: scale(0.7);
              margin-left: -45px;
              margin-top: -80px;
          }
      }
    </component>
    <div class="col-xl-10 mx-auto mb-10">
      <h3>Popup Preview</h3>
      <span class="form-text text-dark-75 mb-10">
        The actual rendering may vary since the popup follows your shop's theme.
      </span>
    </div>
    <span
      class="previewPopup mx-auto"
      v-html="previewPopup(this.checkout_popup_data.popupType)"
    ></span>
  </div>
</template>

<script>
import { mapGetters } from "vuex";
export default {
  data() {
    return {
      isBlank: ["", 'target="_blank"'],
    };
  },
  methods: {
    previewPopup(popupType) {
      let previewHtml = "";
      if (popupType == "basic") {
        previewHtml +=
          '<div id="BGCard" class="BIXGROW_card" >' +
          '<a href="' +
          this.checkout_popup_data.onClickLink +
          '" ' +
          this.isBlank[this.checkout_popup_data.newtab1] +
          ' style="text-decoration: none">' +
          '<div class="BIXGROW_image"></div>' +
          "</a>" +
          "</div>";
      }
      if (popupType == "simple") {
        previewHtml +=
          '<div id="BGCard" class="BIXGROW_card" >' +
          '<div class="BIXGROW_image" ></div>' +
          '<h2 class="BIXGROW_title">' +
          this.checkout_popup_data.titleText +
          "</h2>" +
          '<h4 class="BIXGROW_content">' +
          this.checkout_popup_data.textContent2 +
          "</h4>" +
          '<a class="BIXGROW_btn_container" href="' +
          this.checkout_popup_data.onClickLink +
          '" ' +
          this.isBlank[this.checkout_popup_data.newtab2] +
          ' style="text-decoration: none">' +
          '<button id="BIXGROW_submit_btn" class="BIXGROW_submit_btn" >' +
          this.checkout_popup_data.btnText12 +
          "</button>" +
          "</a>" +
          "</div>";
      }
      if (popupType == "advanced") {
        previewHtml +=
          '<div id="BGCard" class="BIXGROW_card" ">' +
          '<div class="BIXGROW_image"></div>' +
          '<h2 class="BIXGROW_title">' +
          this.checkout_popup_data.titleText +
          "</h2>" +
          '<h4 class="BIXGROW_content">' +
          this.replaceprogramDetail(this.checkout_popup_data.textContent3) +
          "</h4>" +
          '<div class="BIXGROW_input_block" id="bg_referral_link" >' +
          '<label class="BIXGROW_label">' +
          this.checkout_popup_data.referralLinkLabel +
          "</label>" +
          '<div class="BIXGROW_input_wrapper">' +
          '<input id="BIXGROW_input_reflink" class="BIXGROW_input" readonly="" value="' +
          this.checkout_popup_data.referralLink +
          '"></input>' +
          '<button class="BIXGROW_copy_btn">' +
          this.checkout_popup_data.CopyText +
          "</button>" +
          "</div>" +
          "</div>" +
          '<div class="BIXGROW_input_block" id="bg_coupon_code">' +
          '<label class="BIXGROW_label">' +
          this.checkout_popup_data.couponCodeLabel +
          "</label>" +
          '<div class="BIXGROW_input_wrapper">' +
          '<input id="BIXGROW_input_coupon" class="BIXGROW_input" readonly="" value="' +
          this.checkout_popup_data.couponCode +
          '"></input>' +
          '<button class="BIXGROW_copy_btn">' +
          this.checkout_popup_data.CopyText +
          "</button>" +
          "</div>" +
          "</div>" +
          '<a class="BIXGROW_btn_container" href="' +
          this.checkout_popup_data.onClickLink +
          '" ' +
          this.isBlank[this.checkout_popup_data.newtab3] +
          ' style="text-decoration: none">' +
          '<button id="BIXGROW_submit_btn" class="BIXGROW_submit_btn" >' +
          this.checkout_popup_data.btnText3 +
          "</button>" +
          "</a>" +
          '<div class="BIXGROW_share_wrapper" id="BIXGROW_share_wrapper">' +
          '<a id="bg_fb_icon"><img src="media/svg/social-icons/facebook.svg"></a>' +
          '<a id="bg_tw_icon"><img src="media/svg/social-icons/twitter.svg"></a>' +
          '<a id="bg_ptr_icon"><img src="media/svg/social-icons/pinterest.svg"></a>' +
          '<a id="bg_ln_icon"><img src="media/svg/social-icons/linkedin.svg"></a>' +
          "</div>" +
          "</div>";
      }
      return previewHtml;
    },
    replaceprogramDetail(textContent) {
      if (textContent) {
        var c = this.genTextCommissionType(this.group.commission_type);
        if (this.group.commission_type == 1) {
          var d = this.group.commission_amount + "%";
        } else {
          var d = this.formatCommissionAmount(
            this.group,
            this.group.commission_amount,
            this.format
          );
        }
        textContent = textContent.replaceAll(/{commission_type}/g, c);
        textContent = textContent.replaceAll(/{commission_amount}/g, d);
      } else {
        textContent = "<p></p>";
      }
      return textContent;
    },
  },
  computed: {
    ...mapGetters(["checkout_popup_data", "groups", "defaultGroup"]),
    group() {
      let groups = this.groups;

      groups = groups.filter(
        (groups) => groups.id == this.checkout_popup_data.program
      );
      if (groups.length > 0) {
        return groups[0];
      } else {
        return this.defaultGroup;
      }
    },
    showReferral() {
      if (
        this.checkout_popup_data.showArray &&
        this.checkout_popup_data.showArray.includes("Referral Link")
      ) {
        return "block";
      } else {
        return "none";
      }
    },
    showCoupon() {
      if (
        this.checkout_popup_data.showArray &&
        this.checkout_popup_data.showArray.includes("Coupon Code")
      ) {
        return "block";
      } else {
        return "none";
      }
    },
    showShare() {
      if (
        this.checkout_popup_data.shareArray &&
        this.checkout_popup_data.shareArray.length > 0
      ) {
        return "block";
      } else {
        return "none";
      }
    },
    showFb() {
      if (
        this.checkout_popup_data.shareArray &&
        this.checkout_popup_data.shareArray.includes("Facebook")
      ) {
        return "inline-block";
      } else {
        return "none";
      }
    },
    showTw() {
      if (
        this.checkout_popup_data.shareArray &&
        this.checkout_popup_data.shareArray.includes("Twitter")
      ) {
        return "inline-block";
      } else {
        return "none";
      }
    },
    showPtr() {
      if (
        this.checkout_popup_data.shareArray &&
        this.checkout_popup_data.shareArray.includes("Pinterest")
      ) {
        return "inline-block";
      } else {
        return "none";
      }
    },
    showLn() {
      if (
        this.checkout_popup_data.shareArray &&
        this.checkout_popup_data.shareArray.includes("Linkedln")
      ) {
        return "inline-block";
      } else {
        return "none";
      }
    },
  },
};
</script>

<style>
.BIXGROW_input_block {
  margin: 16px 4px;
  max-width: 80%;
  margin-left: auto;
  margin-right: auto;
}
.BIXGROW_label {
  font-weight: 700;
  font-size: 1rem;
  display: inline-block;
  width: 100%;
  margin: 0 0 10px 0;
  padding: 0;
  text-align: left;
}
.BIXGROW_input {
  border: transparent;
  background: transparent;
  outline: none;
  flex: 1 1;
  padding: 0 10px;
  opacity: 0.75;
}
.BIXGROW_copy_btn {
  background-color: #efefef;
  border-color: transparent;
  padding: 8px;
  height: 100%;
  border-top-right-radius: inherit;
  border-bottom-right-radius: inherit;
}
.BIXGROW_copy_btn:hover {
  background-color: #b5b4b4;
  transition: 0.2s ease;
}
button:not(:disabled) {
  cursor: pointer;
}
.BIXGROW_input_wrapper {
  display: flex;
  border: 1px solid #ced4da;
  height: 40px;
  border-radius: 5px;
}
.BIXGROW_btn_container {
  display: flex;
  justify-content: center;
  padding-bottom: 20px;
  margin-top: 20px;
}
.BIXGROW_share_wrapper {
  padding: 10px 0 12px;
  text-align: center;
  height: 60px;
  display: flex !important;
  justify-content: center;
  width: auto;
  border-bottom-left-radius: 10px;
  border-bottom-right-radius: 10px;
}
.BIXGROW_share_wrapper a {
  text-decoration: none;
  margin: 4px;
  width: 32px;
  height: 32px;
}
.BIXGROW_share_wrapper img {
  width: 28px;
  height: 28px;
  margin: 2px;
  transition: 0.1s;
}
.BIXGROW_share_wrapper img:hover {
  width: 32px;
  height: 32px;
}
.BIXGROW_submit_btn:hover {
  opacity: 0.6;
}
</style>
