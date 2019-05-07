<template id="admin-list">
<div class="contentlist">
<div class="flex-container" >
      <div class="columns m-t-10">
        <div class="column">
          <h1 class="title">Daftar Sparepart</h1>
        </div>
        <div class="column">
            <v-flex sm12 d-flex style="margin-left: auto">
              <v-btn slot="activator" md6 color="red " dark @click.prevent="deleteDialog = true" v-if="pemesanan == true">Batalkan Pesanan</v-btn>
              <v-btn slot="activator" md6 color="blue lighten-2" dark @click.prevent="salesDialog = true"  v-if="pemesanan == true">Cetak Surat Pemesanan</v-btn>
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
                            tipe="search"
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
                <b-table-column field="tempat" label="Posisi" sortable>{{ props.row.data.tempat }}</b-table-column>
                <b-table-column field="stok" label="Stock" sortable >{{props.row.data.stok }}</b-table-column>
                <b-table-column label="Stock Minimal" sortable >{{props.row.data.limitStok }}</b-table-column>
                <b-table-column label="Harga Beli" sortable >{{price(props.row.data.hargaJual) }}</b-table-column>
                <b-table-column label="Harga Jual" sortable >{{price(props.row.data.hargaJual) }}</b-table-column>
                <b-table-column label="Jumlah Yang dipesan" sortable >{{props.row.total }}</b-table-column>
                            <b-table-column label=""><v-menu transition="slide-x-transition" offset-x left>
                                <v-btn slot="activator" icon >
                                <v-icon>more_vert</v-icon>
                                </v-btn>
                            <v-list>
                            <v-list-tile  @click.prevent="seteditData(props.row.data); editDialog = true">
                                <v-list-tile-title  >Pesan</v-list-tile-title>
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
                                    Tambahkan Sparepart
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
                                                v-if="tipeInput != 'edit'"
                                                :error-messages="errorSparepart"
                                                @input="cekSparepartCode()"
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
                                        <v-text-field tipe="text" label="Stock Saat Ini"  :disabled="true" v-model="editData.stok"></v-text-field>
                                      </v-flex>
                                       <v-flex xs12>
                                        <v-text-field tipe="text" label="Stock Minimal Sparepart" :disabled="true" v-model="editData.limitStok"></v-text-field>
                                      </v-flex>
                                       <v-flex xs12>
                                        <v-text-field tipe="text" label="Jumlah pesanan" :rules="[rules.required,rules.numberOnly]" v-model="editData.total"></v-text-field>
                                      </v-flex>
                                       <v-flex xs12>
                                        <v-text-field tipe="text" label="Satuan Barang" :rules="[rules.required,rules.textOnly]" v-model="editData.satuan"></v-text-field>
                                      </v-flex>
                                    </v-layout>
                                  </v-container>
                                  <small>*Wajib diisi</small>
                                </v-card-text>
                                <v-card-actions>
                                  <v-spacer></v-spacer>
                                  <v-btn color="red darken-1" dark @click.prevent="editDialog = false; resetData()">Batal</v-btn>
                                  <v-btn color="green darken-1" dark v-if="tipeInput == 'new'" @click.prevent="SendData()" :loading="loading">Tambahkan</v-btn>
                                  <v-btn color="orange darken-1" dark v-if="tipeInput == 'edit'" @click.prevent="UpdateData()" :loading="loading">Pesan</v-btn>
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
                      <v-card-title class="headline">Batalkan pemesanan ini ?</v-card-title>
                      <v-card-text>Pemesanan yang dihapus tidak akan dapat dikembalikan lagi</v-card-text>
                      <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="green darken-1" flat @click="deleteDialog = false">Batal</v-btn>
                        <v-btn color="red darken-1" flat @click="deleteData()">Hapus</v-btn>
                      </v-card-actions>
                    </v-card>
                  </v-dialog>
                </v-layout>
        <!-- delete -->
        <!-- edit -->
         <v-layout row justify-center>
                            <v-dialog v-model="salesDialog" persistent max-width="600px">
                                <v-form
                                    ref="form2"
                                    v-model="valid"
                                    lazy-validation
                                >
                              <v-card>
                                <v-card-title>
                                    Sales Supplier yang Bertugas
                                </v-card-title>
                                <v-card-text>
                                  <v-container grid-list-md>
                                    <v-layout wrap>
                                      <v-flex xs12>
                                        <v-text-field label="Nama Sales*" required v-model="salesData.nama" :rules="[rules.required, rules.textOnly]"></v-text-field>
                                      </v-flex>
                                        <v-flex xs12>
                                        <v-text-field label="Nomor Handphone Sales*" required v-model="salesData.no_telp" :rules="[rules.required, rules.numberOnly]"></v-text-field>
                                      </v-flex>
                                       <v-flex xs12>
                                        <v-text-field label="Alamat Sales*" required v-model="salesData.alamat" :rules="[rules.required]"></v-text-field>
                                      </v-flex>
                                    </v-layout>
                                  </v-container>
                                  <small>*Wajib diisi</small>
                                </v-card-text>
                                <v-card-actions>
                                  <v-spacer></v-spacer>
                                  <v-btn color="red darken-1" dark @click.prevent="salesDialog = false">Batal</v-btn>
                                  <v-btn color="green darken-1" dark @click.prevent="cetakPemesanan()" :loading="load">Cetak</v-btn>
                                </v-card-actions>
                              </v-card>
                                </v-form>
                            </v-dialog>
                          </v-layout>
        <!-- edit -->
</div>
</div>
</template>

<script>
export default {
  mounted () {
    this.$parent.tab = 'cabangD5'
    this.getData()
  },
  data () {
    return {
      pemesanan: false,
      salesDialog: false,
      errorSparepart: '',
      valid: true,
      tipeInput: 'new',
      role: 'semua',
      saveRole: '',
      stat: '',
      search: '',
      spareparts: [],
      cabangSpareprats: [],
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
        id_cabang: -1,
        tempat: '',
        limitStok: '',
        letak: 'DPN',
        rak: 'KACA',
        urut: '01',
        hargaJual: null,
        hargaJual: null
      },
      salesData: {
        nama: '',
        role: 'sales',
        no_telp: '',
        alamat: ''
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
      letak: ['DPN', 'TGH', 'BLK'],
      rak: ['KACA', 'DUS', 'BAN', 'KAYU'],
      errorMessage: '',
      errorCode: false,
      urut: [],
      emailRules: [
        v => !!v || 'Email tidak boleh kosong',
        v => /.+@.+/.test(v) || 'Email tidak valid'
      ],
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
      if (this.cabangSpareprats.length) {
        return this.cabangSpareprats.filter((row, index) => {
          if (this.search !== '') return row.data.sparepart.nama.toLowerCase().includes(this.search.toLowerCase()) || row.data.sparepart.kode.toLowerCase().includes(this.search.toLowerCase())
          return true
        })
      }
    }
  },
  methods: {
    setSalesData (data) {
      this.salesData.id = data.id
      this.salesData.nama = data.nama
      this.salesData.no_telp = data.no_telp
      this.salesData.alamat = data.alamat
      this.salesData.city = data.city
    },
    cetakPemesanan () {
      if (!this.$refs.form2.validate()) {
        this.snackbar = true
        this.text = 'Mohon untuk melengkapi form yang tersedia'
        this.color = 'error'
        return
      }
      this.load = true
      var uri
      var config = {
        responseType: 'blob',
        headers: {
          Authorization: 'Bearer ' + localStorage.getItem('token')
        }
      }
      this.salesData.id_supplier = this.$route.params.idSupplier
      this.salesData.id_cabang = this.$route.params.idCabang
      uri = '/api/cetakPemesanan'
      axios.post(uri, this.salesData, config).then(response => {
        const url = window.URL.createObjectURL(new Blob([response.data]))
        const link = document.createElement('a')
        link.href = url
        link.setAttribute('download', 'pemesanan_' + this.$route.params.idSupplier + '-' + this.$route.params.idCabang + '.pdf')
        document.body.appendChild(link)
        link.click()
        this.load = false
        this.salesDialog = false
        this.$nextTick(function () {
            this.getData()
        })
      }).catch(error => {
        console.log(error.response)
        this.snackbar = true
        this.text = 'Coba Lagi'
        this.color = 'error'
        this.load = false
      })
    },
    cekSparepartCode () {
      var sparepart = this.cabangSpareprats.filter((row, index) => {
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
        this.errorCode = true
        this.errorMessage = 'Data tidak ditemukan'
      } else {
        this.errorCode = false
        this.errorMessage = ''
        this.editData.sparepart = sparepart[0]
      }
    },
    seteditData (data) {
      this.tipeInput = 'edit'
      this.editData.sparepart = data.sparepart
      this.editData.hargaJual = data.hargaJual
      this.editData.hargaJual = data.hargaJual
      this.editData.stok = data.stok
      this.editData.limitStok = data.limitStok
      this.editData.id = data.id
      var tempat = data.tempat.split('-')
      this.editData.letak = tempat[0]
      this.editData.rak = tempat[1]
      this.editData.urut = tempat[2]
      this.editData.sparepart.view = this.editData.sparepart.kode + ' ' + this.editData.sparepart.nama
    },
    resetData (data) {
      this.loading = false
      this.deleteDialog = false
      this.deleteId = -1
      this.tipeInput = 'new'
      this.editData.sparepart = {
        nama: '',
        kode: '',
        merk: '',
        tipe: ''
      }
      this.editData.id_cabang = -1
      this.editData.tempat = ''
      this.editData.limitStok = ''
    },
    deleteData () {
      var config = {
        headers: {
          Authorization: 'Bearer ' + localStorage.getItem('token')
        }
      }
      var uri = '/api/sparepartBS/' + this.$route.params.idSupplier + '/' + this.$route.params.idCabang
      axios.delete(uri, config).then(response => {
        this.snackbar = true
        this.text = 'Data berhasil dihapus'
        this.color = 'green'
        this.resetData()
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
      if (this.errorSparepart !== '') {
        this.snackbar = true
        this.text = 'Mohon untuk melengkapi form yang tersedia'
        this.color = 'red'
        return
      }
      if (this.errorCode) {
        this.snackbar = true
        this.text = 'Mohon untuk kode sparepart yang benar'
        this.color = 'error'
        return
      }
      this.editData.tempat = this.editData.letak + '-' + this.editData.rak + '-' + this.editData.urut
      this.loading = true
      this.editData.kode_sparepart = this.editData.sparepart.kode
      var uri
      var config = {
        headers: {
          Authorization: 'Bearer ' + localStorage.getItem('token')
        }
      }
      uri = '/api/sparepartCabang'
      this.editData.id_cabang = this.$route.params.idCabang
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
    UpdateData () {
      if (!this.$refs.form.validate()) {
        this.snackbar = true
        this.text = 'Mohon untuk melengkapi form yang tersedia'
        this.color = 'red'
        return
      }
      if (parseInt(this.editData.total) + parseInt(this.editData.stok) <= this.editData.limitStok) {
        this.snackbar = true
        this.text = 'Mohon maaf, barang yang dipesan masih di bawah stok minimal'
        this.color = 'red'
        return
      }
      this.loading = true
      var uri
      var config = {
        headers: {
          Authorization: 'Bearer ' + localStorage.getItem('token')
        }
      }
      this.editData.id_supplier = this.$route.params.idSupplier
      this.editData.id_cabang = this.$route.params.idCabang
      this.editData.kode_sparepart = this.editData.sparepart.kode
      uri = '/api/pemesanan'
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
      this.editDialog = false
      this.deleteDialog = false
      var uri
      var config = {
        headers: {
          Authorization: 'Bearer ' + localStorage.getItem('token')
        }
      }
      uri = '/api/sparepartBS/' + this.$route.params.idSupplier + '/' + this.$route.params.idCabang
      axios.get(uri, config).then(response => {
        this.cabangSpareprats = response.data
        for (let i = 0; i < response.data.length; i++) {
          if (response.data[i].total > 0) {
            this.pemesanan = true
            break
          } else {
            this.pemesanan = false
          }
        }
        this.loadData = false
      })
    }
  }
}
</script>
