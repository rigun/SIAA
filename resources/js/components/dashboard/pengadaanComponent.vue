<template id="admin-list">
<div class="contentlist">
<div class="flex-container" >

      <div class="columns m-t-10">
        <div class="column">
          <h1 class="title">Pengadaan Sparepart</h1>
        </div>
        <div class="column">
            <v-flex sm6 d-flex style="margin-left: auto">
                <v-btn slot="activator" color="blue lighten-2" dark @click.prevent="gotoRoute('SupplierCabang')">Pemesanan Baru</v-btn>
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
        <b-table :data="pemesanansList" :paginated="true" :per-page="perPage" :current-page.sync="currentPage" :loading="loadData" :pagination-simple="true" :narrowed="true" :mobile-cards="true" :striped="true" :hoverable="true" :default-sort-direction="defaultSortDirection" default-sort="created_at"
                    detailed
            detail-key="id"
            :show-detail-icon="true"
            >
            <template slot-scope="props">
                <b-table-column label="No." sortable>{{ props.index + 1 }}</b-table-column>
                <b-table-column field="supplier.nama" label="Nama Supplier" sortable >{{ props.row.supplier.nama}}</b-table-column>
                <b-table-column field="supplier.no_telp" label="Nomor Telepon Supplier" sortable >{{ props.row.supplier.no_telp}}</b-table-column>
                <b-table-column field="supplier.address" label="Alamat Supplier" sortable >{{ props.row.supplier.alamat}}</b-table-column>
                <b-table-column field="supplier.address" label="Sales yang bertugas" sortable ><span v-if="props.row.sales != null">{{props.row.sales.nama}}</span></b-table-column>
                <b-table-column label="Dipesan pada" sortable >{{props.row.created_at }}</b-table-column>
                <b-table-column label="Status" centered >
                  <span v-if="props.row.status == 0" style="color:indigo">Pengisian Data </span>
                  <span v-if="props.row.status == 1" style="color:orange">Sedang dipesan </span>
                  <span v-if="props.row.status == 2" style="color:green">Pesanan dikonfirmasi </span>
                </b-table-column>
                <b-table-column label="Pengaturan" centered >
                  <v-btn color="green" small dark @click.prevent="goto(props.row.id)" v-if="props.row.status == 2">Detail</v-btn>
                  <v-btn color="orange" small dark @click.prevent="goto(props.row.id)" v-if="props.row.status == 1">Konfirmasi</v-btn>
                  <v-btn color="indigo" small dark @click.prevent="gotoUpdate(props.row.supplier.id)" v-if="props.row.status == 0">Perbaharui</v-btn>
                  </b-table-column>
            </template>
            <template slot="detail" slot-scope="props">
                    <div class="columns" style="bpemesanan-bottom: solid 1px black">
                      <div class="column">Kode Sparepart</div>
                      <div class="column">Nama Sparepart</div>
                      <div class="column">Total Pesan</div>
                      <div class="column">Satuan Pemesanan</div>
                    </div>
                    <div class="columns" v-for="dt in props.row.detail" :key="dt.id" style="bpemesanan-bottom: solid 1px black">
                      <div class="column">{{dt.data.sparepart.kode}}</div>
                      <div class="column">{{dt.data.sparepart.nama}}</div>
                      <div class="column">{{dt.total}}</div>
                      <div class="column">{{dt.satuan}}</div>
                    </div>
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
                        <p>Belum ada data pemesanan</p>
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
</div>
</div>
</template>

<script>
export default {
  mounted () {
    this.$parent.tab = 'branchD5'
    this.getData()
  },
  data () {
    return {
      load: false,
      search: '',
      pemesanans: [],
      snackbar: false,
      text: '',
      color: null,
      deleteId: -1,
      loading: false,
      loadData: true,
      isPaginated: true,
      isPaginationSimple: true,
      defaultSortDirection: 'desc',
      currentPage: 1,
      perPage: 10
    }
  },
  computed: {
    pemesanansList () {
      if (this.pemesanans.length) {
        return this.pemesanans.filter((row, index) => {
          if (this.search !== '') return row.supplier.nama.toLowerCase().includes(this.search.toLowerCase())
          return true
        })
      }
    }
  },
  methods: {
    gotoRoute (nama) {
      this.$router.push({name: nama, params: {idCabang: this.$route.params.idCabang}})
    },
    goto (id) {
      this.$router.push({name: 'KonfirmasiPengadaan', params: {idPemesanan: id}})
    },
    gotoUpdate (id) {
      this.$router.push({name: 'SparepartSupplierCabang', params: {idSupplier: id}})
    },
    getData () {
      this.load = false
      var config = {
        headers: {
          Authorization: 'Bearer ' + localStorage.getItem('token')
        }
      }
      var uri = '/api/pemesanan/' + this.$route.params.idCabang
      axios.get(uri, config).then(response => {
        this.pemesanans = response.data
        this.loadData = false
      })
    }
  }
}
</script>
