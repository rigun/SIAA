<template id="admin-list">
<div class="contentlist">
<div class="flex-container" >
      <div class="columns m-t-10">
        <div class="column">
          <h1 class="title">Daftar Supplier</h1>
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
        <b-table :data="supplierList" :paginated="true" :per-page="perPage" :current-page.sync="currentPage" :loading="loadData" :pagination-simple="true" :narrowed="true" :mobile-cards="true" :striped="true" :hoverable="true" :default-sort-direction="defaultSortDirection" default-sort="created_at">
            <template slot-scope="props">
                <b-table-column label="No." sortable>{{ props.index + 1 }}</b-table-column>
                <b-table-column field="nama" label="Nama" sortable>{{ props.row.nama }}</b-table-column>
                <b-table-column field="no_telp" label="Nomor Telepon" sortable>{{ props.row.no_telp }}</b-table-column>
                <b-table-column field="alamat" label="Alamat" sortable >{{ props.row.alamat }}</b-table-column>
                  <b-table-column label="Pengaturan" :visible="!loadData">
                    <v-btn color="green lighten-2" dark @click="goto(props.row.id)">List Sparepart</v-btn>
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
                        <p>Belum ada data supplier, silahkan tambahkan supplier terlebih dahulu</p>
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
      search: '',
      supplier: [],
      load: false,
      snackbar: false,
      text: '',
      color: null,
      reset: false,
      editData: {
        nama: '',
        role: 'supplier',
        no_telp: '',
        alamat: ''
      },
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
    supplierList () {
      if (this.supplier.length > 0) {
        return this.supplier.filter((row, index) => {
          if (this.search !== '') return row.nama.toLowerCase().includes(this.search.toLowerCase()) || row.no_telp.toLowerCase().includes(this.search.toLowerCase())
          return true
        })
      }
    }
  },
  methods: {
    goto (id) {
      this.$router.push({name: 'SparepartSupplierCabang', params: {idCabang: this.$route.params.idCabang, idSupplier: id, }})
    },
    getData () {
      var uri
      var config = {
        headers: {
          Authorization: 'Bearer ' + localStorage.getItem('token')
        }
      }
      uri = '/api/supplierCabang/' + this.$route.params.idCabang
      axios.get(uri, config).then(response => {
        this.supplier = response.data
        this.loadData = false
      })
    }
  }
}
</script>
