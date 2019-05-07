<template id="admin-list">
<div class="contentlist">
<div class="flex-container" >

      <div class="columns m-t-10">
        <div class="column">
          <h1 class="title">Transaksi Cabang</h1>
        </div>
        <div class="column">
            <v-flex sm6 d-flex style="margin-left: auto">
              <v-btn slot="activator" color="blue lighten-2" dark @click.prevent="editDialog = true; typeInput = 'new'">Transaksi Baru</v-btn>
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
                <b-table-column label="Nomor Transaksi" sortable >{{ props.row.nomor_transaksi+"-"+props.row.id }}</b-table-column>
                <b-table-column field="namaKonsumen" label="Nama Konsumen" sortable >{{ props.row.namaKonsumen }}</b-table-column>
                <b-table-column field="nomorKonsumen" label="Nomor Handphone" sortable >{{ props.row.nomorKonsumen }}</b-table-column>
                <b-table-column field="alamatKonsumen" label="Alamat" sortable >{{ props.row.alamatKonsumen }}</b-table-column>
                <b-table-column field="status" label="Status" sortable >
                  <span v-if="props.row.status == 0"><v-chip color="blue" dark>Pengisian Data</v-chip></span>
                  <span v-if="props.row.status == 1"><v-chip color="orange" dark>Sedang Dikerjakan</v-chip></span>
                  <span v-if="props.row.status == 2"><v-chip color="yellow">Pengerjaan Selesai</v-chip></span>
                  <span v-if="props.row.status == 3"><v-chip color="green" dark>Transaksi Selesai</v-chip></span>
                  </b-table-column>
                <b-table-column label="Pengaturan" sortable ><v-btn color="green" small dark @click.prevent="gotoRoute(props.row.nomor_transaksi,props.row.id)">Detail</v-btn></b-table-column>
                <b-table-column label="Diterima pada" sortable >{{props.row.created_at }}</b-table-column>
                            <b-table-column label=""><v-menu transition="slide-x-transition" offset-x left>
                                <v-btn slot="activator" icon >
                                <v-icon>more_vert</v-icon>
                                </v-btn>
                            <v-list>
                            <v-list-tile  @click.prevent="seteditData(props.row); editDialog = true;typeInput = 'edit'" v-if="props.row.status != 3">
                                <v-list-tile-title  >Perbaharui</v-list-tile-title>
                            </v-list-tile>
                            <v-list-tile @click.prevent="updateStatus(props.row)" v-if="props.row.status != 0">
                                <v-list-tile-title ><span v-if="props.row.status < 2">Pengerjaan Selesai</span><span v-else>Pengerjaan Diperpanjang</span></v-list-tile-title>
                            </v-list-tile>
                            <v-list-tile @click.prevent="deleteId = props.row.id; deleteDialog = true" v-if="props.row.status != 3">
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
                                    Buat Transaksi Baru
                                </v-card-title>
                                <v-card-text>
                                      <v-flex xs12>
                                        <v-text-field label="Nama Konsumen*" required v-model="editData.namaKonsumen" :rules="[rules.required, rules.textOnly]"></v-text-field>
                                      </v-flex>
                                        <v-flex xs12>
                                        <v-text-field label="Nomor Handphone Konsumen*" required v-model="editData.nomorKonsumen" :rules="[rules.required, rules.numberOnly]"></v-text-field>
                                      </v-flex>
                                       <v-flex xs12>
                                        <v-text-field label="Alamat Konsumen*" required v-model="editData.alamatKonsumen" :rules="[rules.required]"></v-text-field>
                                      </v-flex>
                                       <v-flex xs12>
                                             <v-autocomplete
                                                :items="jt"
                                                item-text="name"
                                                item-value="initials"
                                                label="Jenis Transaksi"
                                                :rules="[rules.required]"
                                                v-model="editData.jenisTransaksi"
                                              ></v-autocomplete>
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
    this.$parent.tab = 'branchD3'
    this.getData()
    this.getRoles()
    this.getCities()
  },
  data () {
    return {
      idCabang: localStorage.getItem('idCabang'),
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
      snackbar: false,
      text: '',
      color: null,
      reset: false,
      jt: [
        {
          initials: 'SV',
          name: 'Service'
        },
        {
          initials: 'SP',
          name: 'Sparepart'
        },
        {
          initials: 'SS',
          name: 'Service & Sparepart'
        }
      ],
      editData: {
        namaKonsumen: '',
        role: 'konsumen',
        nomorKonsumen: '',
        alamatKonsumen: '',
        jenisTransaksi: ''
      },
      deleteId: -1,
      loading: false,
      loadData: true,
      isPaginated: true,
      isPaginationSimple: true,
      defaultSortDirection: 'desc',
      currentPage: 1,
      perPage: 10,
      roles: [],
      emailRules: [
        v => !!v || 'Email tidak boleh kosong',
        v => /.+@.+/.test(v) || 'Email tidak valid'
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
          var data = row.nomor_transaksi + '-' + row.id
          if (this.search !== '') return data.toLowerCase().includes(this.search.toLowerCase()) || row.namaKonsumen.toLowerCase().includes(this.search.toLowerCase())
          return true
        })
      }
    }
  },
  methods: {
    gotoRoute (nomor_transaksi, id) {
      var tN = nomor_transaksi.split('-')
      this.$router.push({name: 'TransaksiDetail', params: {TransaksiType: tN[0], nomor_transaksi: tN[1], idTransaksi: id}})
    },
    citiesFilter (item, queryText, itemText) {
      const textOne = item.name.toLowerCase()
      const searchText = queryText.toLowerCase()
      return textOne.indexOf(searchText) > -1
    },
    getCities () {
      var uri = '/api/cities'
      axios.get(uri).then(response => {
        this.cities = response.data
      })
    },
    getRoles () {
      var config = {
        headers: {
          Authorization: 'Bearer ' + localStorage.getItem('token')
        }
      }
      var uri = '/api/role'
      axios.get(uri, config).then(response => {
        this.roles = response.data
      })
    },
    setDataCustomer (data) {
      this.editData.customerId = data.id
      this.editData.namaKonsumen = data.namaKonsumen
      this.editData.nomorKonsumen = data.nomorKonsumen
      this.editData.alamatKonsumen = data.alamatKonsumen
      this.editData.city = data.city
    },
    seteditData (data) {
      this.editData.id = data.id
      this.editData.customerId = data.id
      this.editData.namaKonsumen = data.namaKonsumen
      this.editData.nomorKonsumen = data.nomorKonsumen
      this.editData.alamatKonsumen = data.alamatKonsumen
      var typeTransaksi = data.nomor_transaksi.split('-')
      this.editData.jenisTransaksi = typeTransaksi[0]
    },
    resetData () {
      this.$refs.form.reset()
      this.typeInput = 'new'
      this.editData.role = null
      this.editData.email = null
      this.saveRole = ''
      this.load = false
    },
    deleteData () {
      this.load = true
      var config = {
        headers: {
          Authorization: 'Bearer ' + localStorage.getItem('token')
        }
      }
      var uri = '/api/transaksi/' + this.deleteId
      axios.delete(uri, config).then(response => {
        this.snackbar = true
        this.text = 'Data berhasil dihapus'
        this.color = 'green'
        this.getData()
      }).catch(error => {
        console.log(error)
        this.snackbar = true
        this.text = 'Coba Lagi'
        this.color = 'red'
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
      this.editData.role = 'konsumen'
      this.load = true
      var config = {
        headers: {
          Authorization: 'Bearer ' + localStorage.getItem('token')
        }
      }
      var uri = '/api/transaksi'
      this.editData.idCabang = this.idCabang
      axios.post(uri, this.editData, config).then(response => {
        this.resetData()
        this.getData()
      }).catch(error => {
        console.log(error.response)
        this.load = false
        this.snackbar = true
        this.color = 'error'
        this.text = error.response.data.errors.nomorKonsumen[0]
      })
    },
    UpdateData () {
      var uri
      var config = {
        headers: {
          Authorization: 'Bearer ' + localStorage.getItem('token')
        }
      }
      this.editData.role = 'konsumen'
      this.load = true
      uri = '/api/updateTransaksi/' + this.editData.id
      axios.post(uri, this.editData, config).then(response => {
        console.log(response)
        this.getData()
      }).catch(error => {
        console.log(error)
        this.snackbar = true
        this.text = 'Coba Lagi'
        this.color = 'red'
        this.load = false
      })
    },
    getData () {
      this.editDialog = false
      this.deleteDialog = false
      this.load = false
      var config = {
        headers: {
          Authorization: 'Bearer ' + localStorage.getItem('token')
        }
      }
      var uri = '/api/transaksiByBranch/' + this.idCabang
      axios.get(uri, config).then(response => {
        this.users = response.data
        this.loadData = false
      })
    }
  }
}
</script>
