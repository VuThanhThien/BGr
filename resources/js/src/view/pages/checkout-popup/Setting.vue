<template>
  <div class="card card-custom gutter-b">
    <div class="card-custom card-body">
      <div class="col-xl-8 mx-auto mb-10">
        <h3 class="text-center">
          Customize {{ checkout_popup_data.popupType }} popup
        </h3>
      </div>
      <div class="togle-card">
        <div class="togle-label d-flex justify-content-between">
          <span class="font-size-h6">Header Image</span>
          <button
            class="btn btn-edit-gray"
            @click="isCloseImgBlock = !isCloseImgBlock"
          >
            {{ isCloseImgBlock == true ? "Edit" : "Close" }}
          </button>
        </div>
        <div class="p-3 pt-5" v-if="!isCloseImgBlock">
          <!-- upload image -->
          <b-form-group
            id="fieldset-horizontal"
            label-cols-sm="4"
            label-cols-lg="3"
            content-cols-sm="8"
            content-cols-lg="9"
            label="Image:"
            label-for="logo"
            class="d-flex align-items-center"
          >
            <div
              class="image-input image-input-empty image-input-outline"
              id="kt_user_edit_avatar"
            >
              <img
                v-if="checkout_popup_data.pathCDN"
                class="image-input-wrapper"
                :src="checkout_popup_data.pathCDN"
              />
              <img
                v-else
                class="image-input-wrapper"
                src="media/svg/icons/Home/Picture.svg"
              />
              <label
                class="
                  btn
                  btn-xs
                  btn-icon
                  btn-circle
                  btn-white
                  btn-hover-text-primary
                  btn-shadow
                "
                data-action="change"
                data-toggle="tooltip"
                title=""
                data-original-title="Change avatar"
              >
                <i class="fa fa-pen icon-sm text-muted">
                  <b-form-file
                    ref="logoInputFile"
                    accept="image/*"
                    v-model="image"
                    style="display: none"
                    @change="previewImage"
                  ></b-form-file>
                </i>
              </label>
              <span
                v-if="checkout_popup_data.pathCDN"
                class="
                  btn
                  btn-xs
                  btn-icon
                  btn-circle
                  btn-white
                  btn-hover-text-primary
                  btn-shadow
                  del-img
                "
                @click="changeLogoDefault"
              >
                <i class="ki ki-close icon-sm text-muted"></i>
              </span>
            </div>
          </b-form-group>
          <!-- img width -->
          <b-form-group
            id="fieldset-horizontal"
            label-cols-sm="4"
            label-cols-lg="3"
            content-cols-sm="8"
            content-cols-lg="9"
            label="Image Width:"
            label-for="width"
          >
            <b-form-input
              id="width"
              type="number"
              v-model="checkout_popup_data.imgWidth"
            ></b-form-input>
          </b-form-group>
          <!-- img height -->
          <b-form-group
            id="fieldset-horizontal"
            label-cols-sm="4"
            label-cols-lg="3"
            content-cols-sm="8"
            content-cols-lg="9"
            label="Image Height:"
            label-for="height"
          >
            <b-form-input
              id="height"
              type="number"
              v-model="checkout_popup_data.imgHeight"
            ></b-form-input>
          </b-form-group>
        </div>
      </div>
      <div v-if="checkout_popup_data.popupType == 'basic'">
        <div class="togle-card">
          <div class="d-flex justify-content-between togle-label">
            <span class="font-size-h6">Image Click</span>
            <button
              class="btn btn-edit-gray"
              @click="isCloseImgClickBlock = !isCloseImgClickBlock"
            >
              {{ isCloseImgClickBlock == true ? "Edit" : "Close" }}
            </button>
          </div>
          <div class="p-3" v-if="!isCloseImgClickBlock">
            <!-- onclick link  -->
            <b-form-group
              id="fieldset-horizontal"
              label-cols-sm="4"
              label-cols-lg="3"
              content-cols-sm="8"
              content-cols-lg="9"
              label="When click open:"
              label-for="onclick"
            >
              <b-form-input id="onclick" v-model="onClickLink"></b-form-input>
              <span class="form-text text-muted">
                Default link will be the registration page of your default
                program. You can change to the registration page of other
                programs if you want.
              </span>
            </b-form-group>
            <!-- open in newtab basic -->
            <b-form-group
              id="fieldset-horizontal"
              label-cols-sm="4"
              label-cols-lg="3"
              content-cols-sm="8"
              content-cols-lg="9"
              label="When click open:"
              label-for="openin"
            >
              <b-form-select
                :options="openin"
                id="openin"
                v-model="checkout_popup_data.newtab1"
              ></b-form-select>
            </b-form-group>
          </div>
        </div>
      </div>

      <div v-if="checkout_popup_data.popupType == 'simple'">
        <div class="togle-card">
          <div class="d-flex justify-content-between togle-label">
            <span class="font-size-h6">Content</span>
            <button
              class="btn btn-edit-gray"
              @click="isCloseContentBlock = !isCloseContentBlock"
            >
              {{ isCloseContentBlock == true ? "Edit" : "Close" }}
            </button>
          </div>
          <div class="p-3" v-if="!isCloseContentBlock">
            <!-- title popup2 -->
            <b-form-group
              id="fieldset-horizontal"
              label-cols-sm="4"
              label-cols-lg="3"
              content-cols-sm="8"
              content-cols-lg="9"
              label="Title:"
              label-for="onclick"
            >
              <b-form-input
                id="onclick"
                v-model="checkout_popup_data.titleText"
              ></b-form-input>
            </b-form-group>
            <b-form-group
              id="fieldset-horizontal"
              label-cols-sm="4"
              label-cols-lg="3"
              content-cols-sm="8"
              content-cols-lg="9"
              label="Title Font-size:"
              label-for="size"
            >
              <b-form-input
                id="size"
                type="range"
                class="mb-3"
                v-model="checkout_popup_data.titleFontSize"
              ></b-form-input>
              <b-input-group append="px">
                <b-form-input
                  id="size"
                  type="number"
                  min="0"
                  max="50"
                  step="1"
                  v-model="checkout_popup_data.titleFontSize"
                ></b-form-input>
              </b-input-group>
            </b-form-group>

            <!-- text content popup 2 -->
            <b-form-group
              id="fieldset-horizontal"
              label-cols-sm="4"
              label-cols-lg="3"
              content-cols-sm="8"
              content-cols-lg="9"
              label="Content:"
              label-for="onclick"
            >
              <b-form-textarea
                id="onclick"
                v-model="checkout_popup_data.textContent2"
                placeholder="Your content..."
                rows="4"
              ></b-form-textarea>
              <span class="form-text text-muted">
                You can edit the content based on your program offer.
              </span>
            </b-form-group>
            <!-- content fontsize popup2 -->
            <b-form-group
              id="fieldset-horizontal"
              label-cols-sm="4"
              label-cols-lg="3"
              content-cols-sm="8"
              content-cols-lg="9"
              label="Content Font-size:"
              label-for="size"
            >
              <b-form-input
                id="size"
                type="range"
                class="mb-3"
                v-model="checkout_popup_data.contentFontSize"
              ></b-form-input>
              <b-input-group append="px">
                <b-form-input
                  id="size"
                  type="number"
                  min="0"
                  max="50"
                  step="1"
                  v-model="checkout_popup_data.contentFontSize"
                ></b-form-input>
              </b-input-group>
            </b-form-group>
            <div class="d-flex">
              <div class="col-6" style="margin-left: -10px">
                <!-- title color popup 2 -->
                <b-form-group
                  id="fieldset-horizontal"
                  label-cols-lg="8"
                  content-cols-lg="4"
                  label="Title Color:"
                  label-for="bg"
                >
                  <color-picker
                    id="bg"
                    v-model="checkout_popup_data.titleColor"
                  ></color-picker>
                </b-form-group>
              </div>
              <div class="col-6">
                <!-- color content popup2 -->
                <b-form-group
                  id="fieldset-horizontal"
                  label-cols-lg="8"
                  content-cols-lg="4"
                  label="Content Color:"
                  label-for="bg"
                >
                  <color-picker
                    id="bg"
                    v-model="checkout_popup_data.contentColor"
                    :position="{ left: '-256px', top: 0 }"
                  ></color-picker>
                </b-form-group>
              </div>
            </div>
          </div>
        </div>
        <div class="togle-card">
          <div class="d-flex justify-content-between togle-label">
            <span class="font-size-h6">Button</span>
            <button
              class="btn btn-edit-gray"
              @click="isCloseBtnBlock = !isCloseBtnBlock"
            >
              {{ isCloseBtnBlock == true ? "Edit" : "Close" }}
            </button>
          </div>
          <div class="p-3" v-show="!isCloseBtnBlock">
            <!-- title btn popup 2 -->
            <b-form-group
              id="fieldset-horizontal"
              label-cols-sm="4"
              label-cols-lg="3"
              content-cols-sm="8"
              content-cols-lg="9"
              label="Button Title:"
              label-for="onclick"
            >
              <b-form-input
                id="onclick"
                v-model="checkout_popup_data.btnText12"
              ></b-form-input>
            </b-form-group>
            <!-- onclick link popup 2 -->
            <b-form-group
              id="fieldset-horizontal"
              label-cols-sm="4"
              label-cols-lg="3"
              content-cols-sm="8"
              content-cols-lg="9"
              label="When Click Open:"
              label-for="onclick"
            >
              <b-form-input id="onclick" v-model="onClickLink"></b-form-input>
              <span class="form-text text-muted">
                Default link will be the registration page of your default
                program. You can change to the registration page of other
                programs if you want.
              </span>
            </b-form-group>
            <!-- background-color popup 2 -->
            <div class="d-flex">
              <div class="col-6" style="margin-left: -10px">
                <b-form-group
                  id="fieldset-horizontal"
                  label-cols-lg="8"
                  content-cols-lg="4"
                  label="Background Color:"
                  label-for="bg"
                >
                  <color-picker
                    id="bg"
                    v-model="checkout_popup_data.btnBg"
                  ></color-picker>
                </b-form-group>
              </div>
              <div class="col-6">
                <b-form-group
                  id="fieldset-horizontal"
                  label-cols-lg="8"
                  content-cols-lg="4"
                  label="Text Color:"
                  label-for="bg"
                >
                  <color-picker
                    id="bg"
                    v-model="checkout_popup_data.btnColor"
                    :position="{ left: '-256px', top: 0 }"
                  ></color-picker>
                </b-form-group>
              </div>
            </div>
            <!-- border radius 2 -->
            <b-form-group
              id="fieldset-horizontal"
              label-cols-sm="4"
              label-cols-lg="3"
              content-cols-sm="8"
              content-cols-lg="9"
              label="Border Radius:"
              label-for="size"
            >
              <b-form-input
                id="size"
                type="range"
                class="mb-3"
                v-model="checkout_popup_data.btnConer"
              ></b-form-input>
              <b-input-group append="px">
                <b-form-input
                  id="size"
                  type="number"
                  min="0"
                  max="50"
                  step="1"
                  v-model="checkout_popup_data.btnConer"
                ></b-form-input>
              </b-input-group>
            </b-form-group>
            <!-- newtab2 -->
            <b-form-group
              id="fieldset-horizontal"
              label-cols-sm="4"
              label-cols-lg="3"
              content-cols-sm="8"
              content-cols-lg="9"
              label="When click open:"
              label-for="openin"
            >
              <b-form-select
                :options="openin"
                id="openin"
                v-model="checkout_popup_data.newtab2"
              ></b-form-select>
            </b-form-group>
          </div>
        </div>
      </div>
      <!-- Khối Advanced -->
      <div v-if="checkout_popup_data.popupType == 'advanced'">
        <div class="togle-card">
          <div class="d-flex justify-content-between togle-label">
            <span class="font-size-h6">Content</span>
            <button
              class="btn btn-edit-gray"
              @click="isCloseContentBlock = !isCloseContentBlock"
            >
              {{ isCloseContentBlock == true ? "Edit" : "Close" }}
            </button>
          </div>
          <div class="p-3" v-if="!isCloseContentBlock">
            <!-- title popup 3 -->
            <b-form-group
              id="fieldset-horizontal"
              label-cols-sm="4"
              label-cols-lg="3"
              content-cols-sm="8"
              content-cols-lg="9"
              label="Title:"
              label-for="onclick"
            >
              <b-form-input
                id="onclick"
                v-model="checkout_popup_data.titleText"
              ></b-form-input>
            </b-form-group>
            <!-- title fontsize popup3 -->
            <b-form-group
              id="fieldset-horizontal"
              label-cols-sm="4"
              label-cols-lg="3"
              content-cols-sm="8"
              content-cols-lg="9"
              label="Title Font-size:"
              label-for="size"
            >
              <b-form-input
                id="size"
                type="range"
                class="mb-3"
                v-model="checkout_popup_data.titleFontSize"
              ></b-form-input>
              <b-input-group append="px">
                <b-form-input
                  id="size"
                  type="number"
                  min="0"
                  max="50"
                  step="1"
                  v-model="checkout_popup_data.titleFontSize"
                ></b-form-input>
              </b-input-group>
            </b-form-group>
            <!-- Content popup 3 -->
            <b-form-group
              id="fieldset-horizontal"
              label-cols-sm="4"
              label-cols-lg="3"
              content-cols-sm="8"
              content-cols-lg="9"
              label="Content:"
              label-for="onclick"
            >
              <b-form-textarea
                id="onclick"
                v-model="checkout_popup_data.textContent3"
                placeholder="Your content..."
                rows="4"
              ></b-form-textarea>
              <span class="form-text text-muted">
                You can use the tag above to represent for your program details.
              </span>
            </b-form-group>
            <!-- content fontsize popup3 -->
            <b-form-group
              id="fieldset-horizontal"
              label-cols-sm="4"
              label-cols-lg="3"
              content-cols-sm="8"
              content-cols-lg="9"
              label="Content Font-size:"
              label-for="size"
            >
              <b-form-input
                id="size"
                type="range"
                class="mb-3"
                v-model="checkout_popup_data.contentFontSize"
              ></b-form-input>
              <b-input-group append="px">
                <b-form-input
                  id="size"
                  type="number"
                  min="0"
                  max="50"
                  step="1"
                  v-model="checkout_popup_data.contentFontSize"
                ></b-form-input>
              </b-input-group>
            </b-form-group>
            <!-- content color popup 3 -->
            <div class="d-flex">
              <div class="col-6" style="margin-left: -10px">
                <!-- title color popup 3 -->
                <b-form-group
                  id="fieldset-horizontal"
                  label-cols-lg="8"
                  content-cols-lg="4"
                  label="Title Color:"
                  label-for="bg"
                >
                  <color-picker
                    id="bg"
                    v-model="checkout_popup_data.titleColor"
                  ></color-picker>
                </b-form-group>
              </div>
              <div class="col-6">
                <!-- color content popup3 -->
                <b-form-group
                  id="fieldset-horizontal"
                  label-cols-lg="8"
                  content-cols-lg="4"
                  label="Content Color:"
                  label-for="bg"
                >
                  <color-picker
                    id="bg"
                    v-model="checkout_popup_data.contentColor"
                    :position="{ left: '-256px', top: 0 }"
                  ></color-picker>
                </b-form-group>
              </div>
            </div>
          </div>
        </div>
        <div class="togle-card">
          <div class="d-flex justify-content-between togle-label">
            <span class="font-size-h6">Button</span>
            <button
              class="btn btn-edit-gray"
              @click="isCloseBtnBlock = !isCloseBtnBlock"
            >
              {{ isCloseBtnBlock == true ? "Edit" : "Close" }}
            </button>
          </div>
          <div class="p-3" v-show="!isCloseBtnBlock">
            <b-form-group
              id="fieldset-horizontal"
              label-cols-sm="4"
              label-cols-lg="3"
              content-cols-sm="8"
              content-cols-lg="9"
              label="Button Title:"
              label-for="onclick"
            >
              <b-form-input
                id="onclick"
                v-model="checkout_popup_data.btnText3"
              ></b-form-input>
            </b-form-group>

            <div class="d-flex">
              <div class="col-6" style="margin-left: -10px">
                <b-form-group
                  id="fieldset-horizontal"
                  label-cols-lg="8"
                  content-cols-lg="4"
                  label="Background Color:"
                  label-for="bg"
                >
                  <color-picker
                    id="bg"
                    v-model="checkout_popup_data.btnBg"
                  ></color-picker>
                </b-form-group>
              </div>
              <div class="col-6">
                <b-form-group
                  id="fieldset-horizontal"
                  label-cols-lg="8"
                  content-cols-lg="4"
                  label="Text Color:"
                  label-for="bg"
                >
                  <color-picker
                    id="bg"
                    v-model="checkout_popup_data.btnColor"
                    :position="{ left: '-256px', top: 0 }"
                  ></color-picker>
                </b-form-group>
              </div>
            </div>
            <!-- Border Radius: -->
            <b-form-group
              id="fieldset-horizontal"
              label-cols-sm="4"
              label-cols-lg="3"
              content-cols-sm="8"
              content-cols-lg="9"
              label="Border Radius:"
              label-for="size"
            >
              <b-form-input
                id="size"
                type="range"
                class="mb-3"
                v-model="checkout_popup_data.btnConer"
              ></b-form-input>
              <b-input-group append="px">
                <b-form-input
                  id="size"
                  type="number"
                  min="0"
                  max="50"
                  step="1"
                  v-model="checkout_popup_data.btnConer"
                ></b-form-input>
              </b-input-group>
            </b-form-group>
          </div>
        </div>
        <div class="togle-card">
          <div class="d-flex justify-content-between togle-label">
            <span class="font-size-h6">Referral Link and Coupon</span>
            <button
              class="btn btn-edit-gray"
              @click="isCloseReferralBlock = !isCloseReferralBlock"
            >
              {{ isCloseReferralBlock == true ? "Edit" : "Close" }}
            </button>
          </div>
          <div class="p-3" v-if="!isCloseReferralBlock">
            <!-- showArray -->
            <b-form-group
              label-cols-sm="4"
              label-cols-lg="3"
              content-cols-sm="8"
              content-cols-lg="9"
              label="Show"
              label-for="tags-component-select"
            >
              <b-form-tags
                id="tags-component-select"
                v-model="checkout_popup_data.showArray"
                size="lg"
                class="mb-2"
                add-on-change
                no-outer-focus
              >
                <template
                  v-slot="{
                    tags,
                    inputAttrs,
                    inputHandlers,
                    disabled,
                    removeTag,
                  }"
                >
                  <ul
                    v-if="tags.length > 0"
                    class="list-inline d-inline-block mb-2"
                  >
                    <li v-for="tag in tags" :key="tag" class="list-inline-item">
                      <b-form-tag
                        @remove="removeTag(tag)"
                        :title="tag"
                        :disabled="disabled"
                        variant="primary"
                        >{{ tag }}</b-form-tag
                      >
                    </li>
                  </ul>
                  <b-form-select
                    v-bind="inputAttrs"
                    v-on="inputHandlers"
                    :disabled="disabled || availableOptionsShow.length === 0"
                    :options="availableOptionsShow"
                  >
                    <template #first>
                      <!-- This is required to prevent bugs with Safari -->
                      <option disabled value="">Choose a tag...</option>
                    </template>
                  </b-form-select>
                </template>
              </b-form-tags>
            </b-form-group>
            <!-- referralLinkLabel -->
            <b-form-group
              v-if="checkout_popup_data.showArray.includes('Referral Link')"
              id="fieldset-horizontal"
              label-cols-sm="4"
              label-cols-lg="3"
              content-cols-sm="8"
              content-cols-lg="9"
              label="Affiliate Link Label:"
              label-for="height"
            >
              <b-form-input
                id="height"
                v-model="checkout_popup_data.referralLinkLabel"
              ></b-form-input>
            </b-form-group>
            <!-- couponcode label -->
            <b-form-group
              v-if="checkout_popup_data.showArray.includes('Coupon Code')"
              id="fieldset-horizontal"
              label-cols-sm="4"
              label-cols-lg="3"
              content-cols-sm="8"
              content-cols-lg="9"
              label="Coupon Code Label:"
              label-for="height"
            >
              <b-form-input
                id="height"
                v-model="checkout_popup_data.couponCodeLabel"
              ></b-form-input>
              <span class="form-text text-muted mt-5">
              Activate the <a class="font-weight-bold" href="#/settings/coupons">automatic coupon</a> feature to auto-generate the dynamic coupon code
            </span>
            </b-form-group>
          </div>
        </div>
        <div class="togle-card">
          <div class="d-flex justify-content-between togle-label">
            <span class="font-size-h6">Sharing Options</span>
            <button
              class="btn btn-edit-gray"
              @click="isCloseSharingBlock = !isCloseSharingBlock"
            >
              {{ isCloseSharingBlock == true ? "Edit" : "Close" }}
            </button>
          </div>
          <div class="p-3" v-if="!isCloseSharingBlock">
            <!-- shareArray -->
            <b-form-group
              label-cols-sm="4"
              label-cols-lg="3"
              content-cols-sm="8"
              content-cols-lg="9"
              label="Share"
              label-for="tags-component-select"
            >
              <b-form-tags
                id="tags-component-select"
                v-model="checkout_popup_data.shareArray"
                size="lg"
                class="mb-2"
                add-on-change
                no-outer-focus
              >
                <template
                  v-slot="{
                    tags,
                    inputAttrs,
                    inputHandlers,
                    disabled,
                    removeTag,
                  }"
                >
                  <ul
                    v-if="tags.length > 0"
                    class="list-inline d-inline-block mb-2"
                  >
                    <li v-for="tag in tags" :key="tag" class="list-inline-item">
                      <b-form-tag
                        @remove="removeTag(tag)"
                        :title="tag"
                        :disabled="disabled"
                        variant="primary"
                        >{{ tag }}</b-form-tag
                      >
                    </li>
                  </ul>
                  <b-form-select
                    v-bind="inputAttrs"
                    v-on="inputHandlers"
                    :disabled="disabled || availableOptionsShare.length === 0"
                    :options="availableOptionsShare"
                  >
                    <template #first>
                      <!-- This is required to prevent bugs with Safari -->
                      <option disabled value="">Choose a tag...</option>
                    </template>
                  </b-form-select>
                </template>
              </b-form-tags>
            </b-form-group>
            <!-- share message -->
            <b-form-group
              v-if="checkout_popup_data.shareArray.length > 0"
              id="fieldset-horizontal"
              label-cols-sm="4"
              label-cols-lg="3"
              content-cols-sm="8"
              content-cols-lg="9"
              label="Sharing Message:"
              label-for="height"
            >
              <b-form-input
                id="height"
                v-model="checkout_popup_data.sharringMsg"
              ></b-form-input>
            </b-form-group>
          </div>
        </div>
      </div>
      <div class="togle-card">
        <div class="d-flex justify-content-between togle-label">
          <span class="font-size-h6">Other Configuration</span>
          <button
            class="btn btn-edit-gray"
            @click="isCloseOtherBlock = !isCloseOtherBlock"
          >
            {{ isCloseOtherBlock == true ? "Edit" : "Close" }}
          </button>
        </div>
        <div class="p-3" v-show="!isCloseOtherBlock">
          <!-- close on background -->
          <b-form-group
            id="fieldset-horizontal"
            label-cols-sm="4"
            label-cols-lg="3"
            content-cols-sm="8"
            content-cols-lg="9"
            label="Close on background click:"
            label-for="onclickBg"
          >
            <b-form-checkbox
              id="onclickBg"
              switch
              size="lg"
              value="1"
              unchecked-value="0"
              v-model="checkout_popup_data.closeOnBg"
            ></b-form-checkbox>
            <span class="form-text text-muted mt-5">
              When enabled, customers can click outside of the popup to close
              it.
            </span>
          </b-form-group>
          <!-- embeded -->
          <b-form-group
            id="fieldset-horizontal"
            label-cols-sm="4"
            label-cols-lg="3"
            content-cols-sm="8"
            content-cols-lg="9"
            label="Embed in the page:"
            label-for="embed"
          >
            <b-form-checkbox
              id="embed"
              switch
              size="lg"
              value="1"
              unchecked-value="0"
              v-model="checkout_popup_data.embededInPage"
            ></b-form-checkbox>
            <span class="form-text text-muted mt-5">
              When enabled, the invitation will be embedded in the thank you
              page (instead of showing pop-up)
            </span>
          </b-form-group>
          <!-- shown only per 1 customer -->
          <b-form-group
            id="fieldset-horizontal"
            label-cols-sm="4"
            label-cols-lg="3"
            content-cols-sm="8"
            content-cols-lg="9"
            label="Show only once per customer:"
            label-for="showOnce"
          >
            <b-form-checkbox
              id="showOnce"
              switch
              size="lg"
              value="1"
              unchecked-value="0"
              v-model="checkout_popup_data.showOnce"
            ></b-form-checkbox>
            <span class="form-text text-muted mt-5">
              When enabled, this popup is shown only once for each customer
            </span>
          </b-form-group>
          <div v-if="checkout_popup_data.popupType == 'advanced'">
            <!-- choose program -->
            <b-form-group
              id="fieldset-horizontal"
              label-cols-sm="4"
              label-cols-lg="3"
              content-cols-sm="8"
              content-cols-lg="9"
              label="Program:"
              label-for="css"
            >
              <select
                v-model="checkout_popup_data.program"
                class="form-control form-control-custom"
              >
                <option
                  v-for="(o, index) in groupSelectOption"
                  :value="o.value"
                  :o="o"
                  :key="index"
                >
                  {{ o.text }}
                </option>
              </select>
              <span class="form-text text-muted">
                Select the program you want to invite customers to join
              </span>
            </b-form-group>
            <!-- more than -->
            <b-form-group
              id="fieldset-horizontal"
              label-cols-sm="4"
              label-cols-lg="3"
              content-cols-sm="8"
              content-cols-lg="9"
              label="Only display if order value is more than:"
              label-for="height"
            >
              <b-input-group>
                <template #append>
                  <b-input-group-text>{{ symbolCurrency }}</b-input-group-text>
                </template>
                <b-form-input
                  id="height"
                  type="number"
                  v-model="checkout_popup_data.showIfMoreThan"
                ></b-form-input>
              </b-input-group>
              <span class="form-text text-muted">
                The popup is only displayed if the total order amount is more
                than the number you enter above
              </span>
            </b-form-group>
            <!-- extra css -->
            <b-form-group
              id="fieldset-horizontal"
              label-cols-sm="4"
              label-cols-lg="3"
              content-cols-sm="8"
              content-cols-lg="9"
              label="Extra css:"
              label-for="css"
            >
              <b-form-textarea
                id="css"
                v-model="checkout_popup_data.ExtraCss"
                placeholder="You should using !important in your css"
                rows="4"
              ></b-form-textarea>
            </b-form-group>
          </div>
        </div>
      </div>
      <div class="d-flex justify-content-center">
        <LoadingSubmitButton :loading="submitLoading" @click="onClickSave">
          Save
        </LoadingSubmitButton>
      </div>
    </div>
  </div>
</template>

<script>
import {
  BFormInput,
  BFormGroup,
  BFormFile,
  BFormSelect,
  BFormCheckbox,
  BInputGroup,
  BFormTags,
  BFormTag,
  BFormTextarea,
  BInputGroupText,
} from "bootstrap-vue";
import ApiService from "../../../core/services/api.service";
import LoadingSubmitButton from "@/view/content/LoadingSubmitButton.vue";
import { mapState, mapActions, mapGetters } from "vuex";
export default {
  components: {
    BFormInput,
    BFormGroup,
    BFormFile,
    BFormSelect,
    BFormCheckbox,
    BInputGroup,
    BFormTags,
    BFormTag,
    BFormTextarea,
    LoadingSubmitButton,
    BInputGroupText,
  },
  data() {
    return {
      image: null,
      submitLoading: false,
      isCloseImgBlock: true,
      isCloseContentBlock: true,
      isCloseReferralBlock: true,
      isCloseSharingBlock: true,
      isCloseOtherBlock: true,
      isCloseBtnBlock: true,
      isCloseImgClickBlock: true,
      openin: [
        { value: 0, text: "Open in this window" },
        { value: 1, text: "Open in new tab" },
      ],
      optionShow: ["Referral Link", "Coupon Code"],
      optionShare: ["Facebook", "Twitter", "Pinterest", "Linkedln"],
    };
  },
  methods: {
    ...mapActions(["updatePopupSetting"]),
    onClickSave() {
      this.submitLoading = true;
      if (this.image != null) {
        if (this.validateSize(this.image)) {
          let formData = new FormData();
          formData.append("image", this.image);
          ApiService.post("/settings/upload-post-checkout", formData).then(
            ({ data }) => {
              this.checkout_popup_data.pathCDN = data.data.pathCDN;
              this.checkout_popup_data.imgSrc = data.data.src;
              this.checkout_popup_data.ExtraCss = this.regexExtraCss(
                this.checkout_popup_data.ExtraCss
              );
              let obj = this.checkout_popup_data;
              this.updatePopupSetting(obj).then(() => {
                this.submitLoading = false;
                this.$toast.success("Popup settings have been updated!", {
                  position: "bottom",
                });
              });
            }
          );
        } else {
          this.submitLoading = false;
          this.$toast.error("File size exceeds 2 MiB", {
            position: "bottom",
          });
        }
      } else {
        this.checkout_popup_data.ExtraCss = this.regexExtraCss(
          this.checkout_popup_data.ExtraCss
        );
        let obj = this.checkout_popup_data;
        this.updatePopupSetting(obj).then(() => {
          this.submitLoading = false;
          this.$toast.success("Updated", {
            position: "bottom",
          });
        });
      }
    },
    previewImage: function (event) {
      var input = event.target;
      if (input.files.length > 0) {
        this.checkout_popup_data.pathCDN = URL.createObjectURL(input.files[0]);
        this.image = input.files[0];
      }
    },
    validateSize(file) {
      const fileSize = file.size / 1024 / 1024; // in MiB
      if (fileSize < 2) {
        return true;
      } else {
        return false;
      }
    },
    changeLogoDefault() {
      this.checkout_popup_data.pathCDN = null;
      this.checkout_popup_data.imgSrc = null;
      this.$refs["logoInputFile"].reset();
      this.image = null;
    },
    regexExtraCss(css) {
      if (css) {
        css = css.replace(/(\r\n|\n|\r)/gm, "");
      }
      return css;
    },
  },
  computed: {
    ...mapGetters(["checkout_popup_data", "subDomain", "groups", "subDomain"]),
    availableOptionsShow() {
      return this.optionShow.filter(
        (opt) => this.checkout_popup_data.showArray.indexOf(opt) === -1
      );
    },
    availableOptionsShare() {
      return this.optionShare.filter(
        (opt) => this.checkout_popup_data.shareArray.indexOf(opt) === -1
      );
    },
    groupSelectOption() {
      let options = [];
      //   lấy group ở store
      let groups = this.groups;
      //   lọc group active
      groups = groups.filter((groups) => groups.is_active == 1);
      //   khai báo một biến lưu giá trị default lấy từ group
      let defaultProgram = null;
      //   FOR để tìm default program
      for (let i = 0; i < groups.length; i++) {
        //   Group nào là default
        if (groups[i].is_default == 1) {
          // đẩy vào mảng option, cộng thêm (Default Program) vào sau
          options.push({
            default: groups[i].is_default,
            value: groups[i].id,
            text: this.groups[i].name + " (Default Program)",
          });
          // Gán giá trị cho biến program default
          defaultProgram = groups[i].id;
        } else {
          options.push({
            default: groups[i].is_default,
            value: groups[i].id,
            text: groups[i].name,
          });
        }
      }
      //   Nếu như chưa lưu program nào thì thay bằng default
      if (this.checkout_popup_data.program == null) {
        this.checkout_popup_data.program = defaultProgram;
      }
      return options;
    },
    symbolCurrency() {
      return this.$store.getters.symbolCurrency;
    },
    GroupSignupPageDefault() {
      return (
        "https://" +
        this.subDomain +
        "." +
        process.env.MIX_BASE_DOMAIN +
        "/#/register"
      );
    },
    onClickLink: {
      get: function () {
        return (
          this.checkout_popup_data.onClickLink || this.GroupSignupPageDefault
        );
      },
      set: function (newVal) {
        if (newVal) {
          this.checkout_popup_data.onClickLink = newVal;
        } else {
          this.checkout_popup_data.onClickLink = this.GroupSignupPageDefault;
        }
      },
    },
  },
};
</script>
<style scoped>
.card {
  border: 1px solid #ebedf3 !important;
}
.del-img {
  position: absolute;
  bottom: -10px;
  right: -10px;
}
.image-input img {
  width: auto;
  min-width: 60px;
  height: 120px;
}
</style>
<style lang="scss">
.font-picker ul.expanded {
  z-index: 3;
}
.one-colorpicker {
  .color-block {
    border: 2px solid #d9d9d9;
  }
}
.list-inline-item {
  margin-bottom: 5px;
}
.togle-card {
  border-radius: 5px;
  border: 1px solid rgb(0 0 0 / 13%);
  margin-bottom: 10px;
}
.togle-label {
  background-color: #f3f6f9;
  align-items: center;
  padding: 5px;
  border-radius: inherit;
}
.btn-edit-gray {
  color: #fff;
  background-color: rgb(187, 187, 187);
  border-color: rgb(187, 187, 187);
}
.btn-edit-gray:hover {
  color: #fff;
  background-color: rgb(156, 156, 156);
  border-color: rgb(156, 156, 156);
}
</style>
