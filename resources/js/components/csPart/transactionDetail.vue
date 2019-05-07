<template id="admin-list">
<div class="contentlist">
<div class="flex-container" >
      <div class="columns m-t-10">
        <div class="column">
          <h1 class="title">Detail Transaksi <br> Konsumen: <br> {{detailTransaksis.namaKonsumen}} <br> {{detailTransaksis.nomorKonsumen}}</h1>
        </div>
        <div class="column">
            <v-flex sm6 d-flex style="margin-left: auto">
              <v-btn slot="activator" color="blue lighten-2" dark @click.prevent="editDialog = true; typeInput = 'new'">Tambahkan Kendaraan</v-btn>
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
        <b-table :data="detailTransaksisList" :paginated="true" :per-page="perPage" :current-page.sync="currentPage" :loading="loadData" :pagination-simple="true" :narrowed="true" :mobile-cards="true" :striped="true" :hoverable="true">
            <template slot-scope="props">
                <b-table-column label="No." sortable>{{ props.index + 1 }}</b-table-column>
                <b-table-column field="plat_nomor" label="Plat Nomor" sortable >{{props.row.plat_nomor}}</b-table-column>
                <b-table-column field="kendaraan.merk" label="Merk" sortable >{{props.row.kendaraan.merk}}</b-table-column>
                <b-table-column field="kendaraan.tipe" label="Tipe" sortable >{{props.row.kendaraan.tipe}}</b-table-column>
                <b-table-column label="Pengaturan" sortable ><v-btn color="green" small dark @click.prevent="gotoRoute(props.row.id)">Detail</v-btn></b-table-column>
                            <b-table-column label=""><v-menu transition="slide-x-transition" offset-x left>
                                <v-btn slot="activator" icon >
                                <v-icon>more_vert</v-icon>
                                </v-btn>
                            <v-list>
                            <v-list-tile  @click.prevent="seteditData(props.row); editDialog = true;typeInput = 'edit'">
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
                                    Kendaraan Konsumen
                                </v-card-title>
                                <v-card-text>
                                      <v-flex xs12>
                                        <v-text-field label="Plat Nomor*" required v-model="editData.plat_nomor" :rules="[rules.required]"></v-text-field>
                                      </v-flex>
                                       <v-flex xs12>
                                             <v-autocomplete
                                                   v-model="editData.id_kendaraan"
                                                  :items="vehicles"
                                                  item-text="name"
                                                  item-value="id"
                                                  label="Jenis kendaraan"
                                                  hint="Kendaraan apa yang cocok untuk sparepart ini?"
                                                  persistent-hint
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
    this.getVehicle()
    this.getCities()
  },
  data () {
    return {
      customer: {},
      load: false,
      cities: [],
      valid: true,
      typeInput: 'new',
      role: 'semua',
      saveRole: '',
      stat: '',
      search: '',
      detailTransaksis: [],
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
        plat_nomor: '',
        id_kendaraan: '',
        id_transaksi: -1
      },
      vehicles: [],
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
    detailTransaksisList () {
      if (this.detailTransaksis.length) {
        return this.detailTransaksis.filter((row, index) => {
          if (this.search !== '') return row.plat_nomor.toLowerCase().includes(this.search.toLowerCase())
          return true
        })
      }
    }
  },
  methods: {
    gotoRoute (id) {
      this.$router.push({name: 'TransaksiDetailItem', params: {TransaksiType: this.$route.params.TransaksiType, nomor_transaksi: this.$route.params.nomor_transaksi, idTransaksi: this.$route.params.idTransaksi, idDetail: id}})
    },
    cekPhoneNumber () {
      var config = {
        headers: {
          Authorization: 'Bearer ' + localStorage.getItem('token')
        }
      }
      var uri = '/api/cekPhoneNumber/' + this.editData.phoneNumber
      axios.get(uri, config).then(response => {
        if (response.data !== 0) {
          this.seteditData(response.data)
        }
      })
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
    getVehicle () {
      var uri
      var config = {
        headers: {
          Authorization: 'Bearer ' + localStorage.getItem('token')
        }
      }
      uri = '/api/kendaraan'
      axios.get(uri, config).then(response => {
        this.vehicles = response.data
        for (let i = 0; i < this.vehicles.length; i++) {
          this.vehicles[i].name = this.vehicles[i].merk + ' ' + this.vehicles[i].tipe
        }
      })
    },
    seteditData (data) {
      this.editData.id = data.id
      this.editData.plat_nomor = data.plat_nomor
      this.editData.id_kendaraan = data.kendaraan.id
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
      var uri = '/api/detailTransaksi/' + this.deleteId
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
      this.editData.id_transaksi = this.$route.params.idTransaksi
      this.load = true
      var config = {
        headers: {
          Authorization: 'Bearer ' + localStorage.getItem('token')
        }
      }
      var uri = '/api/detailTransaksi'
      axios.post(uri, this.editData, config).then(response => {
        this.resetData()
        this.getData()
      }).catch(error => {
        console.log(error.response)
        this.load = false
        this.snackbar = true
        this.color = 'error'
        this.text = error.response.data.errors.phoneNumber[0]
      })
    },
    UpdateData () {
      var uri
      var config = {
        headers: {
          Authorization: 'Bearer ' + localStorage.getItem('token')
        }
      }
      this.editData.id_transaksi = this.$route.params.idTransaksi
      this.load = true
      uri = '/api/detailTransaksi/' + this.editData.id
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
      var uri = '/api/detailTransaksi/' + this.$route.params.idTransaksi
      axios.get(uri, config).then(response => {
        this.detailTransaksis = response.data
        this.loadData = false
      })
    }
  }
}
</script>
