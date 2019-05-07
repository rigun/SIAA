

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
import SparepartCabang from './components/dashboard/sparepartCabang.vue'
import Supplier from './components/dashboard/supplierMain.vue'
import SparepartSupplier from './components/dashboard/sparepartSupplier.vue'
import Pengadaan from './components/dashboard/pengadaanComponent.vue'
import SupplierCabang from './components/dashboard/supplierCabangPengadaan.vue'
import SparepartSupplierCabang from './components/dashboard/sparepartSupplierPengadaan.vue'
import KonfirmasiPengadaan from './components/dashboard/konfirmasiPengadaan.vue'
// Transaksi
import Transaksi from './components/csPart/transaction.vue'
import TransaksiDetail from './components/csPart/transactionDetail.vue'
import TransaksiDetailItem from './components/csPart/transactionDetailItem.vue'
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
                name: 'Transaksi',
                path: 'transaksi',
                component: Transaksi
            },
            {
                name: 'TransaksiDetail',
                path: 'transaksi/:TransaksiType-:nomor_transaksi-:idTransaksi',
                component: TransaksiDetail
            },
            {
                name: 'TransaksiDetailItem',
                path: 'transaksi/:TransaksiType-:nomor_transaksi-:idTransaksi/:idDetail',
                component: TransaksiDetailItem
            },
            {
                name: 'Pengadaan',
                path: 'cabang/pengadaan/:idCabang',
                component: Pengadaan
            },
            {
                name: 'KonfirmasiPengadaan',
                path: 'cabang/konfirmasi/:idPemesanan',
                component: KonfirmasiPengadaan
            },
            {
                name: 'SupplierCabang',
                path: 'cabang/supplier/:idCabang',
                component: SupplierCabang
            },
            {
                name: 'SparepartSupplierCabang',
                path: 'cabang/supplier/:idCabang/sparepart/:idSupplier',
                component: SparepartSupplierCabang
            },
            {
                name: 'Pegawai',
                path: 'cabang/pegawai/:idCabang',
                component: Pegawai
            },
            {
                name: 'SparepartCabang',
                path: 'cabang/sparepart/:idCabang',
                component: SparepartCabang
            },
            {
                name: 'SparepartSupplier',
                path: 'supplier/sparepart/:idSupplier',
                component: SparepartSupplier
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