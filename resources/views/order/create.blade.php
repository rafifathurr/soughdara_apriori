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
                                @if (Auth::guard('admin')->check())
                                    <form id="form_add" action="{{ route('admin.order.' . $url) }}" method="POST"
                                        enctype="multipart/form-data">
                                    @else
                                        <form id="form_add" action="{{ route('user.order.' . $url) }}" method="POST"
                                            enctype="multipart/form-data">
                                @endif
                                {{ csrf_field() }}
                                <input type="hidden" class="form-control" id="id" name="id"
                                    @if (isset($orders)) value="{{ $orders->id }}" @endisset>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label class="col-md-12">Receipt Number <span style="color: red;">*</span></label>
                                            <div class="col-md-12">
                                                <input type="text" name="receipt_number" id="receipt_number" class="form-control"
                                                    @isset($orders) value="{{ $orders->receipt_number }}" @endisset
                                                    required {{ $disabled_ }}>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="col-md-12">Date Order <span style="color: red;">*</span></label>
                                            <div class="col-md-12">
                                                <input type="date" name="tgl" id="tgl" class="form-control tgl_date"
                                                    data-date-format="DD/MM/YYYY"
                                                    @isset($orders) value="{{ $orders->date }}" @endisset
                                                    required {{ $disabled_ }}>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="col-md-12">Time <span style="color: red;">*</span></label>
                                            <div class="col-md-12">
                                                <input type="time" name="time" id="time" class="form-control"
                                                    @isset($orders) value="{{ $orders->date }}" @endisset
                                                    required {{ $disabled_ }}>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label class="col-md-12">Type <span style="color: red;">*</span></label>
                                            <div class="col-md-12">
                                                <select name="event_type" id="event_type" class="form-control" {{ $disabled_ }} required>
                                                @if (isset($orders))
                                                    <option value="{{ $orders->event_type }}" hidden selected>{{ $orders->event_type }}</option>
                                                    <option value="Payment">Payment</option>
                                                    <option value="Refund">Refund</option>
                                                @else
                                                    <option value="Payment" selected>Payment</option>
                                                    <option value="Refund">Refund</option>
                                                @endisset
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <label class="col-md-12">Discount <span style="color: red;">*</span></label>
                                            <div class="col-md-12">
                                                <input type="text" name="total_amount" id="total_amount"
                                                    @if (isset($orders)) value="{{ $orders->total_amount }}" @endisset class="form-control numeric" required>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <label class="col-md-12">Total Amount <span style="color: red;">*</span></label>
                                            <div class="col-md-12">
                                                <input type="text" name="total_amount" id="total_amount"
                                                    @if (isset($orders)) value="{{ $orders->total_amount }}" @endisset class="form-control numeric" required>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    @if ($title == 'Add Order' || $title == 'Edit Order')
                                    <div class="row">
                                        <div class="col-md-12" >
                                            <div style="float: right; margin-right:20px;">
                                                <a style="color:white;" class="btn btn-primary" id="btn-collapse"><i class="fa fa-plus"></i> Add Product</a>
                                            </div>
                                        </div>
                                    </div> @endif
                                    <br>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div id="collapse"
                                            class="panel-collapse collapse @isset($orders) show @endisset">
                                            <hr><br>
                                            @if ($title == 'Add Order' || $title == 'Edit Order')
                                                <div class="panel-body">
                                                    <div class="row">
                                                        <div class="col-md-5">
                                                            <label class="col-md-12">Product <span
                                                                    style="color: red;">*</span></label>
                                                            <div class="col-md-12">
                                                                <select name="prods" id="prods" onchange="getDetails()" class="form-control" {{ $disabled_ }} required>
                                                                    <option hidden> Choose Product</option>
                                                                    @foreach ($products as $prod)
                                                                        <option @isset($orders)
                                                                        @if($orders->id_product == $prod->id) selected
                                                                        @endif @endisset value="{{ $prod->id }}">
                                                                            {{ $prod->product_name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-5">
                                                            <label class="col-md-12">Category <span
                                                                    style="color: red;">*</span></label>
                                                            <div class="col-md-12">
                                                                <select name="category_prod" id="category_prod"
                                                                    onchange="getDetails()" class="form-control"
                                                                    disabled>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label class="col-md-12">Qty <span
                                                                    style="color: red;">*</span></label>
                                                            <div class="col-md-12">
                                                                <input type="hidden" min="0" name="stock"
                                                                    id="stock" class="form-control" srequired=""
                                                                    {{ $disabled_ }}>
                                                                <input type="number" min="0" name="qty"
                                                                    id="qty" oninput="price_change()"
                                                                    class="form-control" required=""
                                                                    {{ $disabled_ }}>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <button type="button" id="btn_tambahToTableUser"
                                                                class="btn btn-primary float-right"
                                                                style="margin-right:20px;">
                                                                <i class="fa fa-save"></i> Save Product
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            <br>
                                            <table id="dt-detail" class="table table-striped table-bordered table-hover"
                                                width="100%" style="text-align: center;">
                                                <thead style="background-color: #fbfbfb;">
                                                    <tr>
                                                        <th style="vertical-align: middle;" width="50%">
                                                            <center>Products</center>
                                                        </th>
                                                        <th style="vertical-align: middle;" width="25%">
                                                            <center>Qty</center>
                                                        </th>
                                                        @if ($title == 'Add Order' || $title == 'Edit Order')
                                                            <th style="vertical-align: middle;" width="25%">
                                                                <center>Action</center>
                                                            </th>
                                                        @endif
                                                    </tr>
                                                </thead>
                                                <tbody id="table_body">
                                                    @isset($orders)
                                                        @foreach ($details_order as $details)
                                                            <tr>
                                                                <td style="text-align:left;">
                                                                    {{ $details->product->product_name }}
                                                                </td>
                                                                <td style="text-align:right;">
                                                                    {{ $details->qty }}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @endisset
                                                </tbody>
                                                {{-- <tbody >
                                                        <tr>
                                                            <td colspan="2"><b>TOTAL</b></td>
                                                            <td>
                                                                <input type="hidden" class="form-control numeric" @if (isset($orders)) value="{{ $orders->total_base_price }}" @endisset style='width:100px !important; height:25px !important; text-align:center;' name="base_price" id="total_base_price" readonly>
                                                                <input type="text" class="form-control numeric" @if (isset($orders)) value="{{ $orders->total_sell_price }}" @endisset style='width:100px !important; height:25px !important; text-align:center;' name="sell_price" id="total_sell_price" readonly>
                                                            </td>
                                                            @if ($title == 'Add Order' || $title == 'Edit Order')
                                                            <td><button type='button' class='btn btn-link' onclick="removedata()"><i style="color:black; font-weight:bold;" class="icon-refresh"></i></button></td>
                                                            @endif
                                                        </tr>
                                                    </tbody> --}}
                                            </table>
                                            @if (isset($orders))
                                                <br>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="col-md-2"></div>
                                                        <label class="col-md-2"> <i><b>Created By</b></i> </label>
                                                        <div class="col-md-2">
                                                            <label
                                                                for=""><i><b>{{ $orders->createdby->name }}</b></i></label>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label
                                                                for=""><i><b>{{ $orders->created_at }}</b></i></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <br>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <br>
                                @if (isset($orders))
                                    <div class="row">
                                        <div class="col-md-1"></div>
                                        <div class="col-md-11">
                                            <div class="col-md-2"></div>
                                            <label class="col-md-2"> <i><b>Created By</b></i> </label>
                                            <div class="col-md-2">
                                                <label
                                                    for=""><i><b>{{ $orders->createdby->name }}</b></i></label>
                                            </div>
                                            <div class="col-md-4">
                                                <label for=""><i><b>{{ $orders->created_at }}</b></i></label>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-1"></div>
                                        <div class="col-md-11">
                                            <div class="col-md-2"></div>
                                            <label class="col-md-2"> <i><b>Updated By</b></i> </label>
                                            @if ($orders->updated_by != null)
                                                <div class="col-md-2">
                                                    <label
                                                        for=""><i><b>{{ $orders->updatedby->name }}</b></i></label>
                                                </div>
                                                <div class="col-md-4">
                                                    <label
                                                        for=""><i><b>{{ $orders->updated_at }}</b></i></label>
                                                </div>
                                            @else
                                                <div class="col-md-2">
                                                    <label for=""><i><b>-</b></i></label>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for=""><i><b>-</b></i></label>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <br>
                                @endif
                                <div class="modal-footer">
                                    <div style="float:right;">
                                        @if ($title == 'Add Order')
                                            <div class="col-md-10" style="margin-right: 20px;">
                                                @if (Auth::guard('admin')->check())
                                                    <a href="{{ route('admin.order.index') }}" type="button"
                                                        class="btn btn-danger">
                                                        <i class="fa fa-arrow-left"></i>&nbsp;
                                                        Back
                                                    </a>
                                                    <button id="save_data" type="submit" class="btn btn-primary"
                                                        style="margin-left:10px;">
                                                        <i class="fa fa-check"></i>&nbsp;
                                                        Save
                                                    </button>
                                                @else
                                                    <a href="{{ route('user.order.index') }}" type="button"
                                                        class="btn btn-danger">
                                                        <i class="fa fa-arrow-left"></i>&nbsp;
                                                        Back
                                                    </a>
                                                    <button type="submit" class="btn btn-primary"
                                                        style="margin-left:10px;">
                                                        <i class="fa fa-check"></i>&nbsp;
                                                        Save
                                                    </button>
                                                @endif
                                            </div>
                                        @elseif ($title == 'Edit Order')
                                            <div class="col-md-10" style="margin-right: 20px;">
                                                @if (Auth::guard('admin')->check())
                                                    <a href="{{ route('admin.order.index') }}" type="button"
                                                        class="btn btn-danger">
                                                        <i class="fa fa-arrow-left"></i>&nbsp;
                                                        Back
                                                    </a>
                                                    <button type="submit" class="btn btn-primary"
                                                        style="margin-left:10px;">
                                                        <i class="fa fa-check"></i>&nbsp;
                                                        Save
                                                    </button>
                                                @else
                                                    <a href="{{ route('user.order.index') }}" type="button"
                                                        class="btn btn-danger">
                                                        <i class="fa fa-arrow-left"></i>&nbsp;
                                                        Back
                                                    </a>
                                                    <button id="save_data" type="submit" class="btn btn-primary"
                                                        style="margin-left:10px;">
                                                        <i class="fa fa-check"></i>&nbsp;
                                                        Save
                                                    </button>
                                                @endif
                                            </div>
                                        @else
                                            <div class="col-md-10" style="margin-right: 20px;">
                                                @if (Auth::guard('admin')->check())
                                                    <a href="{{ route('admin.order.index') }}" type="button"
                                                        class="btn btn-danger">
                                                        <i class="fa fa-arrow-left"></i>&nbsp;
                                                        Back
                                                    </a>
                                                @else
                                                    <a href="{{ route('user.order.index') }}" type="button"
                                                        class="btn btn-danger">
                                                        <i class="fa fa-arrow-left"></i>&nbsp;
                                                        Back
                                                    </a>
                                                @endif
                                            </div>
                                        @endif
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
        var sell_price = 0;
        var base_price = 0;

        function getDetails() {

            var id_prods = $("#prods").val();

            $.ajax({
                url: "{{ route('product.detailproduct') }}",
                type: 'GET',
                data: {
                    'id_prod': id_prods
                },
                success: function(data) {
                    console.log(data);
                }
            });
        }

    </script>
    <script>
        $(document).ready(function() {

            $("#btn-collapse").click(function() {

                $("#collapse").collapse('show');
                $("html, body").animate({
                    scrollTop: $(
                        'html, body').get(1).scrollHeight
                }, 2000);

            });

            $('#qty').on('keyup textInput input', function() {
                var qty = $("#qty").val();
                var max_stock = $("#stock").val();
                var base = $("#base_price").val();
                var base_price_old = $("#base_price_old").val();
                var sell_price_old = $("#sell_price_old").val();

                //Calculation
                if (sell_price != 0) {
                    if (qty.length <= max_qty.length) {
                        if (qty > max_qty && qty.length >= max_qty.length) {
                            $('#save_data').attr('disabled', 'disabled');
                            alert("Item Quantity Exceed Stock Limit!");
                            $("#qty").val(1);
                            $("#base_price").val(base_price);
                            $("#sell_price").val(sell_price);
                        } else {
                            $('#save_data').removeAttr('disabled');
                            var result_base = base_price * qty;
                            var result_sell = sell_price * qty;
                            $("#sell_price").val(result_sell);
                            $("#base_price").val(result_base);
                        }
                    } else {
                        $('#save_data').attr('disabled', 'disabled');
                        alert("Item Quantity Exceed Stock Limit!");
                        $("#qty").val(1);
                        $("#base_price").val(base_price);
                        $("#sell_price").val(sell_price);
                    }

                } else {
                    // var input = document.getElementById("qty");
                    // input.setAttribute("max",max_stock);

                    // if(qty > max_stock){
                    //     $('#save_data').attr('disabled', 'disabled');
                    //     alert("Item Quantity Exceed Stock Limit!");
                    // }else{
                    //     $('#save_data').removeAttr('disabled');
                    //     $("#entry_price").val(0);
                    //     $("#cal_tax").val(0);
                    //     $("#cal_profit").val(0);
                    var result_base = base_price_old * qty;
                    var result_sell = sell_price_old * qty;
                    $("#base_price").val(result_base);
                    $("#sell_price").val(result_sell);
                    // }
                }
            });

            $('#entry_price').on('keyup textInput input', function() {
                var entry_price = $("#entry_price").val();
                var sell_price_old = $("#sell_price_old").val();
                var base_price = $("#base_price").val();
                var entry = entry_price.split('.').join('').replace(/^Rp/, '');
                var base = base_price.split('.').join('').replace(/^Rp/, '');
                var qty = $("#qty").val();

                //Calculation
                if (sell_price != 0) {
                    if (entry == 0) {
                        var tax = 0;
                        var profit = 0;
                    } else {
                        var profit = entry - base;
                        var sell_total = sell_price * qty;
                        if (entry > sell_total) {
                            var tax = 0;
                        } else {
                            var tax = sell_total - entry;
                        }
                    }
                } else {
                    if (entry == 0) {
                        var tax = 0;
                        var profit = 0;
                    } else {
                        var profit = entry - base;
                        var sell_total = sell_price_old * qty;
                        if (entry > sell_total) {
                            var tax = 0;
                        } else {
                            var tax = sell_total - entry;
                        }
                    }
                }
                $("#cal_tax").val(tax);
                $("#cal_profit").val(profit);
            });

            $("#tgl_date").on("change", function() {
                if (this.value == "") {
                    this.setAttribute("data-date", "DD-MM-YYYY")
                } else {
                    this.setAttribute(
                        "data-date",
                        moment(this.value, "dd/mm/yyyy")
                        .format(this.getAttribute("data-date-format"))
                    )
                }
            }).trigger("change");
        });
    </script>
</body>

</html>
