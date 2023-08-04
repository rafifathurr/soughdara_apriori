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
                                <form action="">
                                    <input type="hidden" name="tahun" value={{ $tahun }}>
                                    <input type="hidden" name="bulan" value={{ $bulan }}>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="min_supp" class="col-md-12">Min Support</label>
                                            <input type="number" min="0" id="min_supp" class="form-control">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="min_conf" class="col-md-12">Min Confindence</label>
                                            <input type="number" min="0" id="min_conf" class="form-control">
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <a href="" class="btn btn-primary float-right ml-auto mb-3">
                                                <i class="icon-refresh"></i>
                                                Generate
                                            </a>
                                        </div>
                                    </div>
                                    <hr>
                                    <?php for($i = 0; $i<$max_product; $i++) : ?>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h4><b>Result Itemset</b></h4>
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
                                                    @foreach ($first_data as $key => $data)
                                                        <tr>
                                                            <td>
                                                                {{ $key + 1 }}
                                                            </td>
                                                            <td>
                                                                {{ $data->product->product_name }}
                                                            </td>
                                                            <td>
                                                                {{ $data->total }}
                                                                <input type="hidden" value="{{ $data->total }}"
                                                                    name="total_product[]" id="">
                                                            </td>
                                                            <td>
                                                                <input type="number" class="form-control" id="" name="support[]"
                                                                    style='width:100px !important; height:25px !important; text-align:center;'
                                                                    readonly>
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" id="" name="result[]"
                                                                    style='width:100px !important; height:25px !important; text-align:center;'
                                                                    readonly>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <br>
                                    <?php endfor ?>
                                    <div class="modal-footer">
                                        <div style="float:right;">
                                            <a @if (Auth::guard('admin')->check()) href="{{ route('admin.order.index') }}" @else
                                                href="{{ route('user.order.index') }}" @endif
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

            var support_val = $("input[name='support[]']");
            var result_val = $("input[name='result[]']");
            var cnt = 10;

            support_val.map(function(index) {
                
                $(support_val.get(index)).val(cnt);

            });
        });
    </script>
</body>

</html>
