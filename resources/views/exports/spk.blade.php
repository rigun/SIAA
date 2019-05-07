<html>
    <head>
        <title>Judul</title>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <table>
                    <tr>
                        <td>
                            <div class="titleHeader" style="margin-left: 20px;">
                                <h1 style="text-align: center;margin: 0px">ATMA AUTO</h1>
                                <p style="text-align: center;margin: 0px">MOTORCYCLE SPAREPART AND SERVICES <br> Jl.Babarsari No. 43 Yogyakarta 552181 <br> Telp. (0274) 487711 <br>http://wwww.atmaauto.com</p>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
            <hr>
            <h3 style="text-align:center">SURAT PERINTAH KERJA</h3>
            <hr>
            <div class="detailTransaksiUser">
            <?php $date=date_create($Transaksi->created_at);
                $tD = date_format($date,"d-m-y H:i"); ?>
                <p style="text-align:right">{{$tD}}</p>
                <h2>{{$Transaksi->nomor_transaksi}}-{{$Transaksi->id}}</h2>
                        <table>
                            <tr>
                                <td>Cust</td>
                                <td style="width: 200px">{{$Transaksi->namaKonsumen}}</td>
                                <td>CS</td>
                                <td>{{$Transaksi->cs->nama}}</td>
                            </tr>
                            <tr>
                                <td>Telepon</td>
                                <td style="width: 200px">{{$Transaksi->nomorKonsumen}}</td>
                                <td>Montir</td>
                                <td>{{$detailTransaksi->montir->nama}}</td>
                            </tr>
                            <tr>
                                <td>Motor</td>
                                <td style="width: 200px">{{$detailTransaksi->kendaraan->merk}} {{$detailTransaksi->kendaraan->tipe}} {{$detailTransaksi->licensePlate}}</td>
                            </tr>
                        </table>
            </div>
            <hr>
            @if ($sparepart != null)
            <h3 style="text-align:center">SPAREPARTS</h3>
            <hr>
            <table class="dataTable">
                <tr class="rowTable">
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Merk</th>
                    <th>Rak</th>
                    <th style="text-align: right">Jumlah</th>
                </tr>
                <?php $i=0 ?>
                @foreach($sparepart as $dtsp)
                <tr class="rowTable">
                    <td>{{$dtsp['data']->kode_sparepart}}</td>
                    <td>{{$dtsp['data']->sparepart->nama}}</td>
                    <td>{{$dtsp['data']->sparepart->merk}}</td>
                    <td>{{$dtsp['position']}}</td>
                    <td style="text-align: right">{{$dtsp['data']->jumlah}}</td>
                </tr>
                <?php $i= $i + $dtsp['data']->jumlah ?>
                @endforeach
                <tr class="footerTableData">
                    <td colspan="5" style="text-align: right">{{$i}}</td>
                </tr>
            </table>
            <hr>
            @endif
            <h3 style="text-align:center">SERVICE</h3>
            <hr>
            <table class="dataTable">
                <tr class="rowTable">
                    <th>Kode</th>
                    <th>Nama</th>
                    <th style="text-align: right">Jumlah</th>
                </tr>
                <?php $j=0 ?>
                @foreach($service as $dtsv)
                <tr class="rowTable">
                    <td>{{$dtsv['layanan']->id}}</td>
                    <td>{{$dtsv['layanan']->jenis}}</td>
                    <td style="text-align: right">{{$dtsv->jumlah}}</td>
                </tr>
                <?php $j= $j + $dtsv->jumlah ?>
                @endforeach
                <tr class="footerTableData">
                    <td colspan="3" style="text-align: right">{{$j}}</td>
                </tr>
            </table>
        </div>

    </body>
    <style>
        h3{
            margin:0px;
        }
        .dataTable{
            width: 100%;
            border-top: double;
            border-bottom: double;
            border-spacing: 0px;
        }
        .rowTable th{
            border-bottom: solid;
            text-align: left;
            padding: 8px 0px;
        }
        .rowTable td{
            padding: 8px 0px;
        }
        .footerTableData td{
            border-top: double;
            padding: 8px 0px;
        }
        .container{
            margin: auto;
            max-width: 600px;
            width: 100%;
        }
        .columns{
            display: flex
        }
        .column{
            width: 50%;
        }
    </style>
</html>