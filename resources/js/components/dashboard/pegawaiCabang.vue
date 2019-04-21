<template id="admin-list">
<div class="contentlist">
<div class="flex-container" >

      <div class="columns m-t-10">
        <div class="column">
          <h1 class="title">Daftar Pegawai</h1>
        </div>
        <div class="column">
            <v-flex sm6 d-flex style="margin-left: auto">
              <v-btn slot="activator" color="blue lighten-2" dark @click.prevent="editDialog = true; typeInput = 'new'">Tambah Pegawai</v-btn>
            </v-flex>
        </div>
      </div>
       <nav class="level">
             <div class="level-left">
                <b-field grouped group-multiline>
                    <b-select v-model="perPage" :disabled="!isPaginated">
                        <option value="10">10 per page</option>
                        <option value="20">20 per page</option>
                        <option value="50">50 per page</option>
                    </b-select>
                </b-field>
             </div>
             <div class="level-right">
                    <b-field>
                        <b-input placeholder="Search..."
                            type="search"
                            icon="magnify"
                            v-model="search">
                        </b-input>
                    </b-field>
                 </div>
           </nav>
        <b-table :data="usersList" :paginated="true" :per-page="perPage" :current-page.sync="currentPage" :loading="loadData" :pagination-simple="true" :narrowed="true" :mobile-cards="true" :striped="true" :hoverable="true" :default-sort-direction="defaultSortDirection" default-sort="created_at">
            <template slot-scope="props">
                <b-table-column label="No." sortable>{{ props.index + 1 }}</b-table-column>
                <b-table-column field="detail.nama" label="Nama" sortable>{{ props.row.nama }}</b-table-column>
                <b-table-column field="detail.no_telp" label="Nomor Telepon" sortable>{{ props.row.no_telp }}</b-table-column>
                <b-table-column field="detail.alamat" label="Alamat" sortable >{{ props.row.alamat }}</b-table-column>
                <b-table-column label="Peran" sortable >
                    <span v-if="props.row.user != null">{{props.row.user.role.name }}</span>
                    <span v-if="props.row.user == null">montir</span>
                </b-table-column>
                <b-table-column label="Gaji" sortable >{{convertPrice(props.row.gaji) }}</b-table-column>
                <b-table-column label="Diterima pada" sortable >{{props.row.created_at }}</b-table-column>
                            <b-table-column label=""><v-menu transition="slide-x-transition" offset-x left>
                                <v-btn slot="activator" icon >
                                <v-icon>more_vert</v-icon>
                                </v-btn>
                            <v-list>
                            <v-list-tile  @click.prevent="seteditData(props.row); editDialog = true">
                                <v-list-tile-title  >Perbaharui</v-list-tile-title>
                            </v-list-tile>
                            <v-list-tile @click.prevent="deleteId = props.row.id; deleteDialog = true">
                                <v-list-tile-title >Hapus</v-list-tile-title>
                            </v-list-tile>
                            </v-list>
                        </v-menu>
                        </b-table-column>   
            </template>
              <template slot="empty">
                <section class="section">
                    <div class="content has-text-grey has-text-centered">
                        <p>
                            <b-icon
                                icon="emoticon-sad"
                                size="is-large">
                            </b-icon>
                        </p>
                        <p>Belum ada data pegawai, silahkan tambahkan pegawai terlebih dahulu</p>
                    </div>
                </section>
            </template>
        </b-table>
      
       <v-snackbar
          v-model="snackbar"
          :color="color"
          :multi-line="true"
          :timeout="3000"
        >
          {{ text }}
          <v-btn
            dark
            flat
            @click="snackbar = false"
          >
            Close
          </v-btn>
        </v-snackbar>

        <!-- edit -->
         <v-layout row justify-center>
                            <v-dialog v-model="editDialog" persistent max-width="600px">
                                <v-form
                                    ref="form"
                                    v-model="valid"
                                    lazy-validation
                                >
                              <v-card>
                                <v-card-title>
                                    Tambahkan Pegawai
                                </v-card-title>
                                <v-card-text>
                                      <v-flex xs12>
                                        <v-text-field label="Nama*" required v-model="editData.nama" :rules="[rules.required, rules.textOnly]"></v-text-field>
                                      </v-flex>
                                        <v-flex xs12>
                                        <v-text-field label="Nomor Handphone*" required v-model="editData.no_telp" :rules="[rules.required, rules.numberOnly]"></v-text-field>
                                      </v-flex>
                                       <v-flex xs12>
                                        <v-text-field label="Alamat*" required v-model="editData.alamat" :rules="[rules.required]"></v-text-field>
                                      </v-flex>
                                       <v-flex xs12>
                                        <v-text-field label="Gaji*" type="number" required v-model="editData.gaji" :rules="[rules.required, rules.numberOnly]"></v-text-field>
                                      </v-flex>
                                      <v-flex xs12>
                                            <v-select
                                            :items="roles"
                                            label="Peran"
                                            outline
                                            v-model="editData.role"
                                            ></v-select>
                                        </v-flex>
                                      <v-flex xs12>
                                        <v-text-field label="Username*" v-model="editData.username" :rules="[rules.required]" v-if="editData.role != 'montir' && editData.role != null" ></v-text-field>
                                      </v-flex>
                                      <v-flex xs12>
                                        <v-text-field label="Password*"  type="password" v-model="editData.password" :rules="[rules.required]" v-if="editData.role != 'montir' && editData.role != null && (typeInput != 'edit' || saveRole == 'montir')"></v-text-field>
                                      </v-flex>
                                      <v-flex xs12 v-if="saveRole != 'montir' && editData.role != 'montir'">
                                        <v-checkbox
                                          label="Ubah Password ?"
                                          v-model="editData.changePassword"
                                          v-if="typeInput == 'edit' && editData.username != null"
                                          ></v-checkbox>
                                      </v-flex>
                                      <v-flex xs12>
                                        <v-text-field label="Password baru*"  type="password" v-model="editData.password" :rules="[rules.required]" v-if="editData.changePassword"></v-text-field>
                                      </v-flex>
                                  <small>*Wajib diisi</small>
                                </v-card-text>
                                <v-card-actions>
                                  <v-spacer></v-spacer>
                                  <v-btn color="red darken-1" dark @click.prevent="editDialog = false; resetData()">Batal</v-btn>
                                  <v-btn color="green darken-1" dark :loading="load" v-if="typeInput == 'new'" @click.prevent="SendData()">Tambahkan</v-btn>
                                  <v-btn color="orange darken-1" dark :loading="load" v-if="typeInput == 'edit'" @click.prevent="UpdateData()">Perbaharui</v-btn>
                                </v-card-actions>
                              </v-card>
                                </v-form>
                            </v-dialog>
                          </v-layout>
        <!-- edit -->
        <!-- delete -->
          <v-layout row justify-center>
                  <v-dialog v-model="deleteDialog" persistent max-width="290">
                    <v-card>
                      <v-card-title class="headline">Hapus Data Pengguna ?</v-card-title>
                      <v-card-text>Data yang dihapus tidak akan bisa dikembalikan lagi</v-card-text>
                      <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="green darken-1" flat @click="deleteDialog = false; deleteId = -1">Batal</v-btn>
                        <v-btn color="red darken-1" flat @click="deleteData()">Hapus</v-btn>
                      </v-card-actions>
                    </v-card>
                  </v-dialog>
                </v-layout>
        <!-- delete -->
</div>
</div>
</template>

<script>
export default {
  mounted () {
    this.$parent.tab = 'branchD1'
    this.getData()
  },
  data () {
    return {
      load: false,
      cities: [],
      valid: true,
      typeInput: 'new',
      role: 'semua',
      saveRole: '',
      stat: '',
      search: '',
      users: [],
      dialog: false,
      deleteDialog: false,
      editDialog: false,
      data: {
        nama: null,
        namaEvent: null,
        username: null
      },
      snackbar: false,
      text: '',
      color: null,
      reset: false,
      editData: {
        nama: '',
        username: null,
        role: null,
        password: '',
        no_telp: '',
        alamat: '',
        gaji: 0,
        changePassword: false
      },
      deleteId: -1,
      loading: false,
      loadData: true,
      isPaginated: true,
      isPaginationSimple: true,
      defaultSortDirection: 'desc',
      currentPage: 1,
      perPage: 10,
      roles: ['cs','kasir','montir'],
      usernameRules: [
        v => !!v || 'Username tidak boleh kosong',
        v => /.+@.+/.test(v) || 'Username tidak valid'
      ],
      rules: {
        required: value => !!value || 'Data ini tidak boleh kosong',
        numberOnly: value => !isNaN(value) || 'Data tidak valid, hanya diijinkan memasukkan angka',
        textOnly: value => RegExp(/^[A-Za-z ]+$/).test(value) || 'Data tidak valid, hanya diijinkan memasukkan huruf'
      }
    }
  },
  computed: {
    usersList () {
      if (this.users.length) {
        return this.users.filter((row, index) => {
            if (this.search !== '') return row.nama.toLowerCase().includes(this.search.toLowerCase()) || row.user.username.toLowerCase().includes(this.search.toLowerCase()) || row.no_telp.toLowerCase().includes(this.search.toLowerCase())
            return true
        })
      }
    }
  },
  methods: {
    convertPrice (value) {
      const formatter = new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 2
      })
      return formatter.format(value)
    },
    seteditData (data) {
      this.typeInput = 'edit'
      this.editData.id = data.id
      this.editData.nama = data.nama
      this.editData.no_telp = data.no_telp
      this.editData.alamat = data.alamat
      this.editData.gaji = data.gaji
      if (data.user !== null) {
        this.editData.role = data.user.role.name
        this.editData.username = data.user.username
          this.editData.user_id = data.user_id
      } else {
        this.editData.role = 'montir'
        this.editData.user_id = -1
      }
      this.saveRole = this.editData.role
    },
    resetData () {
      this.$refs.form.reset()
      this.typeInput = 'new'
      this.editData.role = null
      this.editData.username = null
      this.saveRole = ''
      this.load = false
    },
    deleteData () {
      var config = {
        headers: {
          Authorization: 'Bearer ' + localStorage.getItem('token')
        }
      }
      this.load = true
      var uri = '/api/pegawai/' + this.deleteId
      axios.delete(uri, config).then(response => {
        this.snackbar = true
        this.text = 'Data berhasil dihapus'
        this.color = 'green'
        this.deleteDialog = false
        this.load = false
        this.getData()
      }).catch(error => {
        console.log(error)
        this.snackbar = true
        this.text = 'Coba Lagi'
        this.color = 'red'
        this.deleteDialog = false
        this.load = false
      })
    },
    SendData () {
      if (!this.$refs.form.validate()) {
        this.snackbar = true
        this.text = 'Mohon untuk melengkapi form yang tersedia'
        this.color = 'error'
        return
      }
      if (this.editData.role === null) {
        this.snackbar = true
        this.text = 'Mohon untuk menentukan peran dari pegawai anda'
        this.color = 'error'
        return
      }
      this.load = true
      var config = {
        headers: {
          Authorization: 'Bearer ' + localStorage.getItem('token')
        }
      }
      var uri = '/api/pegawai'
      this.editData.branch = this.$route.params.idCabang
      axios.post(uri, this.editData, config).then(response => {
        this.resetData()
        this.getData()
      }).catch(error => {
        console.log(error.response)
        this.load = false
        this.snackbar = true
        this.text = error.response.data.errors.no_telp[0]
        this.color = 'red'
      })
    },
    UpdateData () {
      if (!this.$refs.form.validate()) {
        this.snackbar = true
        this.text = 'Mohon untuk melengkapi form yang tersedia'
        this.color = 'error'
        return
      }
      if (this.editData.role === null) {
        this.snackbar = true
        this.text = 'Mohon untuk menentukan peran dari pegawai anda'
        this.color = 'error'
        return
      }
      this.load = true
      var config = {
        headers: {
          Authorization: 'Bearer ' + localStorage.getItem('token')
        }
      }
      var uri = '/api/pegawai/' + this.editData.id
      this.editData.branch = this.$route.params.idCabang
      axios.post(uri, this.editData, config).then(response => {
        this.resetData()
        this.getData()
      }).catch(error => {
        console.log(error.response)
        this.snackbar = true
        this.text = error.response.data.errors.no_telp[0]
        this.color = 'error'
        this.load = false
      })
    },
    getData () {
      this.editDialog = false
      this.deleteDialog = false
      var config = {
        headers: {
          Authorization: 'Bearer ' + localStorage.getItem('token')
        }
      }
      var uri = '/api/employeebyBranch/' + this.$route.params.idCabang
      axios.get(uri, config).then(response => {
        this.users = response.data
        this.loadData = false
      })
    }
  }
}
</script>
