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
                                <form action="{{ route('admin.analysis.' . $url) }}" method="POST"
                                enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="year" value={{ $tahun }}>
                                    <input type="hidden" name="month" value={{ $bulan }}>
                                    <input type="hidden" name="total_order" id="total_order" value={{ $total_transaksi }}>
                                    <input type="hidden" id="total_itemset" value={{ $max_product }}>
                                    <input type="hidden" id="total_data" value={{ $total_data }}>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="min_supp" class="col-md-12">Min Support</label>
                                            <input type="number" min="0" id="min_supp" name="min_support" class="form-control">
                                        </div>
                                        {{-- <div class="col-md-6">
                                            <label for="min_conf" class="col-md-12">Min Confindence</label>
                                            <input type="number" min="0" id="min_conf" class="form-control">
                                        </div> --}}
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <a href="#" id="generate"
                                                class="btn btn-primary float-right ml-auto mb-3">
                                                <i class="icon-refresh"></i>
                                                Generate
                                            </a>
                                        </div>
                                    </div>
                                    <hr>
                                    <div id="details" style="display:none;">
                                        @for($i = 1; $i<=$max_product; $i++)
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h4><b>Itemset {{ $i }}</b></h4>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md 12">
                                                <table id="table_set_{{ $i + 1 }}"
                                                    class="display table table-striped table-hover dataTable"
                                                    cellspacing="0" width="100%" role="grid"
                                                    aria-describedby="add-row_info" style="width: 100%;">
                                                    <thead>
                                                        <tr>
                                                            <th>
                                                                No
                                                            </th>
                                                            <th>
                                                                Product
                                                            </th>
                                                            <th>
                                                                Total
                                                            </th>
                                                            <th>
                                                                Support
                                                            </th>
                                                            <th>
                                                                Result
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($itemset[$i] as $item)
                                                            @foreach($item as $key => $data)
                                                            <tr>
                                                                <td>
                                                                    {{ $key + 1 }}
                                                                </td>
                                                                <td>
                                                                    {{ $data->product_name }}
                                                                    <input type="hidden" id="" name="id_product[]" value="{{ $data->id_product }}">
                                                                </td>
                                                                <td>
                                                                    {{ $data->total }}
                                                                    <input type="hidden" value="{{ $data->total }}"
                                                                        name="total_per_product[]" id="total_per_product_{{ $i }}_{{ $key }}">
                                                                </td>
                                                                <td>
                                                                    <input type="number" class="form-control"
                                                                        id="support_{{ $i }}_{{ $key }}" name="support[]"
                                                                        style='width:100px !important; height:25px !important; text-align:center;'
                                                                        readonly>
                                                                </td>
                                                                <td>
                                                                    <center>
                                                                        <span id="status_result_{{ $i }}_{{ $key }}"></span>
                                                                    </center>
                                                                    <input type="hidden" class="form-control"
                                                                        id="result_{{ $i }}_{{ $key }}" name="result[]"
                                                                        style='width:100px !important; height:25px !important; text-align:center;'
                                                                        readonly>
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <br>
                                        @endfor
                                        @for($i = 1; $i<=$max_product; $i++)
                                        @if($i > 1)
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h4><b>Confidence {{ $i }}</b></h4>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md 12">
                                                <table id="table_set_{{ $i + 1 }}"
                                                    class="display table table-striped table-hover dataTable"
                                                    cellspacing="0" width="100%" role="grid"
                                                    aria-describedby="add-row_info" style="width: 100%;">
                                                    <thead>
                                                        <tr>
                                                            <th>
                                                                No
                                                            </th>
                                                            <th>
                                                                Product
                                                            </th>
                                                            <th>
                                                                Confidence
                                                            </th>
                                                            <th>
                                                                Result
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($itemset[$i] as $item)
                                                            @foreach($item as $key => $data)
                                                            <tr>
                                                                <td>
                                                                    {{ $key + 1 }}
                                                                </td>
                                                                <td>
                                                                    {{ $data->product_name }}
                                                                </td>
                                                                <td>
                                                                    <input type="number" class="form-control"
                                                                        id="confidence_{{ $i }}_{{ $key }}" name="support[]"
                                                                        style='width:100px !important; height:25px !important; text-align:center;'
                                                                        readonly>
                                                                </td>
                                                                <td>
                                                                    <center>
                                                                        <span id="status_confidence_{{ $i }}_{{ $key }}"></span>
                                                                    </center>
                                                                    <input type="hidden" class="form-control"
                                                                        id="result_confidence_{{ $i }}_{{ $key }}" name="result[]"
                                                                        style='width:100px !important; height:25px !important; text-align:center;'
                                                                        readonly>
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <br>
                                        @endif
                                        @endfor
                                        <div class="modal-footer">
                                            <div style="float:right;">
                                                <a href="{{ route('admin.analysis.index') }}"
                                                    type="button" class="btn btn-danger">
                                                    <i class="fa fa-arrow-left"></i>&nbsp;
                                                    Back
                                                </a>
                                                <button id="save_data" type="submit" class="btn btn-primary"
                                                    style="margin-left:10px;">
                                                    <i class="fa fa-check"></i>&nbsp;
                                                    Save
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </section>
                </section>
            </div>
            @include('layouts.footer')
        </div>
    </div>
    <script>
        $(document).ready(function() {

            $("#generate").on("click", function() {
                let total_itemset = $("#total_itemset").val();
                let total_data = $("#total_data").val();
                let total_order = $("#total_order").val();
                let total_per_product = $("input[name='total_per_product[]']");
                let support_val = $("input[name='support[]']");
                let result_val = $("input[name='result[]']");
                let min_supp = $("#min_supp").val();
                // let min_conf = $("#min_conf").val();

                if(!min_supp){
                    swal({
                        title: "",
                        text: "Please Complete The Data!",
                        icon: "warning"
                    })
                }else{
                    $("#details").css("display", "block");
                }

                for(let x = 1; x <= total_itemset; x++){
                    for(let y = 0; y <= total_data; y++ ){
                        let total = $("#total_per_product_"+x+"_"+y).val();
                        let support = (parseInt(total)/parseInt(total_order)*100).toFixed(2);

                        if(support > min_supp){
                            $('#result_'+x+'_'+y).val("LULUS");
                            $('#status_result_'+x+'_'+y).text("LULUS")
                            $('#status_result_'+x+'_'+y).css("color", "green");
                        }else{
                            $('#result_'+x+'_'+y).val("TIDAK LULUS");
                            $('#status_result_'+x+'_'+y).text("TIDAK LULUS");
                            $('#status_result_'+x+'_'+y).css("color", "red");
                        }
                        $('#support_'+x+'_'+y).val(support);

                    }
                }

                // total_per_product.map(function(index) {

                //     let total = $(this).val();
                //     let support = (parseInt(total)/parseInt(total_order)*100).toFixed(2);

                //     if(support > min_supp){
                //         $(result_val.get(index)).val("LULUS");
                //         $('#status_result_'+1+'_'+index).text("LULUS")
                //         $('#status_result_'+1+'_'+index).css("color", "green");
                //     }else{
                //         $(result_val.get(index)).val("TIDAK LULUS");
                //         $('#status_result_'+1+'_'+index).text("TIDAK LULUS");
                //         $('#status_result_'+1+'_'+index).css("color", "red");
                //     }
                //     $(support_val.get(index)).val(support);

                // });
            });


        });
    </script>
</body>

</html>
