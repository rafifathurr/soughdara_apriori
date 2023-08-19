<!DOCTYPE html>
<html lang="en">
@include('layouts.head')

<body>
    <div class="wrapper">
        @include('layouts.sidebar')
        <div class="main-panel">
            <div class="content">
                <div class="panel-header bg-primary-gradient">
                    <div class="page-inner py-5">
                        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                            <div>
                                <h2 class="text-white pb-2 fw-bold">{{ $title }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
                <section class="content container-fluid">
                    <section class="content container-fluid">
                        <div class="box box-primary">
                            <div class="box-body">

                                @if(isset($success))
                                    <div id="message-container">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="card" id="card-container" style="border:1px solid green;    ">
                                                    <div class="card-body justify-content-center">
                                                        <center>
                                                            <b>
                                                                <span id="message">
                                                                    Data Proccessing Successfully !
                                                                </span>
                                                            </b>
                                                            <i id="icon-message" class="fa fa-check"
                                                                style="color:green;font-size:25px;margin:10px;"></i>
                                                        </center>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="min_supp" class="col-md-12">Min Support</label>
                                        <input type="number" value={{ $min_support }} min="0" id="min_supp"
                                            name="min_support" class="form-control" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="min_conf" class="col-md-12">Min Confindence</label>
                                        <input type="number" value={{ $min_confidence }} min="0" id="min_conf"
                                            class="form-control" readonly>
                                    </div>
                                </div>
                                <hr>

                                <p class="card-title-desc">
                                <h5>Data Support Produk</h5>
                                </p>

                                <div class="table-responsive">
                                    <table class="table mb-0 table-hover" id="tblDataSupport">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Produk</th>
                                                <th>Total Transaksi</th>
                                                <th>Perhitungan Support</th>
                                                <th>Support</th>
                                                <th>Hasil</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($dataSupport as $supp)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $supp->dataProduk($supp->id_product)->product_name }}
                                                    </td>
                                                    <td>{{ $supp->total_transaksi }}</td>
                                                    <td>
                                                        ({{ $supp->total_transaksi }} /
                                                        {{ $totalProduk }})
                                                        * 100
                                                    </td>
                                                    <td>{{ $supp->support }} %</td>
                                                    <td>
                                                        @if($supp->support>=$min_support)
                                                        <span style="color:green;">Lulus</span>
                                                        @else
                                                        <span style="color:red;">Tidak Lulus</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <hr />
                                <p class="card-title-desc">
                                <h5>Data Support Kombinasi 2 Produk</h5>
                                </p>
                                <div class="table-responsive">
                                    <table class="table mb-0 table-hover" id="tblKombinasiItemset">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Produk A</th>
                                                <th>Produk B</th>
                                                <th>Jumlah Transaksi</th>
                                                <th>Perhitungan Support</th>
                                                <th>Support</th>
                                                <th>Hasil</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($dataKombinasiItemset as $is)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $is->dataProduk($is->id_product_a)->product_name}}</td>
                                                    <td>{{ $is->dataProduk($is->id_product_b)->product_name }}</td>
                                                    <td>{{ $is->jumlah_transaksi }}</td>
                                                    <td>( {{ $is->jumlah_transaksi }} / {{ $totalProduk }} ) * 100
                                                    </td>
                                                    <td>{{ $is->support }} %</td>
                                                    <td>
                                                        @if($is->support>=$min_support)
                                                        <span style="color:green;">Lulus</span>
                                                        @else
                                                        <span style="color:red;">Tidak Lulus</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <hr />
                                <p class="card-title-desc">
                                <h5>Data Confidence Kombinasi 2 Produk</h5>
                                </p>
                                <div class="table-responsive">
                                    <table class="table mb-0 table-hover" id="tblKombinasiItemset">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Produk A</th>
                                                <th>Produk B</th>
                                                <th>Jumlah Transaksi</th>
                                                <th>Jumlah Transaksi Produk A</th>
                                                <th>Perhitungan Confidence</th>
                                                <th>Confidence</th>
                                                <th>Hasil</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($dataKombinasiItemsetConfidence as $is)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $is->dataProduk($is->id_product_a)->product_name}}</td>
                                                    <td>{{ $is->dataProduk($is->id_product_b)->product_name }}</td>
                                                    <td>{{ $is->jumlah_transaksi }}</td>
                                                    <td>{{ $is->total_transaksi_product_a }}</td>
                                                    <td>( {{ $is->jumlah_transaksi }} / {{ $is->total_transaksi_product_a }} ) * 100
                                                    </td>
                                                    <td>{{ $is->confidence }} %</td>
                                                    <td>
                                                        @if($is->confidence>=$min_confidence)
                                                        <span style="color:green;">Lulus</span>
                                                        @else
                                                        <span style="color:red;">Tidak Lulus</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <hr />
                                <p class="card-title-desc">
                                <h5>Hasil Analisa</h5>
                                </p>
                                <div class="table-responsive">
                                    <table class="table mb-0 table-hover" id="tblPolaHasil">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Pola</th>
                                                <th>Support</th>
                                                <th>Confidence</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($dataMinConfidence as $is)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>
                                                        Apabila pelanggan membeli produk
                                                        <b>{{ $is->dataProduk($is->id_product_a)->product_name }}</b>,
                                                        maka pelanggan juga akan membeli produk
                                                        <b>{{ $is->dataProduk($is->id_product_b)->product_name }}</b>
                                                    </td>
                                                    <td>{{ $is->support }} %</td>
                                                    <td>{{ $is->confidence }} %</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <br>
                                <br>
                                <div class="modal-footer">
                                    <div style="float:right;">
                                        <div class="col-md-10" style="margin-right: 20px;">
                                            <a href="{{ route('admin.analysis.index')}}" type="button" class="btn btn-danger">
                                                <i class="fa fa-arrow-left"></i>&nbsp;
                                                Back
                                            </a>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </section>
                </section>
            </div>
            @include('layouts.footer')
        </div>
    </div>
</body>
</html>
