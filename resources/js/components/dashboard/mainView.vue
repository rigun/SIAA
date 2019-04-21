<template>
  <div class="contentlist">
    <div class="columns is-multiline">
      <div class="column is-full">
        <div class="card">
          <div class="card-content">
            <div class="columns">
              <div class="column">
                <p><strong>Layanan</strong></p>
              </div>
              <div class="column" style="text-align:right">
                <v-btn slot="activator" small  dark color="indigo" @click.prevent="inputType = 'new'; inputCat = 'layanan'; dialog = true">
                    Tambah
                </v-btn>
              </div>
            </div>
            <div class="limitBox">

              <div class="outsideBox" v-for="layanan in layanans" :key="layanan.id" style="border:solid 1px black; padding: 10px">
                  <div class="box-ticket" style="display:flex" >
                      <p style="color: #888888">Jenis <br> <strong>{{layanan.jenis}}</strong></p>
                      <p style="margin-left: auto;margin-right: 10px; text-align: right;color: #888888">Harga Jasa <br> <strong>{{convertPrice(layanan.hargaJasa)}}</strong></p>
                  </div>
                  <p class="editTool" style="text-align: right;">
                      <v-btn class="editButton" small dark color="orange" @click.prevent=" dialog = true; inputType='edit';inputCat = 'layanan';setEditData(layanan);">edit</v-btn>
                      <v-btn class="deleteButton" small dark color="red" @click.prevent=" deleteDialog = true; inputCat = 'layanan';setEditData(layanan);">delete</v-btn>
                  </p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="column is-full">
        <div class="card">
          <div class="card-content">
            <div class="columns">
              <div class="column">
                <p><strong>Kendaraan</strong></p>
              </div>
              <div class="column" style="text-align:right">
                <v-btn slot="activator" small  dark color="indigo" @click.prevent="inputType = 'new'; inputCat = 'kendaraan'; dialog = true">
                    Tambah
                </v-btn>
              </div>
            </div>
            <div class="limitBox">
              <div class="outsideBox" v-for="kendaraan in vehicles" :key="kendaraan.id" style="border:solid 1px black">
                  <div class="box-ticket" style="display:flex" >
                      <p style="color: #888888">Merk<br> <strong>{{kendaraan.merk}}</strong></p>
                      <p style="margin-left: auto;margin-right: 10px; text-align: right;color: #888888">Tipe <br> <strong>{{kendaraan.tipe}}</strong></p>
                  </div>
                  <p class="editTool" style="text-align: right;">
                      <v-btn class="editButton" small dark color="orange" @click.prevent="dialog = true; inputType='edit';inputCat = 'kendaraan';setEditData(kendaraan);">edit</v-btn>
                      <v-btn class="deleteButton" small dark color="red" @click.prevent="deleteDialog = true; inputCat = 'kendaraan';setEditData(kendaraan);">delete</v-btn>
                  </p>
              </div>
            </div>
          </div>
        </div>
      </div>
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
         <v-layout row justify-center>
            <v-dialog v-model="dialog" persistent max-width="600px">
                <v-form
                    ref="form"
                    v-model="valid"
                    lazy-validation
                >
              <v-card>
                <v-card-title>
                    Form <span v-if="inputCat == 'layanan'">Layanan</span> <span v-else>Kendaraan</span>
                </v-card-title>
                <v-card-text>
                      <v-flex xs12>
                        <v-text-field label="Merk Kendaraan*" v-if="inputCat == 'kendaraan'" required v-model="editData.merk" :rules="[rules.required, rules.textOnly]"></v-text-field>
                      </v-flex>
                        <v-flex xs12>
                        <v-text-field label="Tipe Kendaraan*" v-if="inputCat == 'kendaraan'" required v-model="editData.tipe" :rules="[rules.required]"></v-text-field>
                      </v-flex>
                        <v-flex xs12>
                        <v-text-field label="Nama Jasa Service*" v-if="inputCat == 'layanan'" required v-model="editData.jenis" :rules="[rules.required, rules.textOnly]"></v-text-field>
                      </v-flex>
                        <v-flex xs12>
                        <v-text-field label="Biaya Jasa Service*" v-if="inputCat == 'layanan'" tipe="number" required v-model="editData.hargaJasa" :rules="[rules.required, rules.numberOnly]"></v-text-field>
                      </v-flex>
                  <small>*Wajib diisi</small>
                </v-card-text>
                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn color="red darken-1" dark @click.prevent="resetData()">Batal</v-btn>
                  <v-btn color="green darken-1" dark v-if="inputType == 'new'" @click.prevent="SendData()" :loading="load">Tambahkan</v-btn>
                  <v-btn color="orange darken-1" dark v-if="inputType == 'edit'" @click.prevent="UpdateData()" :loading="load">Perbaharui</v-btn>
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
                <v-card-title class="headline">Hapus Data <span v-if="inputCat == 'layanan'">Layanan</span><span v-if="inputCat == 'kendaraan'">Kendaraan</span> ?</v-card-title>
                <v-card-text>Data yang dihapus tidak akan bisa dikembalikan lagi</v-card-text>
                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn color="green darken-1" dark @click="resetData()">Batal</v-btn>
                  <v-btn color="red darken-1" dark :loading="load" @click="deleteData()">Hapus</v-btn>
                </v-card-actions>
              </v-card>
            </v-dialog>
          </v-layout>
        <!-- delete -->
  </div>
</template>
<style scoped>
.limitBox{
  max-height: 311px;
  overflow-y: auto;
}
</style>

<script>
export default {
  mounted () {
    this.getData('layanan')
    this.getData('kendaraan')
  },
  data () {
    return {
      valid: true,
      snackbar: false,
      color: 'red',
      text: '',
      layanans: [],
      vehicles: [],
      editData: {
        id: -1,
        merk: '',
        tipe: '',
        jenis: '',
        hargaJasa: 0
      },
      load: false,
      inputType: 'new',
      inputCat: 'layanan',
      dialog: false,
      deleteDialog: false,
      rules: {
        required: value => !!value || 'Data ini tidak boleh kosong',
        numberOnly: value => !isNaN(value) || 'Data tidak valid, hanya diijinkan memasukkan angka',
        textOnly: value => RegExp(/^[A-Za-z ]+$/).test(value) || 'Data tidak valid, hanya diijinkan memasukkan huruf'
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
    setEditData (dataByCat) {
      var data = dataByCat
      this.editData.id = data.id
      if (this.inputCat === 'layanan') {
        this.editData.jenis = data.jenis
        this.editData.hargaJasa = data.hargaJasa
      } else {
        this.editData.merk = data.merk
        this.editData.tipe = data.tipe
      }
    },
    resetData () {
      this.$refs.form.reset()
      this.editData.id = -1
      this.editData.merk = ''
      this.editData.tipe = ''
      this.editData.jenis = ''
      this.editData.hargaJasa = ''
      this.inputType = 'new'
      this.inputCat = 'layanan'
      this.dialog = false
      this.deleteDialog = false
      this.load = false
    },
    SendData () {
      if (!this.$refs.form.validate()) {
        this.snackbar = true
        this.text = 'Mohon untuk melengkapi form yang tersedia'
        this.color = 'error'
        return
      }
      this.load = true
      var uri
      var config = {
        headers: {
          Authorization: 'Bearer ' + localStorage.getItem('token')
        }
      }
      uri = '/api/' + this.inputCat
      axios.post(uri, this.editData, config).then(response => {
        this.getData(this.inputCat)
        this.resetData()
      }).catch(error => {
        console.log(error.response)
        this.snackbar = true
        this.text = 'Terjadi kesalahan'
        this.color = 'error'
        this.load = false
      })
    },
    UpdateData () {
      if (!this.$refs.form.validate()) {
        this.snackbar = true
        this.text = 'Mohon untuk melengkapi form yang tersedia'
        this.color = 'error'
        return
      }
      this.load = true
      var uri
      var config = {
        headers: {
          Authorization: 'Bearer ' + localStorage.getItem('token')
        }
      }
      uri = '/api/' + this.inputCat + '/' + this.editData.id
      axios.post(uri, this.editData, config).then(response => {
        this.getData(this.inputCat)
        this.resetData()
      }).catch(error => {
        console.log(error.response)
        this.snackbar = true
        this.text = 'Terjadi kesalahan'
        this.color = 'error'
        this.load = false
      })
    },
    deleteData () {
      this.load = true
      var config = {
        headers: {
          Authorization: 'Bearer ' + localStorage.getItem('token')
        }
      }
      var uri ='/api/' + this.inputCat + '/' + this.editData.id
      axios.delete(uri, config).then(response => {
        this.snackbar = true
        this.text = 'Data berhasil dihapus'
        this.color = 'green'
        this.getData(this.inputCat)
        this.resetData()
      }).catch(error => {
        console.log(error)
        this.snackbar = true
        this.text = 'Coba Lagi'
        this.color = 'red'
        this.load = false
      })
    },
    getData (category) {
      var uri
      var config = {
        headers: {
          Authorization: 'Bearer ' + localStorage.getItem('token')
        }
      }
      uri = '/api/' + category
      axios.get(uri, config).then(response => {
        if (category === 'layanan') {
          this.layanans = response.data
        } else {
          this.vehicles = response.data
        }
      })
    }
  }
}
</script>
