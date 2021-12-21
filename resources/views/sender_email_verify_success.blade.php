<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>

   <title>Affiliate BixGrow</title>

</head>
<body>
   <div class="main">
       <div class="container">
        <div class="alert">
            <div class="alert-icon">
                <span  class="svg-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="48px" height="48px" viewBox="0 0 24 24" version="1.1"><g data-v-6ff413d5="" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect data-v-6ff413d5="" x="0" y="0" width="24" height="24"></rect> <path data-v-6ff413d5="" d="M7.07744993,12.3040451 C7.72444571,13.0716094 8.54044565,13.6920474 9.46808594,14.1079953 L5,23 L4.5,18 L7.07744993,12.3040451 Z M14.5865511,14.2597864 C15.5319561,13.9019016 16.375416,13.3366121 17.0614026,12.6194459 L19.5,18 L19,23 L14.5865511,14.2597864 Z M12,3.55271368e-14 C12.8284271,3.53749572e-14 13.5,0.671572875 13.5,1.5 L13.5,4 L10.5,4 L10.5,1.5 C10.5,0.671572875 11.1715729,3.56793164e-14 12,3.55271368e-14 Z" fill="#000000" opacity="0.3"></path> <path data-v-6ff413d5="" d="M12,10 C13.1045695,10 14,9.1045695 14,8 C14,6.8954305 13.1045695,6 12,6 C10.8954305,6 10,6.8954305 10,8 C10,9.1045695 10.8954305,10 12,10 Z M12,13 C9.23857625,13 7,10.7614237 7,8 C7,5.23857625 9.23857625,3 12,3 C14.7614237,3 17,5.23857625 17,8 C17,10.7614237 14.7614237,13 12,13 Z" fill="#3699ff" fill-rule="nonzero"></path></g></svg></span></div>
            <div class="alert-text">
            <h3>Email verification successful</h3>
            </div>
        </div>
       </div>

   </div>
</body>
</html>
<style>
.main{
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    margin-top: 3.6rem;
    height: auto;
    height: 100%;
    padding-bottom: 5rem;
}
.container{
    margin: 2rem 3rem 0 3rem;
    box-shadow: 0 0 0 1px rgb(63 63 68 / 5%), 0 1px 3px 0 rgb(63 63 68 / 15%);
    border-radius: 10px;
    display: flex;
    gap: 2rem;
    justify-content: space-evenly;
    background-color: #C9F7F5 !important;
}
.alert{
    border-color: transparent;
    display: flex;
    align-items: center;
    padding: 1.5rem 2rem;
    border: 1px solid transparent;
    border-radius: 0.42rem;
    position: relative;
    transition: opacity 0.15s linear;
}
.alert-text{
    color: #3699ff;
    align-self: center;
    flex-grow: 1;
    font-weight: 600 !important;
}
.alert-icon{
    display: flex;
    align-items: center;
    padding: 0 1.25rem 0.5rem 0;
}
.svg-icon{
    height: 2rem !important;
    width: 2rem !important;
}
</style>
