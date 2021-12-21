<template>
  <button
    :class="{
      'btn btn-primary btn-sm': true,
      'spinner spinner-white spinner-right':loading,
    }"
    :disabled="loading"
    type="button"
    @click="handleClick"
  >
    <span v-if="!loading" class="svg-icon svg-icon-md svg-icon-white">
        <inline-svg src="media/svg/icons/General/Save.svg" />
    </span>
    <slot>Submit</slot>
  </button>
</template>

<script>
export default {
    name: "LoadingSubmitButton",
    props: {
        loading: {
            type: Boolean,
            required: false,
            default: false
        }
    },
    methods:{
      handleClick() {
        this.$emit('click')
      }

    }

}
</script>

<style>

</style>
