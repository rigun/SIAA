<template id="admin-list">
<div class="contentlist">
<div class="flex-container" >
      <div class="columns m-t-10">
        <div class="column">
          <h1 class="title">Detail Transaksi <br> Kendaraan Konsumen: <br> {{myVehicle.kendaraan.merk}} - {{myVehicle.kendaraan.tipe}} <br> {{myVehicle.plat_nomor}}</h1>
        </div>
        <div class="column">
            <v-flex sm6 d-flex style="margin-left: auto">
              <v-btn slot="activator" color="blue lighten-2" dark @click.prevent="dialogSPK = true" v-if="$route.params.transactionType != 'SP'">Cetak SPK</v-btn>
            </v-flex>
        </div>
      </div>
      <div v-if="$route.params.transactionType != 'SV'">

      <div class="columns m-t-10">
        <div class="column">
          <h1 class="title">Sparepart</h1>
        </div>
        <div class="column">
            <v-flex sm6 d-flex style="margin-left: auto">
              <v-btn slot="activator" color="green" dark @click.prevent="editDialogSparepart = true; typeInputSparepart = 'new'">Tambahkan Sparepart</v-btn>
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
           </nav>
        <b-table :data="detailTransactionsSparepartList" :paginated="true" :per-page="perPage" :current-page.sync="currentPage" :loading="loadDataSparepart" :pagination-simple="true" :narrowed="true" :mobile-cards="true" :striped="true" :hoverable="true">
            <template slot-scope="props">
                <b-table-column label="No." sortable>{{ props.index + 1 }}</b-table-column>
                <b-table-column field="kode" label="Kode" sortable >{{props.row.data.sparepart.kode}}</b-table-column>
                <b-table-column field="nama" label="Nama" sortable >{{props.row.data.sparepart.nama}}</b-table-column>
                <b-table-column field="merk" label="Merk" sortable >{{props.row.data.sparepart.merk}}</b-table-column>
                <b-table-column field="position" label="Rak" sortable >{{props.row.position}}</b-table-column>
                <b-table-column field="jumlah" label="Jumlah" sortable >{{props.row.data.jumlah}}</b-table-column>
                            <b-table-column label=""><v-menu transition="slide-x-transition" offset-x left>
                                <v-btn slot="activator" icon >
                                <v-icon>more_vert</v-icon>
                                </v-btn>
                            <v-list>
                            <v-list-tile  @click.prevent="seteditDataSparepart(props.row); editDialogSparepart = true;typeInputSparepart = 'edit'">
                                <v-list-tile-title  >Perbaharui</v-list-tile-title>
                            </v-list-tile>
                            <v-list-tile @click.prevent="deleteId = props.row.data.id; deleteDialog = true; itemPart = 'sparepart'">
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
                        <p>Belum ada data sparepart, silahkan tambahkan sparepart terlebih dahulu</p>
                    </div>
                </section>
            </template>
        </b-table>
      </div>
      <div v-if="$route.params.transactionType != 'SP'"> 
        <div class="columns m-t-10">
        <div class="column">
          <h1 class="title">Layanan</h1>
        </div>
        <div class="column">
            <v-flex sm6 d-flex style="margin-left: auto">
              <v-btn slot="activator" color="green" dark @click.prevent="editDialogLayanan = true; typeInputLayanan = 'new'">Tambahkan Layanan</v-btn>
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

           </nav>
        <b-table :data="detailTransactionsLayananList" :paginated="true" :per-page="perPage" :current-page.sync="currentPage" :loading="loadDataLayanan" :pagination-simple="true" :narrowed="true" :mobile-cards="true" :striped="true" :hoverable="true">
            <template slot-scope="props">
                <b-table-column label="No." sortable>{{ props.index + 1 }}</b-table-column>
                <b-table-column field="id" label="Kode" sortable >{{props.row.layanan.id}}</b-table-column>
                <b-table-column field="nama" label="Nama" sortable >{{props.row.layanan.jenis}}</b-table-column>
                <b-table-column field="jumlah" label="Jumlah" sortable >{{props.row.jumlah}}</b-table-column>
                            <b-table-column label=""><v-menu transition="slide-x-transition" offset-x left>
                                <v-btn slot="activator" icon >
                                <v-icon>more_vert</v-icon>
                                </v-btn>
                            <v-list>
                            <v-list-tile  @click.prevent="seteditDataLayanan(props.row); editDialogLayanan = true;typeInputLayanan = 'edit'">
                                <v-list-tile-title  >Perbaharui</v-list-tile-title>
                            </v-list-tile>
                            <v-list-tile @click.prevent="deleteId = props.row.id; deleteDialog = true; itemPart = 'layanan'">
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
                        <p>Belum ada data layanan, silahkan tambahkan layanan terlebih dahulu</p>
                    </div>
                </section>
            </template>
        </b-table>
      </div>
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
                            <v-dialog v-model="editDialogSparepart" persistent max-width="600px">
                                <v-form
                                    ref="form1"
                                    v-model="valid"
                                    lazy-validation
                                >
                              <v-card>
                                <v-card-title>
                                    Sparepart yang dibeli
                                </v-card-title>
                                <v-card-text>
                                                                            <v-flex xs12>
                                         <v-autocomplete
                                                :items="spareparts"
                                                :filter="sparepartFilter"
                                                item-text="view"
                                                label="Sparepart yang tersedia dicabang ini (kode sparepart)"
                                                :rules="[rules.required]"
                                                v-model="editData.sparepart"
                                                return-object
                                                :error-messages="errorSparepart"
                                                @input="countHarga();cekSparepartCode()"
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
                                        <v-text-field label="Harga Sparepart*" v-model="editData.sparepart.hargaJual" :disabled="true"></v-text-field>
                                      </v-flex>
                                       <v-flex xs12>
                                        <v-text-field label="stok yang tersedia*" v-model="editData.sparepart.stok" :disabled="true"></v-text-field>
                                      </v-flex>
                                       <v-flex xs12>
                                        <v-text-field label="Jumlah Sparepart yang dibeli*" required v-model="editData.jumlah" :rules="[rules.required, rules.numberOnly]" @input="countHarga()"></v-text-field>
                                      </v-flex>
                                       <v-flex xs12>
                                        <v-text-field label="Subtotal*" :value="subtotal" :disabled="true"></v-text-field>
                                      </v-flex>
                                  <small>*Wajib diisi</small>
                                </v-card-text>
                                <v-card-actions>
                                  <v-spacer></v-spacer>
                                  <v-btn color="red darken-1" dark @click.prevent="editDialogSparepart = false; resetData('sparepart')">Batal</v-btn>
                                  <v-btn color="green darken-1" dark :loading="load" v-if="typeInputSparepart == 'new'" @click.prevent="SendData('sparepart')">Tambahkan</v-btn>
                                  <v-btn color="orange darken-1" dark :loading="load" v-if="typeInputSparepart == 'edit'" @click.prevent="UpdateData('sparepart')">Perbaharui</v-btn>
                                </v-card-actions>
                              </v-card>
                                </v-form>
                            </v-dialog>
                          </v-layout>
        <!-- edit -->
        <!-- edit -->
         <v-layout row justify-center>
                            <v-dialog v-model="editDialogLayanan" persistent max-width="600px">
                                <v-form
                                    ref="form2"
                                    v-model="valid"
                                    lazy-validation
                                >
                              <v-card>
                                <v-card-title>
                                    Layanan yang diinginkan
                                </v-card-title>
                                <v-card-text>
                                                                            <v-flex xs12>
                                         <v-autocomplete
                                                :items="layanans"
                                                :filter="layananFilter"
                                                item-text="view"
                                                label="Layanan yang tersedia"
                                                :rules="[rules.required]"
                                                v-model="editData.layanan"
                                                return-object
                                                :error-messages="errorLayanan"
                                                @input="countHargaLayanan();cekLayanan()"
                                          ></v-autocomplete>
                                      </v-flex>
                                       <v-flex xs12>
                                        <v-text-field label="Jumlah layanan yang dilakukan*" required v-model="editData.jumlah" :rules="[rules.required, rules.numberOnly]" @input="countHargaLayanan()"></v-text-field>
                                      </v-flex>
                                       <v-flex xs12>
                                        <v-text-field label="Subtotal*" :value="subtotalLayanan" :disabled="true"></v-text-field>
                                      </v-flex>
                                  <small>*Wajib diisi</small>
                                </v-card-text>
                                <v-card-actions>
                                  <v-spacer></v-spacer>
                                  <v-btn color="red darken-1" dark @click.prevent="editDialogLayanan = false; resetData('layanan')">Batal</v-btn>
                                  <v-btn color="green darken-1" dark :loading="load" v-if="typeInputLayanan == 'new'" @click.prevent="SendData('layanan')">Tambahkan</v-btn>
                                  <v-btn color="orange darken-1" dark :loading="load" v-if="typeInputLayanan == 'edit'" @click.prevent="UpdateData('itemPartlayanan')">Perbaharui</v-btn>
                                </v-card-actions>
                              </v-card>
                                </v-form>
                            </v-dialog>
                          </v-layout>
        <!-- edit -->
        <!-- edit -->
         <v-layout row justify-center>
                            <v-dialog v-model="dialogSPK" persistent max-width="600px">
                                <v-form
                                    ref="form"
                                    v-model="valid"
                                    lazy-validation
                                >
                              <v-card>
                                <v-card-title>
                                    Montir yang ditugaskan
                                </v-card-title>
                                <v-card-text>
                                        <v-flex xs12>
                                         <v-autocomplete
                                                :items="montir"
                                                :filter="montirFilter"
                                                item-text="nama"
                                                label="Nama Montir"
                                                :rules="[rules.required]"
                                                v-model="editData.montir"
                                                return-object
                                          ></v-autocomplete>
                                      </v-flex>
                                  <small>*Wajib diisi</small>
                                </v-card-text>
                                <v-card-actions>
                                  <v-spacer></v-spacer>
                                  <v-btn color="red darken-1" dark @click.prevent="dialogSPK = false;">Batal</v-btn>
                                  <v-btn color="green darken-1" dark :loading="load" @click.prevent="cetakSPK()">Cetak</v-btn>
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
                      <v-card-title class="headline">Hapus Data Ini ?</v-card-title>
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
    this.getData('sparepart')
    this.getData('layanan')
    this.getVehicle()
    this.getSparepart()
    this.getLayanan()
    this.getMontir()
  },
  data () {
    return {
      montir: [],
      dialogSPK: false,
      itemPart: 'sparepart',
      layanans: [],
      errorLayanan: '',
      typeInputLayanan: 'new',
      detailTransactionsLayanan: [],
      editDialogLayanan: false,
      editDataLayanan: {
        layanan: {
          nama: '',
          kode: '',
          merk: '',
          tipe: ''
        },
        jumlah: 1
      },
      subtotalLayanan: 0,
      spareparts: [],
      myVehicle: {
        plat_nomor: '',
        kendaraan: {
          merk: '',
          tipe: ''
        }
      },
      errorSparepart: '',
      customer: {},
      load: false,
      valid: true,
      typeInputSparepart: 'new',
      search: '',
      detailTransactionsSparepart: [],
      dialog: false,
      deleteDialog: false,
      editDialogSparepart: false,
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
        jumlah: 1
      },
      branchSpareprats: [],
      deleteId: -1,
      loading: false,
      loadDataSparepart: true,
      loadDataLayanan: true,
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
      },
      subtotal: 0
    }
  },
  computed: {
    detailTransactionsSparepartList () {
      if (this.detailTransactionsSparepart.length) {
        return this.detailTransactionsSparepart.filter((row, index) => {
          if (this.search !== '') return row.detail.nama.toLowerCase().includes(this.search.toLowerCase()) || row.detail.user.email.toLowerCase().includes(this.search.toLowerCase()) || row.detail.phoneNumber.toLowerCase().includes(this.search.toLowerCase())
          return true
        })
      }
    },
    detailTransactionsLayananList () {
      if (this.detailTransactionsLayanan.length) {
        return this.detailTransactionsLayanan.filter((row, index) => {
          if (this.search !== '') return row.detail.nama.toLowerCase().includes(this.search.toLowerCase()) || row.detail.user.email.toLowerCase().includes(this.search.toLowerCase()) || row.detail.phoneNumber.toLowerCase().includes(this.search.toLowerCase())
          return true
        })
      }
    }
  },
  methods: {
    cetakSPK () {
      if (!this.$refs.form.validate()) {
        this.snackbar = true
        this.text = 'Mohon untuk melengkapi form yang tersedia'
        this.color = 'error'
        return
      }
      this.editData.montir_id = this.editData.montir.id
      this.load = true
      var config = {
        responseType: 'blob',
        headers: {
          Authorization: 'Bearer ' + localStorage.getItem('token')
        }
      }
      var uri = '/api/spk/' + this.$route.params.idTransaksi + '/' + this.$route.params.idDetail
      axios.post(uri, this.editData, config).then(response => {
        const url = window.URL.createObjectURL(new Blob([response.data]))
        const link = document.createElement('a')
        link.href = url
        link.setAttribute('download', 'spk_' + this.$route.params.TransaksiType + '-' + this.$route.params.nomor_transaksi + '-' + this.$route.params.idTransaksi + '.pdf')
        document.body.appendChild(link)
        link.click()
        this.load = false
        this.dialogSPK = false
      }).catch(error => {
        console.log(error.response)
        this.load = false
        this.snackbar = true
        this.color = 'error'
        this.text = 'Coba lagi'
      })
    },
    getMontir () {
      var uri
      var config = {
        headers: {
          Authorization: 'Bearer ' + localStorage.getItem('token')
        }
      }
      uri = '/api/montir/' + localStorage.getItem('idCabang')
      axios.get(uri, config).then(response => {
        for (let i = 0; i < response.data.length; i++) {
          this.montir.push(response.data[i])
        }
      })
    },
    cekSparepartCode () {
      var sparepart = this.detailTransactionsSparepart.filter((row, index) => {
        if (row.data.sparepart.kode === this.editData.sparepart.kode) {
          return true
        }
      })
      if (sparepart.length > 0) {
        this.errorSparepart = 'Mohon maaf, sparepart ini sudah dipilih.'
      } else {
        this.errorSparepart = ''
      }
    },
    cekLayanan () {
      var layanan = this.detailTransactionsLayanan.filter((row, index) => {
        if (row.layanan.nama === this.editData.layanan.jenis) {
          return true
        }
      })
      if (layanan.length > 0) {
        this.errorLayanan = 'Mohon maaf, layanan ini sudah dipilih.'
      } else {
        this.errorLayanan = ''
      }
    },
    countHarga () {
      this.subtotal = this.harga(this.editData.sparepart.harga * this.editData.jumlah)
    },
    countHargaLayanan () {
      this.subtotalLayanan = this.harga(this.editData.layanan.hargaJasa * this.editData.jumlah)
    },
    harga (value) {
      const formatter = new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 2
      })
      return formatter.format(value)
    },
    getSparepart () {
      var uri
      var config = {
        headers: {
          Authorization: 'Bearer ' + localStorage.getItem('token')
        }
      }
      uri = '/api/sparepartCabang/' + localStorage.getItem('idCabang')
      axios.get(uri, config).then(response => {
        this.branchSpareprats = response.data
        for (let i = 0; i < this.branchSpareprats.length; i++) {
          this.spareparts.push(this.branchSpareprats[i].sparepart)
          this.spareparts[i].view = this.spareparts[i].kode + ' ' + this.spareparts[i].nama
          this.spareparts[i].stok = this.branchSpareprats[i].stok
          this.spareparts[i].harga = this.branchSpareprats[i].hargaJual
          this.spareparts[i].hargaJual = this.harga(this.branchSpareprats[i].hargaJual)
        }
      })
    },
    getLayanan () {
      var uri
      var config = {
        headers: {
          Authorization: 'Bearer ' + localStorage.getItem('token')
        }
      }
      uri = '/api/layanan'
      axios.get(uri, config).then(response => {
        this.layanans = response.data
        for (let i = 0; i < this.layanans.length; i++) {
          this.layanans[i].view = this.layanans[i].jenis + ' ' + this.harga(this.layanans[i].hargaJasa)
        }
      })
    },
    sparepartFilter (item, queryText, itemText) {
      const textOne = item.kode.toLowerCase()
      const searchText = queryText.toLowerCase()
      return textOne.indexOf(searchText) > -1
    },
    layananFilter (item, queryText, itemText) {
      const textOne = item.nama.toLowerCase()
      const searchText = queryText.toLowerCase()
      return textOne.indexOf(searchText) > -1
    },
    montirFilter (item, queryText, itemText) {
      const textOne = item.nama.toLowerCase()
      const searchText = queryText.toLowerCase()
      return textOne.indexOf(searchText) > -1
    },
    gotoRoute (transactionNumber, id) {
      this.$router.push({name: 'detailTransaction', params: {trasactionNumber: transactionNumber, idTransaction: id}})
    },
    getVehicle () {
      var uri
      var config = {
        headers: {
          Authorization: 'Bearer ' + localStorage.getItem('token')
        }
      }
      uri = '/api/myvehicle/' + this.$route.params.idDetail
      axios.get(uri, config).then(response => {
        this.myVehicle = response.data
      })
    },
    seteditDataSparepart (data) {
      this.editData.id = data.data.id
      this.editData.sparepart = data.data.sparepart
      this.editData.sparepart.view = this.editData.sparepart.kode + ' ' + this.editData.sparepart.nama
      this.editData.sparepart.harga = data.data.harga
      this.editData.sparepart.stok = data.stok
      this.editData.jumlah = data.data.jumlah
      this.editData.sparepart.hargaJual = this.harga(data.data.harga)
      this.countHarga()
    },
    seteditDataLayanan (data) {
      this.editData.id = data.id
      this.editData.layanan = data.layanan
      this.editData.layanan.view = this.editData.layanan.jenis + ' ' + this.harga(this.editData.layanan.hargaJasa)
      this.editData.jumlah = data.jumlah
      this.countHargaLayanan()
    },
    resetData (itemPart) {
      this.errorLayanan = ''
      this.errorSparepart = ''
      if (itemPart === 'sparepart') {
        this.editData.sparepart = {
          nama: '',
          kode: '',
          merk: '',
          tipe: ''
        }
        this.typeInputSparepart = 'new'
      } else {
        this.editData.layanan = {
          nama: '',
          harga: 0
        }
        this.typeInputLayanan = 'new'
      }
      this.load = false
    },
    deleteData () {
      this.load = true
      var config = {
        headers: {
          Authorization: 'Bearer ' + localStorage.getItem('token')
        }
      }
      var uri
      if (this.itemPart === 'sparepart') {
        uri = '/api/detailTSp/' + this.deleteId
      } else {
        uri = '/api/detailTSv/' + this.deleteId
      }
      axios.delete(uri, config).then(response => {
        this.snackbar = true
        this.text = 'Data berhasil dihapus'
        this.color = 'green'
        this.getData(this.itemPart)
      }).catch(error => {
        console.log(error)
        this.snackbar = true
        this.text = 'Coba Lagi'
        this.color = 'red'
        this.load = false
      })
    },
    SendData (itemPart) {
      if (!this.$refs.form1.validate() && itemPart === 'sparepart') {
        this.snackbar = true
        this.text = 'Mohon untuk melengkapi form yang tersedia'
        this.color = 'error'
        return
      }
      if (!this.$refs.form2.validate() && itemPart === 'layanan') {
        this.snackbar = true
        this.text = 'Mohon untuk melengkapi form yang tersedia'
        this.color = 'error'
        return
      }
      if (this.errorSparepart !== '' || this.errorLayanan !== '') {
        this.snackbar = true
        this.text = 'Mohon untuk melengkapi form yang tersedia'
        this.color = 'red'
        return
      }
      if (this.editData.sparepart.stok < this.editData.jumlah) {
        this.snackbar = true
        this.text = 'Stok tidak mencukupi'
        this.color = 'red'
        return
      }
      this.editData.idCabang = localStorage.getItem('idCabang')
      this.editData.id_detailtransaksi = this.$route.params.idDetail
      this.load = true
      var config = {
        headers: {
          Authorization: 'Bearer ' + localStorage.getItem('token')
        }
      }
      var uri
      if (itemPart === 'sparepart') {
        uri = '/api/detailTSp'
        this.editData.kode_sparepart = this.editData.sparepart.kode
      } else {
        uri = '/api/detailTSv'
        this.editData.id_service = this.editData.layanan.id
      }
      axios.post(uri, this.editData, config).then(response => {
        this.resetData(itemPart)
        this.getData(itemPart)
      }).catch(error => {
        console.log(error.response)
        this.load = false
        this.snackbar = true
        this.color = 'error'
        this.text = 'Coba lagi'
      })
    },
    UpdateData (itemPart) {
      var uri
      var config = {
        headers: {
          Authorization: 'Bearer ' + localStorage.getItem('token')
        }
      }
      if (this.errorSparepart !== '' || this.errorLayanan !== '') {
        this.snackbar = true
        this.text = 'Mohon untuk melengkapi form yang tersedia'
        this.color = 'red'
        return
      }
      if (this.editData.sparepart.stok < this.editData.jumlah) {
        this.snackbar = true
        this.text = 'Stok tidak mencukupi'
        this.color = 'red'
        return
      }
      if (itemPart === 'sparepart') {
        this.editData.kode_sparepart = this.editData.sparepart.kode
        uri = '/api/detailTSp/' + this.editData.id
      } else {
        this.editData.id_service = this.editData.layanan.id
        uri = '/api/detailTSv/' + this.editData.id
      }
      this.load = true
      axios.post(uri, this.editData, config).then(response => {
        console.log(response)
        this.getData(itemPart)
        this.resetData(itemPart)
      }).catch(error => {
        console.log(error)
        this.snackbar = true
        this.text = 'Coba Lagi'
        this.color = 'red'
        this.load = false
      })
    },
    getData (itemPart) {
      this.editDialogSparepart = false
      this.editDialogLayanan = false
      this.deleteDialog = false
      this.load = false
      var config = {
        headers: {
          Authorization: 'Bearer ' + localStorage.getItem('token')
        }
      }
      var uri
      if (itemPart === 'sparepart') {
        uri = '/api/detailTSp/' + this.$route.params.idDetail + '/' + localStorage.getItem('idCabang')
      } else {
        uri = '/api/detailTSv/' + this.$route.params.idDetail
      }
      axios.get(uri, config).then(response => {
        if (itemPart === 'sparepart') {
          this.detailTransactionsSparepart = response.data
          this.loadDataSparepart = false
        } else {
          this.detailTransactionsLayanan = response.data
          this.loadDataLayanan = false
        }
      })
    }
  }
}
</script>

