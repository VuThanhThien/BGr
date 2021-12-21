import Vue from "vue";
import Router from "vue-router";

Vue.use(Router);

export default new Router({
    routes: [
        {
            path: "/",
            redirect: "/dashboard",
            component: () => import("@/view/layout/Layout"),
            children: [
                {
                    path: "/dashboard",
                    name: "dashboard",
                    component: () => import(/* webpackPrefetch: true */"@/view/pages/dashboard/Main.vue")
                },
                {
                    path: "/sales",
                    name: "sales",
                    component: () =>
                        import(
                            /* webpackPrefetch: true */ "@/view/pages/sale/Main.vue"
                        )
                },
                {
                    path: "/payouts",
                    name: "payouts",
                    component: () =>
                        import(
                            /* webpackPrefetch: true */ "@/view/pages/payout/Main.vue"
                        )
                },
                {
                    path: "/emails",
                    name: "emails",
                    component: () => import(/* webpackPrefetch: true */"@/view/pages/emails/Main.vue"),
                    children: [

                      {
                        path: "bulk-email",
                        name: "bulkemail",
                        component: () => import(/* webpackPrefetch: true */"@/view/pages/emails/BulkEmail.vue")
                      },
                      {
                          path:"mail-outbox",
                          name:"mailoutbox",
                          component:() => import(/* webpackPrefetch: true */"@/view/pages/emails/MailOutbox.vue")
                      }
                    ]
                  },
                  {
                    path: "/email-templates",
                    redirect: "/email-templates/affiliate_review",
                    name: "templates",
                    component: () =>
                        import(
                            /* webpackPrefetch: true */ "@/view/pages/emails/templates/Main.vue"
                        ),
                    children: [
                        {
                            path: ":slug",
                            name: "templates.edit",
                            component: () =>
                                import(
                                    /* webpackPrefetch: true */ "@/view/pages/emails/templates/ViewEmail.vue"
                                )
                        }
                    ]
                },
                {
                    path: "/coupons",
                    name: "coupons",
                    component: () =>
                        import(
                            /* webpackPrefetch: true */ "@/view/pages/coupon/List.vue"
                        )
                },
                {
                    path: "/programs",
                    name: "programs",
                    redirect: "/groups.list",
                    component: () => import(/* webpackPrefetch: true */ "@/view/pages/group/Main.vue"),
                    children: [
                        {
                            path: "/",
                            name: "groups.list",
                            component: () =>
                                import(/* webpackPrefetch: true */"@/view/pages/group/List.vue")
                        },
                        {
                            path: ":id/edit",
                            name: "groups.edit",
                            props: true,
                            component: () =>
                                import(
                                    /* webpackPrefetch: true */ "@/view/pages/group/Edit.vue"
                                )
                        }
                    ]
                },
                {
                    path: "/multi-level",
                    name: "multi-level",
                    component: () => import(/* webpackPrefetch: true */ "@/view/pages/multi-level/Main.vue"),
                },
                {
                    path: "/affiliates",
                    name: "affiliates",
                    redirect: "/affiliates.list",
                    component: () =>
                        import(
                            /* webpackPrefetch: true */ "@/view/pages/affiliate/Main.vue"
                        ),
                    children: [
                        {
                            path: "/",
                            name: "affiliates.list",
                            component: () =>
                                import(
                                    /* webpackPrefetch: true */ "@/view/pages/affiliate/List.vue"
                                )
                        },
                        {
                            path: ":id/edit",
                            name: "affiliates.edit",
                            redirect: "/affiliates/:id/edit/overview",
                            component: () =>
                                import(
                                    /* webpackPrefetch: true */ "@/view/pages/affiliate/Edit.vue"
                                ),
                            children: [
                                {
                                    path: "overview",
                                    name: "affiliates.overview",
                                    component: () =>
                                        import(
                                            /* webpackPrefetch: true */ "@/view/pages/affiliate/partials/Overview.vue"
                                        )
                                },
                                {
                                    path: "sales",
                                    name: "affiliates.sales",
                                    component: () =>
                                        import(
                                            /* webpackPrefetch: true */ "@/view/pages/affiliate/partials/Sale.vue"
                                        )
                                },
                                {
                                    path: "payments",
                                    name: "affiliates.payments",
                                    component: () =>
                                        import(
                                            /* webpackPrefetch: true */ "@/view/pages/affiliate/partials/Payment.vue"
                                        )
                                },
                                {
                                    path: "network",
                                    name: "affiliates.network",
                                    component: () =>
                                        import(
                                            /* webpackPrefetch: true */"@/view/pages/affiliate/partials/Network.vue"
                                        )
                                },
                                {
                                    path: "settings",
                                    name: "affiliates.settings",
                                    component: () =>
                                        import(
                                            /* webpackPrefetch: true */"@/view/pages/affiliate/partials/Setting.vue"
                                        )
                                },
                                {
                                    path: "coupons",
                                    name: "affiliates.coupons",
                                    component: () =>
                                        import(
                                            /* webpackPrefetch: true */"@/view/pages/affiliate/partials/Coupon.vue"
                                        )
                                }
                            ]
                        }
                    ]
                },
                // setting
                {
                    path: "/settings",
                    name: "settings",
                    redirect: "settings/general",
                    component: () => import(/* webpackPrefetch: true */"@/view/pages/setting/Main.vue"),
                    children: [
                        {
                            path: "general",
                            name: "settings.general",
                            component: () =>
                                import(/* webpackPrefetch: true */"@/view/pages/setting/General.vue")
                        },
                        {
                            path: "payment",
                            name: "settings.payment",
                            component: () =>
                                import(/* webpackPrefetch: true */"@/view/pages/setting/Payment.vue")
                        },
                        {
                            path: "notification",
                            name: "settings.notification",
                            component: () =>
                                import(/* webpackPrefetch: true */"@/view/pages/setting/Notication.vue")
                        },
                        {
                            path: "embedded-portal",
                            name: "settings.embedded-portal",
                            component: () =>
                                import(/* webpackPrefetch: true */"@/view/pages/setting/Embedded.vue")
                        },
                        {
                            path: "coupons",
                            name: "settings.coupons",
                            component: () =>
                                import(/* webpackPrefetch: true */"@/view/pages/setting/Coupon.vue")
                        },
                        {
                            path:"advanced",
                            name:"settings.advanced",
                            component:()=>
                                import(/* webpackPrefetch: true */"@/view/pages/setting/Advanced.vue")
                        },
                        {
                            path:"term_condition",
                            name:"settings.term_condition",
                            component:()=>
                                import(/* webpackPrefetch: true */"@/view/pages/setting/TermsAndConditions.vue")
                        },
                        {
                            path: "account-language",
                            name: "settings.account_language",
                            component:()=>
                                import(/* webpackPrefetch: true */"@/view/pages/setting/AccountLanguage.vue")
                        },
                        {
                            path: "integration",
                            name: "settings.integration",
                            component:()=>
                                import(/* webpackPrefetch: true */"@/view/pages/setting/Integration.vue")
                        }
                    ]
                },
                // creatives
                {
                    path: "/creatives",
                    name: "creatives",
                    redirect: to => ({
                        name: "creatives.view",
                        params: { name: "all", id: 0 }
                    }),
                    component: () =>
                        import(/* webpackPrefetch: true */"@/view/pages/creative/Creative.vue"),
                    children: [
                        {
                            path: ":name/:id",
                            name: "creatives.view",
                            component: () =>
                                import(
                                    /* webpackPrefetch: true */"@/view/pages/creative/partials/ListFile.vue"
                                )
                        }
                    ]
                },
                // checkout-popup
                {
                    path: "/checkout-popup",
                    name: "checkout-popup",
                    component: () =>
                        import(/* webpackPrefetch: true */"@/view/pages/checkout-popup/Main.vue"),
                },
                // affiliate-tier
                {
                    path: "/affiliate-tier",
                    name: "affiliate-tier",
                    component: () =>
                        import(/* webpackPrefetch: true */"@/view/pages/affiliate-tier/Main.vue"),
                },
                // translations
                {
                    path :"/translations",
                    name: "translations",
                    component: ()=>
                        import(/* webpackPrefetch: true */"@/view/pages/translation/Translation.vue")
                },
                // tod0list
                {
                    path: "/todo-list",
                    name: "todo-list",
                    component: () =>
                        import(/* webpackPrefetch: true */"@/view/pages/todo-list/Main.vue"),
                },
                //registration
                {
                    path: "/customize/affiliate-registration",
                    name: "registration",
                    redirect: to => ({
                        name: "registration.edit",
                        params: {  id: 0 }
                    }),
                    component: () => import(/* webpackPrefetch: true */ "@/view/pages/registration/Main.vue"),
                    children: [
                        {
                            path: ":id",
                            name: "registration.edit",
                            props: true,
                            component: () =>
                                import(
                                /* webpackPrefetch: true */"@/view/pages/registration/partials/TemplateTab.vue"
                                )
                        },
                    ]
                },
                {
                    path: "/customize/affiliate-login",
                    name: "registration",
                    component: () => import(/* webpackPrefetch: true */ "@/view/pages/affiliate-login/Main.vue")
                },
            ]
        },
        // {
        //     path: "/",
        //     component: () => import("@/view/pages/auth/login_pages/Login-1"),
        //     children: [
        //         {
        //             name: "login",
        //             path: "/login",
        //             component: () =>
        //                 import("@/view/pages/auth/login_pages/Login-1")
        //         },
        //         {
        //             name: "register",
        //             path: "/register",
        //             component: () =>
        //                 import("@/view/pages/auth/login_pages/Login-1")
        //         }
        //     ]
        // },
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
            path: "/welcome",
            name: "welcome",
            component: () => import("@/view/pages/welcome/Welcome.vue")
        },
        {
            path: "/quick-start",
            name: "quick-start",
            component: () => import("@/view/pages/quick-start/Main.vue"),
            redirect: "quickQuestion",
            children: [
                /**Router quickquestion, mặc định vào đây */
                {
                    path: "/",
                    name: "quickQuestion",
                    component: () =>
                        import(
                            "@/view/pages/quick-start/partials/QuickQuestion.vue"
                        )
                },
                /**Khi vừa cài app thì vào router này */
                {
                    path: "/install-success",
                    name: "install-success",
                    component: () =>
                        import(
                            "@/view/pages/quick-start/partials/JustInstall.vue"
                        )
                },
                {
                    path: "/quick-start/NO",
                    name: "quickQuestion.NO",
                    component: () =>
                        import("@/view/pages/quick-start/partials/WizardNo.vue")
                },
                {
                    path: "/quick-start/YES",
                    name: "quickQuestion.YES",
                    component: () =>
                        import(
                            "@/view/pages/quick-start/partials/WelcomeYes.vue"
                        )
                },
                {
                    path: "/quick-start/first-setup",
                    name: "wizard.YES",
                    component: () =>
                        import("@/view/pages/quick-start/partials/WizardYes.vue")
                }
            ]
        },
        {
            path: "/registration/:id/detail",
            name: "registration.customize",
            props: true,
            component: () => import("@/view/pages/registration/Edit.vue"),
        },
    ]
});
