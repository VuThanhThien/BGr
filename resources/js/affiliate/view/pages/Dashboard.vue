<template>
  <div>
    <div class="row">
      <div class="col-xl-6">
        <div class="card card-custom card-stretch gutter-b">
          <div class="card-header justify-content-start border-0 py-5">
            <h3 class="card-title font-weight-bolder">{{ $t("welcome") }}!</h3>
          </div>
          <div class="card-body border-top d-flex flex-column">
            <span class="font-weight-bold"
              >{{ $t("welcome_text_in_dashboard") }}
            </span>
          </div>
        </div>
      </div>
      <div class="col-xl-6">
        <div class="card card-custom card-stretch gutter-b">
          <div class="card-header justify-content-start border-0 py-5">
            <h3 class="card-title font-weight-bolder">
              {{ $t("program_details") }}
            </h3>
          </div>
          <div class="card-body border-top d-flex flex-column">
            <span class="font-weight-bold mb-3">
              {{ $t("name") }}:
              <strong class="text-primary"> {{ group.name }}</strong>
            </span>
            <span class="font-weight-bold mb-3">
              {{ $t("commission_type") }}:
              <strong class="text-primary">
                {{ this.genTextCommissionType(group.commission_type) }}
              </strong>
            </span>
            <span class="font-weight-bold mb-3">
              {{ $t("commission_amount") }}:
              <strong class="text-primary">
                {{
                  this.formatCommissionAmount(
                    group.commission_type,
                    group.commission_amount,
                    format
                  )
                }}
              </strong>
            </span>
          </div>
        </div>
      </div>
    </div>
    <!--begin::Body-->
    <div id="myTabContent" class="tab-content">
      <b-overlay
        class="d-flex flex-column"
        opacity="0"
        :show="loadingDasboard"
        rounded="sm"
      >
        <div class="row">
          <div class="mb-5 col-xl-3">
            <DateRange
              ref="dateRange"
              @updateTimePicker="updateTimePicker"
              @updatedateRangText="updatedateRangText"
              @cancelFilterDate="cancelFilterDate"
            />
          </div>
        </div>
        <div class="row mb-5">
          <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card card-custom">
              <div class="card-body p-5">
                <div class="row">
                  <div class="col-8">
                    <div class="numbers">
                      <div class="card-toolbar mb-2">
                        <span class="text-muted">{{ getDateRageText() }}</span>
                      </div>
                      <span class="text-brand text-md font-weight-bolder">{{
                        $t("clicks")
                      }}</span>
                      <h3 class="font-weight-bolder mb-0 mt-1">
                        {{ statistics.clicks }}
                      </h3>
                    </div>
                  </div>
                  <div
                    class="
                      col-4
                      text-end
                      d-flex
                      align-items-center
                      justify-content-end justify-content-end
                    "
                  >
                    <div class="symbol symbol-50 symbol-bixgrow">
                      <span class="symbol-label symbol-label-custom shadow">
                        <span class="svg-icon svg-icon-2x svg-icon-white">
                          <inline-svg
                            src="media/svg/icons/General/Cursor.svg"
                          />
                        </span>
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card card-custom">
              <div class="card-body p-5">
                <div class="row">
                  <div class="col-8">
                    <div class="numbers">
                      <div class="card-toolbar mb-2">
                        <span class="text-muted">{{ getDateRageText() }}</span>
                      </div>
                      <span class="text-brand text-md font-weight-bolder">{{
                        $t("orders")
                      }}</span>
                      <h3 class="font-weight-bolder mb-0 mt-1">
                        {{ statistics.orders }}
                      </h3>
                    </div>
                  </div>
                  <div
                    class="
                      col-4
                      text-end
                      d-flex
                      align-items-center
                      justify-content-end justify-content-end
                    "
                  >
                    <div class="symbol symbol-50 symbol-bixgrow">
                      <span class="symbol-label symbol-label-custom shadow">
                        <span class="svg-icon svg-icon-2x svg-icon-white">
                          <inline-svg
                            src="media/svg/icons/Shopping/Cart2.svg"
                          />
                        </span>
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card card-custom">
              <div class="card-body p-5">
                <div class="row">
                  <div class="col-8">
                    <div class="numbers">
                      <div class="card-toolbar mb-2">
                        <span class="text-muted">{{ getDateRageText() }}</span>
                      </div>
                      <span class="text-brand text-md font-weight-bolder">{{
                        $t("revenue")
                      }}</span>
                      <h3 class="font-weight-bolder mb-0 mt-1">
                        {{ formatMoney(statistics.revenue, format) }}
                      </h3>
                    </div>
                  </div>
                  <div
                    class="
                      col-4
                      text-end
                      d-flex
                      align-items-center
                      justify-content-end justify-content-end
                    "
                  >
                    <div class="symbol symbol-50 symbol-bixgrow">
                      <span class="symbol-label symbol-label-custom shadow">
                        <span class="svg-icon svg-icon-2x svg-icon-white">
                          <inline-svg
                            src="media/svg/icons/Shopping/Chart-line1.svg"
                          />
                        </span>
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-3 col-sm-6">
            <div class="card card-custom">
              <div class="card-body p-5">
                <div class="row">
                  <div class="col-8">
                    <div class="numbers">
                      <div class="card-toolbar mb-2">
                        <span class="text-muted">{{ getDateRageText() }}</span>
                      </div>
                      <span class="text-brand text-md font-weight-bolder">{{
                        $t("commission")
                      }}</span>
                      <h3 class="font-weight-bolder mb-0 mt-1">
                        {{ formatMoney(statistics.commission, format) }}
                      </h3>
                    </div>
                  </div>
                  <div
                    class="
                      col-4
                      text-end
                      d-flex
                      align-items-center
                      justify-content-end justify-content-end
                    "
                  >
                    <div class="symbol symbol-50 symbol-bixgrow">
                      <span class="symbol-label symbol-label-custom shadow">
                        <span class="svg-icon svg-icon-2x svg-icon-white">
                          <i
                            class="fas fa-hand-holding-usd icon-2x"
                            style="color: #fff"
                          ></i>
                        </span>
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!--begin::Body-->
        <div class="row">
          <div class="col-md-6">
            <div class="card card-custom gutter-b card-stretch">
              <div
                class="card-header align-items-center border-0"
                style="border-bottom: 1px solid #e8e9f2 !important"
              >
                <h4 class="card-title align-items-start flex-column">
                  <span class="font-weight-bolder text-brand">{{
                    $t("affiliate_link")
                  }}</span>
                </h4>
              </div>
              <div class="card-body pt-0 mt-5">
                <span class="font-weight-bolder text-dark"
                  >{{ $t("default_affiliate_link") }}
                </span>
                <div class="d-flex mt-4 mb-4">
                  <input
                    type="text"
                    disabled
                    class="form-control form-control-solid me-3 flex-grow-1"
                    ref="aff_link_input"
                    name="search"
                    v-model="affLink"
                  />
                  <button
                    class="btn btn-light fw-bolder flex-shrink-0 ml-1"
                    @click="copy('aff_link_input', $event)"
                  >
                    {{ $t("copy") }}
                  </button>
                  <button
                    v-if="allowAffiliatesCustomLink && !affiliatesCustomlink"
                    v-b-modal.affiliates_custom_link
                    class="
                      btn btn-icon btn-light btn-hover-primary
                      fw-bolder
                      flex-grow-1 flex-shrink-0
                      ml-1
                    "
                    v-b-tooltip.hover
                    :title="getTextLocale('add_custom_link')"
                  >
                    <span class="svg-icon svg-icon-md svg-icon-primary">
                      <inline-svg src="media/svg/icons/Code/Plus.svg" />
                    </span>
                  </button>
                  <b-modal
                    id="affiliates_custom_link"
                    size="lg"
                    :title="getTextLocale('add_your_custom_affiliate_link')"
                    hide-footer
                    ref="affiliatesCustomLink"
                  >
                    <div class="card card-custom card-stretch gutter-b">
                      <div class="card-body">
                        <alert-note :type="'alert-default'">
                          {{ $t("add_custom_link_text_note") }}
                        </alert-note>
                        <div class="form-group">
                          <label>{{ $t("affiliate_link") }}</label>
                          <div class="input-group">
                            <div class="input-group-prepend">
                              <span class="input-group-text">{{
                                linkShopDomain
                              }}</span>
                            </div>
                            <input
                              style="height: auto"
                              type="text"
                              v-model="pathCustomAffiliateLink"
                              class="form-control"
                              :placeholder="
                                getTextLocale('enter_your_custom_path_here')
                              "
                              :class="{ 'border-error': !isPathValid }"
                            />
                          </div>
                          <div
                            class="invalid-feedback"
                            style="text-align: center"
                            v-bind:style="{
                              display: isPathValid ? 'none' : 'block',
                            }"
                          >
                            {{ $t("can_not_be_blank") }}
                          </div>
                        </div>
                        <div class="w-100">
                          <LoadingSubmitButton
                            :loading="submitLoading"
                            class="float-right"
                            @click="saveCustomAffiliateLink"
                          >
                            {{ $t("save") }}
                          </LoadingSubmitButton>
                        </div>
                      </div>
                    </div>
                  </b-modal>
                </div>
                <span
                  v-if="affiliatesCustomlink"
                  class="font-weight-bolder text-dark"
                  >{{ $t("custom_affiliate_link") }}
                </span>
                <div class="d-flex mt-1" v-if="affiliatesCustomlink">
                  <input
                    type="text"
                    disabled
                    class="form-control form-control-solid me-3 flex-grow-1"
                    ref="affiliates_custom_link_aff"
                    v-model="affiliatesCustomlinkFull"
                  />
                  <button
                    class="btn btn-light fw-bolder flex-shrink-0 ml-1"
                    @click="copy('affiliates_custom_link_aff', $event)"
                  >
                    {{ $t("copy") }}
                  </button>
                  <button
                    v-if="allowAffiliatesCustomLink"
                    @click="openModalEditAffiliatesCustomLinkAff"
                    class="
                      btn btn-icon btn-light btn-hover-primary
                      fw-bolder
                      flex-grow-1 flex-shrink-0
                      ml-1
                    "
                    v-b-tooltip.hover
                    :title="getTextLocale('edit_custom_link')"
                  >
                    <span class="svg-icon svg-icon-md svg-icon-primary">
                      <inline-svg
                        src="media/svg/icons/Communication/Write.svg"
                      />
                    </span>
                  </button>
                  <b-modal
                    id="edit_affiliates_custom_link_aff"
                    size="lg"
                    :title="getTextLocale('edit_your_custom_affiliate_link')"
                    hide-footer
                    ref="editAffiliatesCustomLinkAff"
                  >
                    <div class="card card-custom card-stretch gutter-b">
                      <div class="card-body">
                        <alert-note :type="'alert-default'">
                          {{ $t("edit_custom_link_text_note") }}
                        </alert-note>
                        <div class="form-group">
                          <label>{{ $t("affiliate_link") }}</label>
                          <div class="input-group">
                            <div class="input-group-prepend">
                              <span class="input-group-text">{{
                                linkShopDomain
                              }}</span>
                            </div>
                            <input
                              style="height: auto"
                              type="text"
                              v-model="pathCustomAffiliateLink"
                              class="form-control"
                              :placeholder="
                                getTextLocale('enter_your_custom_path_here')
                              "
                              :class="{ 'border-error': !isPathValid }"
                            />
                          </div>
                          <div
                            class="invalid-feedback"
                            style="text-align: center"
                            v-bind:style="{
                              display: isPathValid ? 'none' : 'block',
                            }"
                          >
                            {{ $t("can_not_be_blank") }}
                          </div>
                        </div>
                        <div class="w-100">
                          <LoadingSubmitButton
                            :loading="submitLoading"
                            class="float-right"
                            @click="saveEditCustomAffiliateLink"
                          >
                            {{ $t("save") }}
                          </LoadingSubmitButton>
                        </div>
                      </div>
                    </div>
                  </b-modal>
                </div>
              </div>
              <div class="card-body pt-0">
                <h6 class="card-title align-items-start flex-column">
                  <span class="font-weight-bolder text-dark"
                    >{{ $t("share_on_social_media") }}
                  </span>
                </h6>
                <div class="d-flex mt-2 flex-wrap" >
                  <div class="symbol-label mr-1">
                    <a
                      :href="'https://www.facebook.com/sharer.php?u=' + affLink"
                      data-toggle-t="tooltip"
                      data-placement="top"
                      :data-original-title="getTextLocale('share_on_fb')"
                      class="btn btn-icon btn-facebook btn-sm"
                      target="_blank"
                    >
                      <i class="fab fa-facebook-f text-white"></i>
                    </a>
                  </div>
                  <div class="symbol-label mr-1">
                    <a
                      :href="'https://twitter.com/intent/tweet?url=' + affLink"
                      data-toggle-t="tooltip"
                      data-placement="top"
                      :data-original-title="getTextLocale('share_on_fb')"
                      class="btn btn-icon btn-twitter btn-sm"
                      target="_blank"
                    >
                      <i class="fab fa-twitter text-white"></i>
                    </a>
                  </div>
                  <div class="symbol-label mr-1">
                    <a
                      :href="
                        'https://www.linkedin.com/shareArticle?mini=true&url=' +
                        affLink
                      "
                      data-toggle-t="tooltip"
                      data-placement="top"
                      :data-original-title="getTextLocale('share_on_fb')"
                      class="btn btn-icon btn-linkedin btn-sm"
                      target="_blank"
                    >
                      <i class="fab fa-linkedin-in text-white"></i>
                    </a>
                  </div>
                  <div class="symbol-label">
                    <a
                      :href="
                        'http://pinterest.com/pin/create/link/?url=' + affLink
                      "
                      data-toggle-t="tooltip"
                      data-placement="top"
                      :data-original-title="getTextLocale('share_on_fb')"
                      class="btn btn-icon btn-pinterest btn-sm"
                      target="_blank"
                    >
                      <i class="fab fa-pinterest-p text-white"></i>
                    </a>
                  </div>
                  <button
                    v-b-modal.new_referral_link
                    type="button"
                    class="btn btn-primary btn-lg btn-block mt-5"
                  >
                    {{ $t("get_referral_link_by_a_specific_url") }}
                  </button>
                  <!--Modal referral link product -->
                  <b-modal
                    id="new_referral_link"
                    size="lg"
                    :title="
                      getTextLocale('get_referral_link_by_a_specific_url')
                    "
                    hide-footer
                  >
                    <div class="card card-custom card-stretch gutter-b">
                      <div class="card-body pt-0">
                        <alert-note :type="'alert-default'">{{
                          $t("get_referral_link_text_note")
                        }}</alert-note>
                        <h5 class="align-items-start flex-column">
                          <span class="font-weight-bolder text-dark"
                            >{{ $t("specific_link") }}
                          </span>
                        </h5>
                        <div class="d-flex">
                          <input
                            type="text"
                            class="form-control me-3 flex-grow-1"
                            ref="referral_link_input"
                            name="search"
                            :placeholder="
                              getTextLocale('enter_specific_link_guide')
                            "
                            v-model="linkProductInput"
                            :class="{ 'border-error': !isLinkValid }"
                          />
                          <button
                            @click="generateLinkProduct(linkProductInput)"
                            class="btn btn-primary fw-bolder flex-shrink-0 ml-1"
                          >
                            {{ $t("generate") }}
                          </button>
                        </div>
                        <div
                          class="invalid-feedback"
                          v-bind:style="{
                            display: isLinkValid ? 'none' : 'block',
                          }"
                        >
                          {{ $t("field_is_required_and_valid_link") }}
                        </div>
                        <div class="d-flex mt-5">
                          <input
                            type="text"
                            disabled
                            class="form-control me-3 flex-grow-1"
                            ref="referral_link_coppy_product_input"
                            name="search"
                            v-model="linkCopyProductInput"
                          />
                          <button
                            @click="copyGenerateUrl"
                            class="btn btn-light fw-bolder flex-shrink-0 ml-1"
                          >
                            {{ $t("copy") }}
                          </button>
                        </div>
                      </div>
                    </div>
                  </b-modal>
                </div>
                <div></div>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card card-custom gutter-b card-stretch">
              <div
                class="card-header align-items-center border-0"
                style="border-bottom: 1px solid #e8e9f2 !important"
              >
                <h4 class="card-title align-items-start flex-column">
                  <span class="font-weight-bolder text-brand">{{
                    $t("coupons")
                  }}</span>
                </h4>
              </div>
              <div class="card-body pt-0 mt-5">
                <template v-if="coupons.data.length > 0">
                  <div class="col-12">
                    <div
                      class="coupon-wrapper"
                      v-for="(coupon, index) in coupons.data"
                      :key="index"
                    >
                      <div class="coupon-left col-3">
                        <input
                          class="copy-share-link flex-1"
                          :value="linkCoupon + coupon.code"
                          :ref="coupon.code + '_' + index"
                        />
                        <div class="d-flex flex-column justify-content-center">
                          <h4 class="font-weight-bold tag-coupon">
                            {{
                              formatCommissionAmount(
                                coupon.discount_type,
                                parseInt(-1 * coupon.discount_amount),
                                format
                              )
                            }} {{$t('off')}}</h4>
                        </div>
                      </div>
                      <div class="coupon-con col-4 d-flex flex-column ">
                          <span class="mt-1">{{$t('code')}}</span>
                        <h4
                            class="font-weight-bold w-100 text-center"
                            style="padding: 5px;border: dashed .5px #fecb0c;background-color: #fff"
                            :ref="coupon.code"
                            >{{ coupon.code }}</h4>
                      </div>
                      <div class="col-5">
                          <div class="d-flex flex-column justify-content-center w-100 h-auto">
                          <span
                            style="cursor: pointer; margin-bottom: 5px"
                            class="label label-lg label-dark-custom px-7 label-inline h-auto"
                            @click="copyText(coupon.code, $event)"
                          >
                            {{ $t("copy") }}
                          </span>
                          <span
                            style="cursor: pointer"
                            class="label label-lg label-dark-custom px-7 label-inline h-auto"
                            @click="copyText(coupon.code + '_' + index, $event)"
                          >
                            {{ $t("copy_shareable_Link") }}
                          </span>
                        </div>
                      </div>
                    </div>
                  </div>
                </template>
                <template v-else>
                  <div class="d-flex align-items-center">
                    <div class="d-flex flex-column flex-grow-1">
                      <span class="text-muted font-weight-bold">{{
                        $t("no_data")
                      }}</span>
                    </div>
                  </div>
                </template>
              </div>
              <div
                v-if="coupons.data.length > 0"
                class="
                  d-flex
                  justify-content-between
                  align-items-center
                  flex-wrap
                  card-footer
                "
              >
                <div class="d-flex flex-wrap py-2">
                  <pagination
                    :data="coupons"
                    @pagination-change-page="loadCoupons"
                    :limit="3"
                  >
                  </pagination>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-12" v-if="isEnableMlm">
            <div class="card card-custom gutter-b card-stretch">
              <div
                class="card-header align-items-center border-0"
                style="border-bottom: 1px solid #e8e9f2 !important"
              >
                <h4 class="card-title align-items-start flex-column">
                  <span class="font-weight-bolder text-brand">{{
                    $t("network_link")
                  }}</span>
                </h4>
              </div>
              <div class="card-body pt-0">
                <div class="d-flex mt-5">
                  <input
                    type="text"
                    disabled
                    class="form-control form-control-solid me-3 flex-grow-1"
                    ref="network_link_input"
                    name="search"
                    v-model="networkLink"
                  />
                  <button
                    class="btn btn-light fw-bolder flex-shrink-0 ml-1"
                    @click="copy('network_link_input', $event)"
                  >
                    {{ $t("copy") }}
                  </button>
                </div>
              </div>
              <div class="card-body pt-0">
                <h6 class="card-title align-items-start flex-column">
                  <span class="font-weight-bolder text-dark"
                    >{{ $t("share_on_social_media") }}
                  </span>
                </h6>
                <div class="d-flex mt-2 flex-wrap" >
                  <div class="symbol-label mr-1">
                    <a
                      :href="'https://www.facebook.com/sharer.php?u=' + affLink"
                      data-toggle-t="tooltip"
                      data-placement="top"
                      :data-original-title="getTextLocale('share_on_fb')"
                      class="btn btn-icon btn-facebook btn-sm"
                      target="_blank"
                    >
                      <i class="fab fa-facebook-f text-white"></i>
                    </a>
                  </div>
                  <div class="symbol-label mr-1">
                    <a
                      :href="'https://twitter.com/intent/tweet?url=' + affLink"
                      data-toggle-t="tooltip"
                      data-placement="top"
                      :data-original-title="getTextLocale('share_on_fb')"
                      class="btn btn-icon btn-twitter btn-sm"
                      target="_blank"
                    >
                      <i class="fab fa-twitter text-white"></i>
                    </a>
                  </div>
                  <div class="symbol-label mr-1">
                    <a
                      :href="
                        'https://www.linkedin.com/shareArticle?mini=true&url=' +
                        affLink
                      "
                      data-toggle-t="tooltip"
                      data-placement="top"
                      :data-original-title="getTextLocale('share_on_fb')"
                      class="btn btn-icon btn-linkedin btn-sm"
                      target="_blank"
                    >
                      <i class="fab fa-linkedin-in text-white"></i>
                    </a>
                  </div>
                  <div class="symbol-label">
                    <a
                      :href="
                        'http://pinterest.com/pin/create/link/?url=' + affLink
                      "
                      data-toggle-t="tooltip"
                      data-placement="top"
                      :data-original-title="getTextLocale('share_on_fb')"
                      class="btn btn-icon btn-pinterest btn-sm"
                      target="_blank"
                    >
                      <i class="fab fa-pinterest-p text-white"></i>
                    </a>
                  </div>
                  <!-- <div class="symbol-label">
                <a
                  href="#"
                  url_qrcode="https://af.uppromote.com/kanawut/dashboard/create_qrcode"
                  class="btn btn-icon btn-twitter btn-sm "
                  :data_link="affLink"
                  data-toggle="modal"
                  data-target="#modal_qrcode"
                  ><i class="fas fa-qrcode text-white"></i
                ></a>
              </div> -->
                </div>
              </div>
            </div>
          </div>
        </div>
        <template #overlay>
          <TableLoader></TableLoader>
        </template>
      </b-overlay>
    </div>
    <!--end::Dashboard-->
    <b-overlay
      class="d-flex flex-column"
      opacity="0"
      :show="loadPerformanceOverplay"
      rounded="sm"
    >
      <div class="row">
        <div class="col-xl-8">
          <div class="card card-custom card-stretch gutter-b">
            <div class="card-header border-0 py-5">
              <h3 class="card-title font-weight-bolder text-brand">
                {{ $t("your_performance") }}
              </h3>
            </div>
            <div class="card-body border-top d-flex flex-column">
              <div class="row mt-2">
                <div class="col-xl-12">
                  <div class="row justify-content-between">
                    <div class="col-md-5 mb-3">
                      <ul
                        class="
                          nav nav-pills nav-pills-sm nav-dark-75
                          float-left
                          pl-2
                          bg-white
                          rounded
                        "
                        id="myTab"
                        role="tablist"
                      >
                        <li class="nav-item nav-item-bixgrow">
                          <a
                            class="nav-link py-2 px-4"
                            data-toggle="tab"
                            :class="[type == 'clicks' ? 'active' : '']"
                            href="#kt_tab_pane_11_1"
                            @click.prevent="loadType('clicks')"
                            >{{ $t("clicks") }}
                            <span class="ml-2" v-if="type == 'clicks'">{{
                              total
                            }}</span>
                          </a>
                        </li>
                        <li class="nav-item nav-item-bixgrow">
                          <a
                            class="nav-link py-2 px-4"
                            :class="[type == 'orders' ? 'active' : '']"
                            data-toggle="tab"
                            href="#kt_tab_pane_11_2"
                            @click.prevent="loadType('orders')"
                            >{{ $t("conversions") }}
                            <span class="ml-2" v-if="type == 'orders'">{{
                              total
                            }}</span></a
                          >
                        </li>
                        <li class="nav-item nav-item-bixgrow">
                          <a
                            class="nav-link py-2 px-4"
                            :class="[type == 'sales' ? 'active' : '']"
                            data-toggle="tab"
                            href="#kt_tab_pane_11_3"
                            @click.prevent="loadType('sales')"
                            >{{ $t("sales") }}
                            <span class="ml-2" v-if="type == 'sales'">{{
                              formatMoney(total, format)
                            }}</span></a
                          >
                        </li>
                      </ul>
                    </div>
                    <div class="col-md-3 mb-2">
                      <select class="form-control form-control-custom">
                        <option value="" selected class="text-muted">
                          {{ $t("all_source") }}
                        </option>
                      </select>
                    </div>
                  </div>
                  <div class="justify-content-center">
                    <apexchart
                      ref="apexchart"
                      type="area"
                      height="400"
                      :options="chartOptions1"
                      :series="series1"
                    ></apexchart>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-4">
          <div class="card card-custom card-stretch gutter-b">
                <div class="card-header border-0 py-5">
              <h3 class="card-title font-weight-bolder text-brand">
                {{ $t("commission_status") }}
              </h3>
            </div>
                <div class="card-body border-top p-0 position-relative overflow-hidden">
                  <div class="card-spacer">
                    <div
                      id="chart"
                      class="d-flex justify-content-center"
                      v-if="chartHaveData"
                    >
                      <apexchart
                        type="pie"
                        width="300"
                        :options="chartOptions"
                        :series="series"
                      ></apexchart>
                    </div>
                    <div class="mt-10 mb-5">
                      <div class="row row-paddingless">
                        <div class="col">
                          <div class=" border-dash">
                            <div>
                              <div
                                class="
                                  font-size-h4
                                  text-warning
                                  font-weight-bolder
                                "
                              >
                                {{ formatMoney(series[0], format) }}
                              </div>
                              <div
                                class="
                                  font-size-sm
                                  text-warning
                                  font-weight-bold
                                  mt-1
                                "
                              >
                                {{$t('pending')}}
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col">
                          <div class=" border-dash">
                            <div>
                              <div
                                class="
                                  font-size-h4
                                  text-primary
                                  font-weight-bolder
                                "
                              >
                                {{ formatMoney(series[1], format) }}
                              </div>
                              <div
                                class="
                                  font-size-sm
                                  text-primary
                                  font-weight-bold
                                  mt-1
                                "
                              >
                                {{$t('approved')}}
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row row-paddingless">
                        <div class="col">
                          <div class=" border-dash">
                            <div>
                              <div
                                class="
                                  font-size-h4
                                  text-success
                                  font-weight-bolder
                                "
                              >
                                {{ formatMoney(series[2], format) }}
                              </div>
                              <div
                                class="
                                  font-size-sm
                                  text-success
                                  font-weight-bold
                                  mt-1
                                "
                              >
                                {{$t('paid')}}
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col">
                          <div class=" border-dash">
                            <div>
                              <div
                                class="
                                  font-size-h4
                                  text-danger
                                  font-weight-bolder
                                "
                              >
                                {{ formatMoney(series[3], format) }}
                              </div>
                              <div
                                class="
                                  font-size-sm
                                  text-danger
                                  font-weight-bold
                                  mt-1
                                "
                              >
                                {{$t('rejected')}}
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
        </div>
      </div>
      <template #overlay>
        <TableLoader></TableLoader>
      </template>
    </b-overlay>
  </div>
</template>
<script>
import ApiService from "aff/core/services/api.service";
import moment from "moment";
import TableLoader from "aff/view/content/TableLoader.vue";
import { mapGetters, mapMutations } from "vuex";
import pagination from "laravel-vue-pagination";
import { BOverlay } from "bootstrap-vue";
import DateRange from "aff/view/content/DateRange.vue";
import LoadingSubmitButton from "aff/view/content/LoadingSubmitButton.vue";
export default {
  name: "dashboard",
  data() {
    let dateNow = moment().toDate();
    let momentSevenDaysLast = moment().add(-7, "days");
    let firstDayDateNow = momentSevenDaysLast.toDate();
    let endDate = moment().unix() + 86399;
    let startDate = momentSevenDaysLast.unix();
    return {
      total: 0,
      startDate: startDate,
      endDate: endDate,
      dateRangText: "",
      loadPerformanceOverplay: false,
      type: "clicks",
      showCopyGenerateLink: false,
      chartHaveData: false,
      dateRange: {
        startDate: firstDayDateNow,
        endDate: dateNow,
      },

      coupons: {
        data: [],
      },
      loadingCouponTable: false,
      commissionStatistics: {},
      show: false,
      show1: false,
      loadingDasboard: false,
      statistics: {
        commission: 0,
        clicks: 0,
        revenue: 0,
        orders: 0,
      },
      chartHaveData: false,
      series: [0, 0, 0, 1],
      chartOptions: {
        chart: {
          width: 380,
          type: "pie",
        },
        labels: ["Pending", "Approved", "Paid", "Rejected"],
        colors: ["#ff9900", "#3699FF", "#1BC5BD", "#F64E60"],
        responsive: [
          {
            options: {
              chart: {
                width: 200,
              },
              legend: {
                position: "bottom",
              },
            },
          },
        ],
      },
      series1: [
        {
          name: "clicks",
          data: [],
        },
      ],
      chartOptions1: {
        chart: {
          toolbar: {
            show: false,
          },
          height: 350,
          type: "area",
        },
        dataLabels: {
          enabled: false,
        },
        colors: ["#ff9900"],
        stroke: {
          curve: "smooth",
        },
        tooltip: {
          x: {
            format: "dd/MM/yy",
          },
        },
      },
      isLinkValid: true,
      linkProductInput: "",
      linkCopyProductInput: "",
      submitLoading: false,
      pathCustomAffiliateLink: "",
      isPathValid: true,
      a: {},
    };
  },
  computed: {
    ...mapGetters([
      "subDomain",
      "group",
      "shopDomain",
      "defaultAffiliateLink",
      "allowAffiliatesCustomLink",
    ]),
    linkShopDomain() {
      return "https://" + this.shopDomain + "/";
    },
    linkCoupon() {
      return "https://" + this.shopDomain + "/discount/";
    },
    format() {
      return this.$store.getters.moneyFormat;
    },
    affLink() {
      let linkAff = this.$store.getters.currentUser.primary_aff_link
        ? this.$store.getters.currentUser.primary_aff_link
        : this.defaultAffiliateLink;
      return (
        this.generateLinkAffiliate(linkAff) +
        this.$store.getters.currentUser.group_id +
        ":" +
        this.$store.getters.currentUser.hash_code
      );
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
    GroupSignupPage() {
      return this.GroupSignupPageDefault + "/" + this.group.slug + "?parent=";
    },
    networkLink() {
      if (this.group.is_default) {
        return (
          this.GroupSignupPageDefault +
          "?parent=" +
          this.$store.getters.currentUser.hash_code
        );
      } else {
        return this.GroupSignupPage + this.$store.getters.currentUser.hash_code;
      }
    },
    isEnableMlm() {
      return this.$store.getters.group.is_enable_mlm;
    },
    affiliatesCustomlink() {
      return this.$store.getters.currentUser.affiliates_custom_link;
    },
    affiliatesCustomlinkFull() {
      return "https://" + this.shopDomain + this.affiliatesCustomlink;
    },
    lang() {
      let lang = this.$i18n.locale;
      if (lang) {
        if (lang == "zh") {
          moment.locale("zh-cn");
        } else {
          moment.locale(lang);
        }
      }
      return lang;
    },
  },
  components: {
    TableLoader,
    pagination,
    BOverlay,
    DateRange,
    LoadingSubmitButton,
  },
  mounted() {
    this.dateRange.startDate = moment(moment().subtract(14, "days").toDate());
    this.dateRange.endDate = moment(moment().toDate());
    this.loadCommissionStatistics();
    switch (this.lang) {
      case "zh":
        this.$refs.dateRange.dateRangText = "过去 14 天";
        break;
      case "en":
        this.$refs.dateRange.dateRangText = "Last 14 Days";
        break;
      case "de":
        this.$refs.dateRange.dateRangText = "Letzten 14 Tage";
        break;
      case "it":
        this.$refs.dateRange.dateRangText = "Ultimi 14 giorni";
        break;
      case "es":
        this.$refs.dateRange.dateRangText = "Últimos 14 días";
        break;
      case "fr":
        this.$refs.dateRange.dateRangText = "14 derniers jours";
        break;
      case "pt":
        this.$refs.dateRange.dateRangText = "Últimos 14 dias";
        break;
      default:
        this.$refs.dateRange.dateRangText = "Last 14 Days";
        break;
    }
    let a = {};
    a.startDate = moment().subtract(14, "days").toDate();
    a.endDate = moment().toDate();
    this.updateTimePicker(a);
    this.loadCoupons();
  },
  methods: {
    ...mapMutations(["updateAffiliatesCustomLink"]),
    loadCoupons(page = 1) {
      this.loadingCouponTable = true;
      let resource = "coupons";
      let params = {
        page: page,
      };
      ApiService.query(resource, { params: params })
        .then(({ data }) => {
          this.coupons = data.data;
        })
        .finally(() => {
          this.loadingCouponTable = false;
        });
    },
    loadType(type) {
      this.type = type;
      this.loadPerformance();
    },
    loadPerformance() {
      this.loadPerformanceOverplay = true;
      let params = {
        type: this.type,
      };
      if (this.startDate) {
        params["start_date"] = this.startDate;
      }
      if (this.endDate) {
        params["end_date"] = this.endDate;
      }
      let resource = `/dashboard/performance`;
      ApiService.query(resource, {
        params: params,
      })
        .then(({ data }) => {
          let dataSeries = [];
          let totalCount = 0;
          while (params["start_date"] <= params["end_date"] - 86399) {
            let dateString = moment
              .unix(params["start_date"])
              .format("YYYY/MM/DD");
            dataSeries.push({ x: dateString, y: 0 });
            params["start_date"] += 86400;
          }
          let lengthDraw = dataSeries.length;
          let lengthLine = data.data.line.length;
          for (let i = 0; i < lengthDraw; i++) {
            for (let j = 0; j < lengthLine; j++) {
              if (
                this.convertUTCToLocalTime(data.data.line[j].times) ==
                dataSeries[i].x
              ) {
                if (data.data.type == "clicks") {
                  totalCount += parseInt(data.data.line[j].clicks);
                  dataSeries[i].y = data.data.line[j].clicks;
                } else {
                  if (data.data.type == "orders") {
                    totalCount += parseInt(data.data.line[j].orders);
                    dataSeries[i].y = data.data.line[j].orders;
                  } else {
                    totalCount += parseFloat(data.data.line[j].sales);
                    dataSeries[i].y = parseFloat(data.data.line[j].sales);
                  }
                }
              }
            }
          }
          for (let i = 0; i < lengthDraw; i++) {
            dataSeries[i].x = this.convertToShortMonth(
              moment(dataSeries[i].x, "YYYY/MM/DD").format("MMMM DD")
            );
          }

          let startTime = moment.unix(this.startDate).toDate();
          let endTime = moment.unix(this.endDate).toDate();
          if (this.monthDiff(startTime, endTime) >= 11) {
            dataSeries = [];
            totalCount = 0;
            let yearTime = startTime.getFullYear();
            for (let i = 0; i <= 11; i++) {
              dataSeries.push({
                x: new Date(yearTime, i, 1).toDateString().slice(4, 7),
                y: 0,
              });
            }
            let lengthSeries = dataSeries.length;
            for (let i = 0; i < lengthSeries; i++) {
              for (let j = 0; j < lengthLine; j++) {
                let month = this.convertToMonth(
                  this.convertUTCToLocalTime(data.data.line[j].times)
                );
                if (month == dataSeries[i].x) {
                  if (data.data.type == "clicks") {
                    totalCount += parseInt(data.data.line[j].clicks);
                    dataSeries[i].y += parseInt(data.data.line[j].clicks);
                  } else {
                    if (data.data.type == "orders") {
                      totalCount += parseInt(data.data.line[j].orders);
                      dataSeries[i].y += parseInt(data.data.line[j].orders);
                    } else {
                      totalCount += parseFloat(data.data.line[j].sales);
                      dataSeries[i].y += parseFloat(data.data.line[j].sales);
                    }
                  }
                }
              }
            }
          }
          this.type = data.data.type;
          this.total = totalCount;
          this.series1 = [
            {
              name: data.data.type,
              data: dataSeries,
            },
          ];
        })
        .finally(() => {
          this.loadPerformanceOverplay = false;
        });
    },
    convertToMonth(timeString) {
      let month = moment(timeString, "YYYY/MM/DD").format("MMMM").slice(0, 3);
      return month;
    },
    monthDiff(d1, d2) {
      let months;
      months = (d2.getFullYear() - d1.getFullYear()) * 12;
      months -= d1.getMonth();
      months += d2.getMonth();
      return months <= 0 ? 0 : months;
    },
    convertUTCToLocalTime(time) {
      let stillUtc = moment.utc(time).toDate();
      let local = moment(stillUtc).local().format("YYYY/MM/DD");
      return local; // 2015-09-13 09:39:27
    },
    convertToShortMonth(timeString) {
      let month = timeString.slice(0, 3);
      let day = timeString.slice(-2);
      return day + " " + month;
    },
    updateTimePicker(value) {
      this.startDate = moment(
        this.formatTimeRangeDatePicker(value.startDate),
        "YYYY-MM-DD"
      ).unix();
      this.endDate =
        moment(
          this.formatTimeRangeDatePicker(value.endDate),
          "YYYY-MM-DD"
        ).unix() + 86399;
      this.loadStatistics({
        startDate: this.startDate,
        endDate: this.endDate,
      });
      this.loadPerformance();
    },
    cancelFilterDate() {
      let a = {};
      a.startDate = moment().subtract(14, "days").toDate();
      a.endDate = moment().toDate();
      this.updateTimePicker(a);
      this.dateRangText = this.$t("last_14_days");
    },
    updatedateRangText(e) {
      this.dateRangText = e;
    },
    loadStatistics(value) {
      this.loadingDasboard = true;
      let params = {
        end_date: value.endDate,
        start_date: value.startDate,
      };
      let resource = `conversions/get-statistics`;
      ApiService.query(resource, { params: params }).then(({ data }) => {
        this.statistics = data.data;
        this.loadingDasboard = false;
      });
    },

    copyText(ref, e) {
      let a = e.target.textContent;
      e.target.textContent = this.$t("copied");
      setTimeout(() => {
        e.target.textContent = a;
      }, 1500);
      this.selectText(this.$refs[ref][0]);
      document.execCommand("copy");
    },
    isValidHttpUrl(string) {
      let url;

      try {
        url = new URL(string);
      } catch (_) {
        return false;
      }

      return true;
    },
    generateLinkProduct(link) {
      this.linkCopyProductInput = "";
      if (!this.isValidHttpUrl(link)) {
        this.isLinkValid = false;
        return;
      }
      this.linkCopyProductInput =
        this.generateLinkAffiliate(link) +
        this.$store.getters.currentUser.group_id +
        ":" +
        this.$store.getters.currentUser.hash_code;
      this.isLinkValid = true;
    },
    saveCustomAffiliateLink() {
      this.submitLoading = true;
      if (!this.pathCustomAffiliateLink) {
        this.submitLoading = false;
        this.isPathValid = false;
        return;
      }
      this.isPathValid = true;
      let resource = `affiliates/set-custom-aff-link`;
      ApiService.post(resource, {
        path: this.pathCustomAffiliateLink,
        target: this.affLink,
      })
        .then(({ data }) => {
          if (data.data.status) {
            this.updateAffiliatesCustomLink({
              affiliates_custom_link: data.data.path,
            });
            this.$refs["affiliatesCustomLink"].hide();
            this.$toast.success("Redirect created", { position: "bottom" });
          } else {
            this.$toast.error(data.data.message, { position: "bottom" });
          }
        })
        .catch(({ response }) => {
          if (response.status == 422) {
            let arrError = Object.keys(response.data.errors);
            if (arrError.length) {
              this.$toast.error(response.data.errors[arrError[0]][0], {
                position: "bottom",
              });
            }
          }
        })
        .finally(() => {
          this.submitLoading = false;
        });
    },
    openModalEditAffiliatesCustomLinkAff() {
      this.pathCustomAffiliateLink = this.affiliatesCustomlink;
      this.pathCustomAffiliateLink = this.pathCustomAffiliateLink.slice(1);
      this.$refs["editAffiliatesCustomLinkAff"].show();
    },
    saveEditCustomAffiliateLink() {
      this.submitLoading = true;
      if (!this.pathCustomAffiliateLink) {
        this.submitLoading = false;
        this.isPathValid = false;
        return;
      }
      this.isPathValid = true;
      let resource = `affiliates/update-custom-aff-link`;
      ApiService.put(resource, {
        path: this.pathCustomAffiliateLink,
      })
        .then(({ data }) => {
          if (data.data.status) {
            this.updateAffiliatesCustomLink({
              affiliates_custom_link: data.data.path,
            });
            this.$refs["editAffiliatesCustomLinkAff"].hide();
            this.$toast.success("Updated", { position: "bottom" });
          } else {
            this.$toast.error(data.data.message, { position: "bottom" });
          }
        })
        .catch(({ response }) => {
          if (response.status == 422) {
            let arrError = Object.keys(response.data.errors);
            if (arrError.length) {
              this.$toast.error(response.data.errors[arrError[0]][0], {
                position: "bottom",
              });
            }
          }
        })
        .finally(() => {
          this.submitLoading = false;
        });
    },

    copyGenerateUrl(e) {
      var a = e.target.textContent;
      e.target.textContent = this.$t("copied");
      setTimeout(() => {
        e.target.textContent = a;
      }, 1500);
      var input = document.createElement("textarea");
      document.body.appendChild(input);
      input.value = this.linkCopyProductInput;

      input.select();
      input.focus();
      document.execCommand("Copy");
      input.remove();
    },

    getDateRageText() {
      if (this.dateRangText == "") {
        return this.$t("last_14_days");
      } else {
        return this.dateRangText;
      }
    },
    loadCommissionStatistics() {
      let resource = "conversions/commission-statistics";
      ApiService.query(resource).then(({ data }) => {
        let newData = [];
        let value = 0;
        for (const key in data.data) {
          if (key == "timestamp") {
            break;
          }
          newData.push(parseFloat(data.data[key]));
          value += parseFloat(data.data[key]);
        }
        this.series = newData;
        if (!value) {
          this.chartHaveData = false;
        } else {
          this.chartHaveData = true;
        }
      });
    },
  },
};
</script>
<style scoped>
.symbol-label-custom {
  border: solid 1px #3f4254;
}
.copy-share-link {
  opacity: 0;
  position: absolute;
  z-index: -9999;
  pointer-events: none;
}
.border-error {
  border-color: #f64e60 !important;
}
.symbol.symbol-bixgrow .symbol-label {
  background-color: #3f4254;
  color: #fbfbfb;
}
.coupon-wrapper {
  display: flex;
  margin-bottom: 10px;
  overflow: hidden;
  min-height: 60px;
  height: auto;
}
.coupon-left {
  background-color: #fecb0c ;
  position: relative;
  display: flex;
  align-items: center;
  border-top-left-radius: 10px;
  border-bottom-left-radius: 10px;
  border-right: dotted 3px #fff;
}
.tag-coupon{
    background-color: #000;
    color: #fecb0c;
    padding: 4px;
}
.coupon-left::before {
  content: "";
  position: absolute;
  top: -5px;
  display: block;
  right: -5px;
  width: 10px;
  height: 10px;
  border-radius: 50%;
  background-color: #fff;
  clip: rect(auto, auto, 285px, auto);
}
.coupon-left::after {
  content: "";
  position: absolute;
  bottom: -5px;
  display: block;
  right: -5px;
  width: 10px;
  height: 10px;
  border-radius: 50%;
  background-color: #fff;
  clip: rect(auto, auto, 285px, auto);
}
.coupon-con {
  background-color: #ededed;
  /* border: solid 1px #000; */
  position: relative;
  display: flex;
  align-items: center;
  border-top-right-radius: 10px;
  border-bottom-right-radius: 10px;
}
.coupon-con::before {
  content: "";
  position: absolute;
  top: -5px;
  display: block;
  left: -5px;
  width: 10px;
  height: 10px;
  border-radius: 50%;
  background-color: #fff;
  clip: rect(auto, auto, 285px, auto);
}
.coupon-con::after {
  content: "";
  position: absolute;
  bottom: -5px;
  display: block;
  left: -5px;
  width: 10px;
  height: 10px;
  border-radius: 50%;
  background-color: #fff;
  clip: rect(auto, auto, 285px, auto);
}
.label.label-dark-custom {
    background-color: #d5d3d3;
}
.border-dash{
    display: flex;
    align-items: center;
    margin-right: 10px;
    margin-bottom: 10px;
    padding: 10px;
    border: 1px dashed rgb(139, 139, 139) !important;
    border-radius: 5px;
}
</style>
