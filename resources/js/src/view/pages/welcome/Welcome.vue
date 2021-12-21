<template>
    <div class="d-flex flex-column flex-root">
			<!--begin::Authentication - Signup Welcome Message -->
			<div class="d-flex flex-column flex-column-fluid bgi-position-y-bottom position-x-center bgi-no-repeat bgi-size-contain bgi-attachment-fixed" style="background-image: url(/media/bg/progress-hd.png)">
				<!--begin::Content-->
				<div class="d-flex flex-column flex-column-fluid text-center p-10 py-lg-20">
					<!--begin::Logo-->
					<a href="#" class="mb-10 pt-lg-20">
						<img alt="Logo" src="/media/logos/bixgrow-black.png" class="h-50px mb-5" />
					</a>
					<!--end::Logo-->
					<!--begin::Wrapper-->
					<div class="pt-lg-10">
						<!--begin::Logo-->
						<h1 class="fw-bolder fs-2qx text-dark mb-7">Welcome to BixGrow Affiliate</h1>
						<!--end::Logo-->
						<!--begin::Message-->
						<div class="fw-bold fs-3 text-gray-400 mb-15">Create your own affiliate programs to reach more potential customer
						<br />and grow your sales.</div>
                         <div class="alert alert-custom alert-light-warning" role="alert">
                        <div class="alert-icon">
                          <span class="svg-icon svg-icon-3x svg-icon-primary px-2">
                              <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                  <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                      <rect x="0" y="0" width="24" height="24"></rect>
                                      <path d="M7.07744993,12.3040451 C7.72444571,13.0716094 8.54044565,13.6920474 9.46808594,14.1079953 L5,23 L4.5,18 L7.07744993,12.3040451 Z M14.5865511,14.2597864 C15.5319561,13.9019016 16.375416,13.3366121 17.0614026,12.6194459 L19.5,18 L19,23 L14.5865511,14.2597864 Z M12,3.55271368e-14 C12.8284271,3.53749572e-14 13.5,0.671572875 13.5,1.5 L13.5,4 L10.5,4 L10.5,1.5 C10.5,0.671572875 11.1715729,3.56793164e-14 12,3.55271368e-14 Z" fill="#000000" opacity="0.3"></path>
                                      <path d="M12,10 C13.1045695,10 14,9.1045695 14,8 C14,6.8954305 13.1045695,6 12,6 C10.8954305,6 10,6.8954305 10,8 C10,9.1045695 10.8954305,10 12,10 Z M12,13 C9.23857625,13 7,10.7614237 7,8 C7,5.23857625 9.23857625,3 12,3 C14.7614237,3 17,5.23857625 17,8 C17,10.7614237 14.7614237,13 12,13 Z" fill="#000000" fill-rule="nonzero"></path>
                                  </g>
                              </svg>
                          </span>
                        </div>
                        <div class="alert-text">
                           Your current session has been expired, please re-login the app from the Shopify admin to continue
                        </div>
                      </div>
						<!--end::Message-->
						<!--begin::Action-->
						<!-- <div class="text-center">
							<router-link to="/" class="btn btn-lg btn-primary fw-bolder">Sign in to shopify</router-link>
						</div> -->
						<!--end::Action-->
					</div>
					<!--end::Wrapper-->
				</div>
				<!--end::Content-->
				<!--begin::Footer-->
				<div class="d-flex flex-center flex-column-auto p-10">
					
				</div>
				<!--end::Footer-->
			</div>
			<!--end::Authentication - Signup Welcome Message-->
		</div>
</template>
<style scoped>
.flex-root {
    flex: 1;
}
.flex-column {
    flex-direction: column!important;
}
.d-flex {
    display: flex!important;
}
*, ::after, ::before {
    box-sizing: border-box;
}

.flex-column-fluid {
    flex: 1 0 auto;
}
.bgi-attachment-fixed {
    background-attachment: fixed;
}
.bgi-size-contain {
    background-size: contain;
}
.bgi-position-y-bottom {
    background-position-y: bottom;
}
.bgi-no-repeat {
    background-repeat: no-repeat;
}
.flex-column {
    flex-direction: column!important;
}
.d-flex {
    display: flex!important;
}
.flex-column-fluid {
    flex: 1 0 auto;
}
@media (min-width: 992px)
{.py-lg-20 {
    padding-top: 5rem!important;
    padding-bottom: 5rem!important;
}
.text-center {
    text-align: center!important;
}
.p-10 {
    padding: 2.5rem!important;
}
.flex-column {
    flex-direction: column!important;
}
.d-flex {
    display: flex!important;
}
.mb-10 {
    margin-bottom: 2.5rem!important;
}
a {
    transition: color .2s ease,background-color .2s ease;
}
a {
    color: #009ef7;
    text-decoration: none;
}
.text-dark {
    color: #181c32!important;
}
@media (min-width: 1200px)
{
    .fs-2qx {
        font-size: 2.25rem!important;
}
}
.fw-bolder {
    font-weight: 600!important;
}
.mb-7 {
    margin-bottom: 1.75rem!important;
}
.text-gray-400 {
    color: #b5b5c3!important;
}
@media (min-width: 1200px)
{
    .fs-3 {
    font-size: 1.35rem!important;
}
}
.mb-15 {
    margin-bottom: 3.75rem!important;
}
.text-center {
    text-align: center!important;
}
div {
    display: block;
}
.flex-center {
    justify-content: center;
    align-items: center;
}
.flex-column-auto {
    flex: none;
}
.d-flex {
    display: flex!important;
}
.fs-6 {
    font-size: 1.075rem!important;
}
.align-items-center {
    align-items: center!important;
}
.text-hover-primary {
    transition: color .2s ease,background-color .2s ease;
}
.px-2 {
    padding-right: .5rem!important;
    padding-left: .5rem!important;
}
.text-muted {
    color: #a1a5b7!important;
}
}
</style>
