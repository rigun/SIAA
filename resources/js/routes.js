

import Vue from 'vue'
import VueRouter from 'vue-router'
import store from './store'

Vue.use(VueRouter)

import DashboardLayout from './components/Layout/dashboardLayout.vue';
import LoginLayout from './components/Layout/loginLayout.vue';
import Logout from './components/logoutLayout.vue';
import ChangePassword from './components/dashboard/changePassword.vue';
// dashboard
import MainView from './components/dashboard/mainView.vue'
import CabangMain from './components/dashboard/cabangMain.vue'
import Pegawai from './components/dashboard/pegawaiCabang.vue'
import Sparepart from './components/dashboard/sparepartCabang.vue'
import Supplier from './components/dashboard/supplierMain.vue'

const routes = [
    {
        name: 'LoginLayout',
        path: '/login',
        component: LoginLayout,
    },
    {
        name: 'Logout',
        path: '/logout',
        component: Logout,
    },
    {
        path: '/',
        component: DashboardLayout,
        meta: { requiresAuth: true },
        children: [
            {
                name: 'DashboardContent',
                path: '',
                component: MainView
            },
            {
                name: 'Cabang',
                path: 'cabang',
                component: CabangMain
            },
            {
                name: 'Pegawai',
                path: 'cabang/pegawai/:idCabang',
                component: Pegawai
            },
            {
                name: 'Sparepart',
                path: 'cabang/sparepart/:idCabang',
                component: Sparepart
            },
            {
                name: 'Supplier',
                path: 'supplier',
                component: Supplier
            },
            {
                name: 'changePassword',
                path: 'changepassword',
                component: ChangePassword
            }
        ]
    },
];
const router = new VueRouter({mode: 'history', routes: routes});
router.beforeEach((to, from, next) => {

    // check if the route requires authentication and user is not logged in
    if (to.matched.some(route => route.meta.requiresAuth) && !store.state.isLoggedIn) {
        // redirect to login page
        next({ name: 'LoginLayout' })
        return
    }

    // if logged in redirect to dashboard
    if(to.name === 'LoginLayout' && store.state.isLoggedIn) {
        next({ name: 'DashboardContent' })
        return
    }
    // if logged in redirect to dashboard
    if(to.name === 'DashboardContent' && !store.state.isLoggedIn) {
        next({ name: 'LoginLayout' })
        return
    }
    next()
})
export default router