<template>
    <div class="d-flex flex-column flex-root">
			<div class="d-flex flex-column flex-column-fluid bgi-position-y-bottom position-x-center bgi-no-repeat bgi-size-contain bgi-attachment-fixed"
            style="height: 100vh">
				<div class="d-flex flex-column flex-column-fluid text-center" style="margin-top: -250px">
					<a href="#" class="mb-10 pt-lg-20 d-flex flex-column flex-column-fluid text-center justify-content-center">
                        <div class="spinner-border mx-auto" style="width: 3rem; height: 3rem;" role="status">
                        <span class="sr-only">Loading...</span>
                        </div>
					</a>
				</div>
			</div>
		</div>
</template>
<style scoped>
*{
    background:#ffffff;
}
.flex-root {
    flex: 1;
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
