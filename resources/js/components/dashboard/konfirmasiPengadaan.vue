<template id="admin-list">
<div class="contentlist">
<div class="flex-container" >
      <div class="columns m-t-10">
        <div class="column">
          <h1 class="title">Daftar Sparepart</h1>
        </div>
        <div class="column">
            <v-flex sm12 d-flex style="margin-left: auto">
              <v-btn slot="activator" md6 color="green" dark @click.prevent="konfirmDialog = true" v-if="status != 2">Konfirmasi</v-btn>
            </v-flex>
        </div>
      </div>
      <div class="columns is-multiline" >
        <div class="column is-3">
          <div class="card">
            <div class="card-content">
              <div class="card-title">
                <h2>Supplier</h2>
              </div>
              {{supplier.nama}} <br>
              {{supplier.no_telp}} <br>
              {{supplier.alamat}} <br>
            </div>
          </div>
        </div>
        <div class="column is-3">
          <div class="card">
            <div class="card-content">
              <div class="card-title">
                <h2>Sales</h2>
              </div>
              {{sales.nama}} <br>
              {{sales.no_telp}} <br>
              {{sales.alamat}} <br>
            </div>
          </div>
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
        <b-table :data="sparepartList" :paginated="true" :per-page="perPage" :current-page.sync="currentPage" :loading="loadData" :pagination-simple="true" :narrowed="true" :mobile-cards="true" :striped="true" :hoverable="true" :default-sort-direction="defaultSortDirection" default-sort="stok">
            <template slot-scope="props">
                <b-table-column label="No." sortable>{{ props.index + 1 }}</b-table-column>
                <b-table-column field="kode" label="Kode" sortable>{{ props.row.data.sparepart.kode }}</b-table-column>
                <b-table-column field="nama" label="Nama" sortable>{{ props.row.data.sparepart.nama }}</b-table-column>
                <b-table-column field="merk" label="Merk" sortable>{{ props.row.data.sparepart.merk }}</b-table-column>
                <b-table-column field="tipe" label="Tipe" sortable>{{ props.row.data.sparepart.tipe }}</b-table-column>
                <b-table-column field="stok" label="Stock" sortable numeric>{{props.row.data.stok }}</b-table-column>
                <b-table-column label="Stock Minimal" sortable numeric>{{props.row.data.limitStok }}</b-table-column>
                <b-table-column label="Harga Beli" sortable numeric>{{price(props.row.data.hargaBeli) }}</b-table-column>
                <b-table-column label="Jumlah yang dipesan" sortable numeric>{{props.row.total }}</b-table-column>
                <b-table-column label="Jumlah yang diterima" sortable numeric>{{props.row.totalDiterima }}</b-table-column>
                            <b-table-column label=""><v-menu transition="slide-x-transition" offset-x left v-if="status != 2">
                                <v-btn slot="activator" icon >
                                <v-icon>more_vert</v-icon>
                                </v-btn>
                            <v-list>
                            <v-list-tile  @click.prevent="seteditData(props.row); editDialog = true">
                                <v-list-tile-title  >Edit</v-list-tile-title>
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
                        <p>Belum ada data sparepart, silahkan tambahkan sparepart terlebih dahulu</p>
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
                                    Konfirmasi jumlah sparepart yang diterima
                                </v-card-title>
                                <v-card-text>
                                  <v-container grid-list-md>
                                    <v-layout wrap>
                                      
                                      <v-flex xs12>
                                         <v-autocomplete
                                                :items="spareparts"
                                                :filter="sparepartFilter"
                                                item-text="view"
                                                label="Sparepart yang tersedia dicabang ini (kode sparepart)"
                                                :rules="[rules.required]"
                                                v-model="editData.sparepart"
                                                return-object
                                                v-if="typeInput != 'edit'"
                                                :error-messages="errorSparepart"
                                                @input="cekSparepartkode()"
                                          ></v-autocomplete>
                                      </v-flex>
                                      <v-flex xs12>
                                        <v-text-field label="Kode Sparepart*" v-model="editData.sparepart.kode" :disabled="true"></v-text-field>
                                      </v-flex>
                                       <v-flex xs12>
                                        <v-text-field label="Nama Sparepart*" v-model="editData.sparepart.nama" :disabled="true"></v-text-field>
                                      </v-flex>
                                       <v-flex xs12>
                                        <v-text-field label="Merk Sparepart*" v-model="editData.sparepart.merk" :disabled="true"></v-text-field>
                                      </v-flex>
                                       <v-flex xs12>
                                        <v-text-field type="text" label="Stock Saat Ini"  :disabled="true" v-model="editData.stok"></v-text-field>
                                      </v-flex>
                                       <v-flex xs12>
                                        <v-text-field type="text" label="Stock Minimal Sparepart" :disabled="true" v-model="editData.limitStok"></v-text-field>
                                      </v-flex>
                                       <v-flex xs12>
                                        <v-text-field type="text" label="Jumlah pesanan" :disabled="true" v-model="editData.total"></v-text-field>
                                      </v-flex>
                                       <v-flex xs12>
                                        <v-text-field type="text" label="Jumlah barang diterima*" :rules="[rules.required,rules.numberOnly]" v-model="editData.totalDiterima"></v-text-field>
                                      </v-flex>
                                       <v-flex xs12>
                                        <v-text-field type="text" label="Harga Beli*" :rules="[rules.required,rules.numberOnly]" v-model="editData.hargaBeli"></v-text-field>
                                      </v-flex>
                                       <v-flex xs12>
                                        <v-text-field type="text" label="Harga Jual" :rules="[rules.required,rules.numberOnly]" v-model="editData.hargaJual"></v-text-field>
                                      </v-flex>
                                    </v-layout>
                                  </v-container>
                                  <small>*Wajib diisi</small>
                                </v-card-text>
                                <v-card-actions>
                                  <v-spacer></v-spacer>
                                  <v-btn color="red darken-1" dark @click.prevent="editDialog = false; resetData()">Batal</v-btn>
                                  <v-btn color="green darken-1" dark @click.prevent="SendData()" :loading="loading">Konfirmasi</v-btn>
                                </v-card-actions>
                              </v-card>
                                </v-form>
                            </v-dialog>
                          </v-layout>
        <!-- edit -->
        <!-- edit -->
           <v-layout row justify-center>
                  <v-dialog v-model="konfirmDialog" persistent max-width="290">
                    <v-card>
                      <v-card-title class="headline">Konfirmasi Pemesanan Ini?</v-card-title>
                      <v-card-text>Pemesanan yang dikonfirmasi tidak akan dapat ubah lagi</v-card-text>
                      <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="green darken-1" flat @click="konfirmDialog = false">Batal</v-btn>
                        <v-btn color="red darken-1" flat @click="konfirmasiData()">Konfirmasi</v-btn>
                      </v-card-actions>
                    </v-card>
                  </v-dialog>
                </v-layout>
        <!-- edit -->
</div>
</div>
</template>

<script>
export default {
  mounted () {
    this.$parent.tab = 'branchD5'
    this.getData()
    this.getCities()
  },
  data () {
    return {
      status: 2,
      sales: {},
      pemesanan: false,
      supplier: {
        nama: ''
      },
      konfirmDialog: false,
      errorSparepart: '',
      valid: true,
      typeInput: 'new',
      role: 'semua',
      saveRole: '',
      stat: '',
      search: '',
      spareparts: [],
      order: [],
      dialog: false,
      deleteDialog: false,
      snackbar: false,
      text: '',
      color: null,
      reset: false,
      editData: {
        sparepart: {
          nama: '',
          kode: '',
          merk: '',
          tipe: ''
        },
        branch_id: -1,
        position: '',
        limitStok: '',
        letak: 'DPN',
        rak: 'KACA',
        urut: '01',
        hargaBeli: null,
        hargaJual: null
      },
      editDialog: false,
      load: false,
      deleteId: -1,
      loading: false,
      loadData: true,
      isPaginated: true,
      isPaginationSimple: true,
      defaultSortDirection: 'asc',
      currentPage: 1,
      perPage: 10,
      roles: [],
      unique: true,
      errorMessage: '',
      rules: {
        required: value => !!value || 'Data ini tidak boleh kosong',
        numberOnly: value => !isNaN(value) || 'Data tidak valid, hanya diijinkan memasukkan angka',
        textOnly: value => RegExp(/^[A-Za-z ]+$/).test(value) || 'Data tidak valid, hanya diijinkan memasukkan huruf'
      },
      cities: []
    }
  },
  computed: {
    sparepartList () {
      if (this.order.length) {
        return this.order.filter((row, index) => {
          if (this.search !== '') return row.data.sparepart.nama.toLowerCase().includes(this.search.toLowerCase()) || row.data.sparepart.kode.toLowerCase().includes(this.search.toLowerCase())
          return true
        })
      }
    }
  },
  methods: {
    cekSparepartkode () {
      var sparepart = this.order.filter((row, index) => {
        if (row.sparepart.kode === this.editData.sparepart.kode) {
          return true
        }
      })
      if (sparepart.length > 0) {
        this.errorSparepart = 'Mohon maaf, sparepart ini sudah dimasukkan kedalam cabang ini.'
      } else {
        this.errorSparepart = ''
      }
    },
    price (value) {
      const formatter = new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 2
      })
      return formatter.format(value)
    },
    sparepartFilter (item, queryText, itemText) {
      const textOne = item.kode.toLowerCase()
      const searchText = queryText.toLowerCase()
      return textOne.indexOf(searchText) > -1
    },
    setData () {
      var sparepart = this.spareparts.filter((row, index) => {
        if (row.kode === this.editData.sparepart.kode) return true
      })
      console.log(sparepart)
      if (sparepart.length < 1) {
        this.errorkode = true
        this.errorMessage = 'Data tidak ditemukan'
      } else {
        this.errorkode = false
        this.errorMessage = ''
        this.editData.sparepart = sparepart[0]
      }
    },
    seteditData (data) {
      this.typeInput = 'edit'
      this.editData.sparepart = data.data.sparepart
      this.editData.hargaBeli = data.data.hargaBeli
      this.editData.hargaJual = data.data.hargaJual
      this.editData.stok = data.data.stok
      this.editData.limitStok = data.data.limitStok
      this.editData.total = data.total
      this.editData.totalDiterima = data.totalDiterima
      this.editData.id = data.id
      this.editData.sparepart_cabang = data.data.id
      this.editData.sparepart.view = this.editData.sparepart.kode + ' ' + this.editData.sparepart.nama
    },
    resetData (data) {
      this.loading = false
      this.editDialog = false
      this.konfirmDialog = false
      this.deleteId = -1
      this.typeInput = 'new'
      this.editData.sparepart = {
        nama: '',
        kode: '',
        merk: '',
        tipe: ''
      }
      this.editData.branch_id = -1
      this.editData.position = ''
      this.editData.limitStok = ''
    },
    konfirmasiData () {
      var config = {
        headers: {
          Authorization: 'Bearer ' + localStorage.getItem('token')
        }
      }
      var uri = '/api/konfirmasiData/' + this.$route.params.idPemesanan
      axios.get(uri, config).then(response => {
        this.snackbar = true
        this.text = 'Data berhasil di konfirmasi'
        this.color = 'green'
        this.getData()
      }).catch(error => {
        console.log(error)
        this.snackbar = true
        this.text = 'Coba Lagi'
        this.color = 'red'
        this.resetData()
      })
    },
    SendData () {
      if (!this.$refs.form.validate()) {
        this.snackbar = true
        this.text = 'Mohon untuk melengkapi form yang tersedia'
        this.color = 'red'
        return
      }
      if (this.editData.totalDiterima === null) {
        this.editData.totalDiterima = 0
      }
      if (this.editData.totalDiterima > this.editData.total) {
        this.snackbar = true
        this.text = 'Total yang diterima melebihi jumlah yang dipesan'
        this.color = 'red'
        this.editData.totalDiterima = this.editData.total
        return
      }
      if (this.editData.hargaBeli > this.editData.hargaJual) {
        this.snackbar = true
        this.text = 'Harga beli lebih mahal daripada harga jual'
        this.color = 'red'
        this.editData.totalDiterima = this.editData.total
        return
      }
      this.loading = true
      var uri
      var config = {
        headers: {
          Authorization: 'Bearer ' + localStorage.getItem('token')
        }
      }
      uri = '/api/storetotalaccept'
      axios.post(uri, this.editData, config).then(response => {
        this.resetData()
        this.getData()
      }).catch(error => {
        console.log(error.response)
        this.snackbar = true
        this.text = error.response.data.errors.no_telp[0]
        this.color = 'red'
      })
    },
    getData () {
      this.konfirmDialog = false
      var uri
      var config = {
        headers: {
          Authorization: 'Bearer ' + localStorage.getItem('token')
        }
      }
      uri = '/api/detailPemesanan/' + this.$route.params.idPemesanan
      axios.get(uri, config).then(response => {
        this.order = response.data.detail
        this.supplier = response.data.supplier
        this.sales = response.data.sales
        this.status = response.data.status
        this.loadData = false
      })
    }
  }
}
</script>
