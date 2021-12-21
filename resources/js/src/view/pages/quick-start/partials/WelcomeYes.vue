<template>
  <div class="col-lg-10 mx-auto">
    <div class="card card-custom mt-10" >
      <div class="card-body d-flex flex-column align-items-center">
        <div
          class="pb-5 step-content"
          data-wizard-type="step-content"
          data-wizard-state="current"
        >
          <h1
            class="mb-5 font-weight-bolder font-size-h1 text-center text-dark"
          >
            Welcome to BixGrow
          </h1>
          <h3 class="font-weight-bold text-center font-size-h5 mb-10">
            We're delighted to have you join us today! <br />
            Now, we'll walk you through the basic setup of our app with
            4 main steps listed below:
          </h3>
          <div class="d-flex align-items-center justify-content-center ">
            <img src="media/getstarted/Welcome.jpg" alt="" class="imgWz md-screen"/>
          </div>
        </div>
      </div>
      <div class="card-footer px-30 d-flex justify-content-between">
        <a href="#/quick-start" class="btn btn-light-primary font-weight-bold text-uppercase">Back</a>
        <a href="#/quick-start/first-setup" class="btn btn-primary font-weight-bold text-uppercase">Next Step</a>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      picked: "",
    };
  },
};
</script>
<style scoped>
.imgWz{
    max-width: 600px;
    height: auto;
    object-fit: cover;
}
</style>
