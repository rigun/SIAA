<template>
  <v-layout
    wrap
  >
  <div class="background-all"></div>
      <vue-topprogress ref="topProgress"></vue-topprogress>
    <div v-if="loading == true"></div>
    <div  v-else style="width: 100%;">
    <v-toolbar :class="{'sideActive': mini, 'sideDeactive':!mini, 'sideToggle':!drawer}" class="toolbarFixed">
        <v-toolbar-side-icon  @click.stop="showSide()"></v-toolbar-side-icon>
        <v-toolbar-title>Atma Auto</v-toolbar-title>
    </v-toolbar>
    <v-navigation-drawer
      v-model="drawer"
      :mini-variant="mini"
      absolute
      :permanent="drawer"
      dark
      class="sideBarFixed"
    >
      <v-list class="pa-1">

        <v-list-tile avatar tag="div">
          <v-list-tile-avatar>
            <v-icon>account_circle</v-icon>
          </v-list-tile-avatar>

          <v-list-tile-content>
            <v-list-tile-title>{{name}}</v-list-tile-title>
          </v-list-tile-content>

        </v-list-tile>
      </v-list>

      <v-list class="pt-0" dense>
        <v-divider light></v-divider>
         <v-list-tile @click="routerGoto('DashboardContent')"><v-list-tile-action><v-icon>dashboard</v-icon></v-list-tile-action><v-list-tile-content><v-list-tile-title>Beranda</v-list-tile-title></v-list-tile-content></v-list-tile>
         <v-list-tile @click="routerGoto('Cabang')" v-if="roles == 'owner'"><v-list-tile-action><v-icon>dashboard</v-icon></v-list-tile-action><v-list-tile-content><v-list-tile-title>Cabang</v-list-tile-title></v-list-tile-content></v-list-tile>
         <v-list-tile @click="routerGoto('Supplier')" v-if="roles == 'owner'"><v-list-tile-action><v-icon>lock</v-icon></v-list-tile-action><v-list-tile-content><v-list-tile-title>Supplier</v-list-tile-title></v-list-tile-content></v-list-tile>
         <v-list-tile @click="routerGoto('Transaksi')" v-if="roles == 'cs'"><v-list-tile-action><v-icon>lock</v-icon></v-list-tile-action><v-list-tile-content><v-list-tile-title>Transaksi</v-list-tile-title></v-list-tile-content></v-list-tile>
         <v-list-tile @click="routerGoto('changePassword')"><v-list-tile-action><v-icon>lock</v-icon></v-list-tile-action><v-list-tile-content><v-list-tile-title>Ubah Password</v-list-tile-title></v-list-tile-content></v-list-tile>
         <v-list-tile @click="logoutDialog = true"><v-list-tile-action><v-icon>power_settings_new</v-icon></v-list-tile-action><v-list-tile-content><v-list-tile-title>Keluar</v-list-tile-title></v-list-tile-content></v-list-tile>
      </v-list>
    </v-navigation-drawer>
     <!-- delete -->
                  <v-dialog v-model="logoutDialog" persistent max-width="290">
                    <v-card>
                      <v-card-title class="headline">Apakah Anda ingin keluar ?</v-card-title>
                      <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="green darken-1" flat @click="logoutDialog = false;">Batal</v-btn>
                        <v-btn color="red darken-1" flat @click="routerGoto('Logout')">Keluar</v-btn>
                      </v-card-actions>
                    </v-card>
                  </v-dialog>
        <!-- delete -->
        <div id="appDashboard">
            <main class="dashboardContent" :class="{'sideActive': mini, 'sideDeactive':!mini,'sideToggle':!drawer}" >
            <transition name="fade">
            <router-view></router-view>
            </transition>
            </main>
        </div>
    </div>
  </v-layout>
</template>
<script>
import { vueTopprogress } from 'vue-top-progress'

  export default {
    components: {
      vueTopprogress
    },
    mounted () {
        this.cekSize()
        this.getUser()
    },
    data () {
      return {
        user: {},
        loading: true,
        roles: localStorage.getItem('role'),
        name: localStorage.getItem('name'),
        activeFirst: false,
        wrongPassword: false,
        leftNav: false,
        showDropDown: false,
        logoutDialog: false,
        drawer: true,
        mini: false,
        items: [
          { title: 'Home', icon: 'dashboard', click: 'changePassword' },
          { title: 'About', icon: 'question_answer', click: 'changePassword' },
          { title: 'Ubah Password', icon: 'lock', click: 'changePassword' },
          { title: 'Keluar', icon: 'power_settings_new', click: 'logoutDialog' }
        ],
        mini: false,
        right: null,
        window: {
            width: 0,
            height: 0
        }
      }
    },
    created() {
        window.addEventListener('resize', this.handleResize)
        this.handleResize();
    },
    destroyed() {
        window.removeEventListener('resize', this.handleResize)
    },
    methods:{
    getUser () {
        if (localStorage.getItem('name') !== null) {
          this.loading = false
          return
        }
        this.$refs.topProgress.start()
        axios.get('/api/user', {
          headers: {
            Authorization: 'Bearer ' + localStorage.getItem('token')
          }
        }).then(response => {
          this.$refs.topProgress.done()
          this.loading = false
          if (response.data.status === 'Token is Expired' || response.data.userdata === null || response.data[0] === 'user_not_found') {
            this.$router.push({ name: 'Logout' })
          } else {
            this.user = response.data.userdata
            this.name = response.data.userdata.role.name
            localStorage.setItem('name', this.user.detail.nama)
            localStorage.setItem('id', this.user.id)
          }
        }).catch(error => {
          console.log(error)
          this.$router.push({ name: 'Logout' })
        })
      },
        routerGoto(name) {
            if(name == 'logoutDialog'){
                this.logoutDialog = true
                return
            }
            this.$router.push({name: name})
        },
        handleResize() {
           this.window.width = window.innerWidth;
        },
        cekSize(){
            if(this.window.width < 960){
               this.mini = true
           }else{
               this.mini = false
           }
        },
        showSide(){
            if(this.window.width < 960){
               this.drawer = !this.drawer
            }else{
               this.mini = !this.mini
            }
        }
    }
  }
</script>