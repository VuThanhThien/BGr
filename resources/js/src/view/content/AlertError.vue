<template>
  <div
    v-if="this.errorMsg"
    class="d-flex align-items-center bg-light-danger rounded p-5 mb-5"
  >
    <span class="svg-icon svg-icon-danger mr-5">
      <span class="svg-icon svg-icon-lg"
        ><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\legacy\metronic\theme\html\demo1\dist/../src/media/svg/icons\Code\Warning-1-circle.svg--><svg
          xmlns="http://www.w3.org/2000/svg"
          xmlns:xlink="http://www.w3.org/1999/xlink"
          width="24px"
          height="24px"
          viewBox="0 0 24 24"
          version="1.1"
        >
          <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
            <rect x="0" y="0" width="24" height="24" />
            <circle fill="#000000" opacity="0.3" cx="12" cy="12" r="10" />
            <rect fill="#000000" x="11" y="7" width="2" height="8" rx="1" />
            <rect fill="#000000" x="11" y="16" width="2" height="2" rx="1" />
          </g></svg
        ><!--end::Svg Icon--></span
      >
    </span>
    <div class="d-flex flex-column flex-grow-1 mr-2">
      <ul href="#" class="text-danger font-weight-bold mt-2" v-html="errorMsg">
      </ul>
    </div>
  </div>
</template>
<script>
export default {
  props: ["errorMsg"],
};
</script>