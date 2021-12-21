import Vue from "vue";
import Router from "vue-router";
Vue.use(Router);

export default new Router({
  routes: [
    {
      path: "/",
      redirect: "/dashboard",
      component: () => import("aff/view/layout/Layout"),
      children: [
        {
          path: "/dashboard",
          name: "dashboard",
          component: () => import("aff/view/pages/Dashboard.vue")
        },
        {
          path: "/settings",
          name: "settings",
          component: () => import(
            /* webpackPrefetch: true */ "aff/view/pages/setting/Main.vue")
        },
        {
          path: "/commissions",
          name: "commissions",
          component: () => import(
            /* webpackPrefetch: true */ "aff/view/pages/commission/Main.vue")
        },
        {
          path:"/payouts",
          name:"payouts",
          component:() => import(
            /* webpackPrefetch: true */ "aff/view/pages/payout/Main.vue"),
          children:[
            {
              path:"payouts",
              name:"payouts",
              component:() => import(
                /* webpackPrefetch: true */ "aff/view/pages/payout/partials/PaymentHistory.vue"),
            },
            {
              path:"store-credit",
              name:"store-credit",
              component:() => import(
                /* webpackPrefetch: true */ "aff/view/pages/payout/partials/StoreCredit.vue"),
            }
          ]
        },

        {
          path:"/account-not-active",
          name:"account-not-active",
          component:() => import(
            /* webpackPrefetch: true */ "aff/view/pages/error/AccountNotActive.vue")
        }
        // {
        //   path: "/commissions",
        //   name: "commissions",
        //   component: () => import("~/view/pages/commission/Main.vue")
        // },
        // {
        //   path: "/payouts",
        //   name: "payouts",
        //   component: () => import("~/view/pages/payout/Main.vue")
        // },

        // {
        //   path: "/settings",
        //   name: "settings",
        //   component: () => import("~/view/pages/setting/Main.vue"),
        //   children: [
        //     {
        //       path: "general",
        //       name: "settings.general",
        //       component: () => import("~/view/pages/setting/General.vue")
        //     },
        //     {
        //       path: "payment",
        //       name: "settings.payment",
        //       component: () => import("~/view/pages/setting/Payment.vue")
        //     }
        //   ]
        // },
        ,
        {
          path:"/creatives",
          name:"creatives",
          redirect: to => ({
            name: "creatives.view",
            params:{name:'all',id:0}
          }),
          component:()=>import(
            /* webpackPrefetch: true */ "aff/view/pages/creative/Creative.vue"),
          children:[
              {
                path:':name/:id',
                name:'creatives.view',
                component:()=>import(
                  /* webpackPrefetch: true */ "aff/view/pages/creative/partials/ListFile.vue")
              }

          ]
        },
        {
          path:'/network',
          name:'network',
          component:()=>import(
            /* webpackPrefetch: true */ "aff/view/pages/network/Network.vue")

        }





      ]
    },
    {
      path: "/register",
      name: "register",
      component: () => import("aff/view/pages/auth/Register"),
      children: [
        {
          path: ":slug",
          name: "register.group",
          component: () => import("aff/view/pages/auth/Register")
        }
      ]

    },
    {
      path: "/login",
      name:"login",
      component: () => import("aff/view/pages/auth/Login"),

    },

    {
      path: "*",
      redirect: "/404"
    },
    {
      // the 404 route, when none of the above matches
      path: "/404",
      name: "404",
      component: () => import("@/view/pages/error/Error-1.vue")
    },
    {
        path:'/welcome',
        name:'welcome',
        component:()=>import("@/view/pages/welcome/Welcome.vue")
    },{
      path:'/password/reset',
      name: 'forgot-password',
      component:()=>import('aff/view/pages/auth/ForgotPassword')
    },
    {
      path:'/reset-password/:token',
      name:'reset-password',
      component:()=> import('aff/view/pages/auth/ResetPassword')
    }
  ]
});
