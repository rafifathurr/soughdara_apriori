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
                                        <div class="col-md-3">
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
                                        <div class="col-md-3">
                                            <label class="col-md-12">Payment Method <span style="color: red;">*</span></label>
                                            <div class="col-md-12">
                                                <select name="payment_method" id="payment_method" class="form-control" {{ $disabled_ }} required>
                                                @foreach($payment_method as $pay)
                                                    <option  @if(isset($orders))  @if($orders->payment_method == $pay->id) selected @endif @endisset value="{{$pay->id}}">{{$pay->payment_method}}</option>
                                                @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="col-md-12">Discount <span style="color: red;">*</span></label>
                                            <div class="col-md-12">
                                                <input type="text" name="discount" id="discount"
                                                    @if (isset($orders)) value="{{ $orders->total_amount }}" @endisset class="form-control numeric" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="col-md-12">Total Amount <span style="color: red;">*</span></label>
                                            <div class="col-md-12">
                                                <input type="text" oninput="allprice()" name="total_amount" id="total_amount"
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
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label class="col-md-12">Product</label>
                                                        <div class="col-md-12">
                                                            <select id="prods" onchange="getDetails()"
                                                                class="form-control" {{ $disabled_ }}>
                                                                <option hidden> Choose Product</option>
                                                                @foreach ($products as $prod)
                                                                    <option
                                                                        @isset($orders)
                                                                @if ($orders->id_product == $prod->id) selected
                                                                @endif @endisset
                                                                        value="{{ $prod->id }}">
                                                                        {{ $prod->product_name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="col-md-12">Category</label>
                                                        <div class="col-md-12">
                                                            <select id="category_prod" class="form-control" disabled>
                                                                <option value="0" hidden> -</option>
                                                                @foreach ($category as $cat)
                                                                    <option value="{{ $cat->id }}">
                                                                        {{ $cat->category }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="col-md-12">Price</label>
                                                        <div class="col-md-12">
                                                            <input type="hidden" id="qty">
                                                            <input type="hidden" id="price_">
                                                            <input type="text" id="price"
                                                                class="form-control numeric" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <br>
                                                <div id="message-container" style="display:none;">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="card" id="card-container">
                                                                <div class="card-body justify-content-center">
                                                                    <center>
                                                                        <b>
                                                                            <span id="message">
                                                                            </span>
                                                                        </b>
                                                                        <i id="icon-message" class="fa fa-check"
                                                                            style="color:green;font-size:25px;margin:10px;"></i>
                                                                    </center>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <br>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <button type="button" id="btn_tambahToTableUser"
                                                            class="btn btn-primary float-right"
                                                            style="margin-right:20px;" disabled>
                                                            <i class="fa fa-save"></i> Save Product
                                                        </button>
                                                    </div>
                                                </div>
                                            @endif
                                            <br>
                                            <table id="dt-detail" class="table table-striped table-bordered table-hover"
                                                width="100%" style="text-align: center;">
                                                <thead style="background-color: #fbfbfb;">
                                                    <tr>
                                                        <th style="vertical-align: middle;" width="25%">
                                                            <center>Products <span style="color: red;">*</span></center>
                                                        </th>
                                                        <th style="vertical-align: middle;" width="20%">
                                                            <center>Category <span style="color: red;">*</span></center>
                                                        </th>
                                                        <th style="vertical-align: middle;" width="20%">
                                                            <center>Qty <span style="color: red;">*</span></center>
                                                        </th>
                                                        <th style="vertical-align: middle;" width="20%">
                                                            <center>Price <span style="color: red;">*</span></center>
                                                        </th>
                                                        @if ($title == 'Add Order' || $title == 'Edit Order')
                                                            <th style="vertical-align: middle;" width="15%">
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
                                                <tbody>
                                                    <tr>
                                                        <td colspan="3"><b>TOTAL PRICE</b></td>
                                                        <td>
                                                            <input type="hidden" class="form-control numeric"
                                                                style='width:100px !important; height:25px !important; text-align:center;' readonly>
                                                            <input type="text" class="form-control numeric"
                                                                style='width:100px !important; height:25px !important; text-align:center;'
                                                                id="total_price" readonly>
                                                        </td>
                                                        @if ($title == 'Add Order' || $title == 'Edit Order')
                                                            <td><button type='button' class='btn btn-link'
                                                                    onclick="removedata()"><i
                                                                        style="color:black; font-weight:bold;"
                                                                        class="icon-refresh"></i></button></td>
                                                        @endif
                                                    </tr>
                                                </tbody>
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
                url: "{{ route('admin.product.detailproduct') }}",
                type: 'GET',
                data: {
                    'id_prod': id_prods
                },
                success: function(data) {
                    $("#category_prod").val(data.category.id);
                    $("#qty").val(1);
                    $("#price").val(data.price);
                    $("#price_").val(data.price);
                    $('#qty').attr('disabled', false);
                    $('#btn_tambahToTableUser').attr('disabled', false);
                }
            });

        }

        function removedata(id) {

            if (id) {

                document.getElementById(id).remove();

            } else {

                $('#table_body').empty();
                $('#total_price').val(0);

            }

            allprice();

        }

        function price_update(e) {

            let id_prods = $('#product_id_' + e + '').val();
            let prods = $('#product_name_' + e + '').text();

            let qty = $("#qty_" + e).val();

            let price = $("#price_" + e).val();

            let result_price = price * qty;

            $("#price_show_" + e).val(result_price);
            $("#price_show_" + e).inputmask({
                alias: "numeric",
                prefix: "Rp.",
                digits: 0,
                repeat: 20,
                digitsOptional: false,
                decimalProtect: true,
                groupSeparator: ".",
                placeholder: '0',
                radixPoint: ",",
                radixFocus: true,
                autoGroup: true,
                autoUnmask: false,
                clearMaskOnLostFocus: false,
                onBeforeMask: function(value, opts) {
                    return value;
                },
                removeMaskOnSubmit: true
            });

            allprice();

        }

        function allprice() {

            let total_price = 0;
            let total_amount = $("#total_amount").val();
            total_amount = total_amount.split("Rp.").pop();
            total_amount = total_amount.split(".").join('');

            $("input[name='price_data[]']").map(function() {

                let price = $(this).val();
                price = price.split("Rp.").pop();
                price = price.split(".").join('');
                total_price += parseInt(price);

            });

            if (total_price == total_amount && total_price > 0 && total_amount > 0) {
                $("#message-container").css("display", "block");
                $("#message").text("Total Harga Product Sesuai Dengan Harga Yang Masuk!");
                $("card-container").css({
                    "border-color": "green",
                    "border-width": "1px",
                    "border-style": "solid"
                });
                $("#icon-message").removeClass("fa-ban");
                $("#icon-message").addClass("fa-check");
                $("#icon-message").css("color", "green");
            } else {
                if (total_amount == 0 || total_price == 0) {
                    $("#message-container").css("display", "none");
                } else {
                    $("#message-container").css("display", "block");
                    $("#message").text("Total Harga Product Tidak Sesuai Dengan Harga Yang Masuk!");
                    $("card-container").css({
                        "border-color": "red",
                        "border-width": "1px",
                        "border-style": "solid"
                    });
                    $("#icon-message").removeClass("fa-check");
                    $("#icon-message").addClass("fa-ban");
                    $("#icon-message").css("color", "red");
                }

            }

            $('#total_price').val(total_price);

        }

        $(document).ready(function() {

            $("#btn-collapse").click(function() {

                $("#collapse").collapse('show');
                $("html, body").animate({
                    scrollTop: $(
                        'html, body').get(1).scrollHeight
                }, 2000);

            });

            $('#qty').on('keyup textInput input', function() {
                let qty = $("#qty").val();
                let price = $("#price").val();

                let result = price * qty;
                $("#price").val(result);

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

            $('#btn_tambahToTableUser').on('click', function() {
                var table_body = $('#tabel_body');

                let id_product = $('#prods').val();

                let qty = parseInt($('#qty').val());

                let price = $('#price_').val();

                var data = $('input[name^="product_id[]"]').map(function() {
                    return $(this).val();
                }).get();

                let length = $('#product_id_' + id_product).length;

                if (data.length > 0 && length > 0) {

                    let id_prods = $('#product_id_' + id_product).val();
                    let qty_prods = parseInt($('#qty_' + id_product).val());
                    let result_qty = qty_prods + qty;

                    $('#qty_' + id_product).val(result_qty);

                    $('#price_show_' + id_prods).val(result_qty * price);
                    $('#price_show_' + id_prods).inputmask({
                        alias: "numeric",
                        prefix: "Rp.",
                        digits: 0,
                        repeat: 20,
                        digitsOptional: false,
                        decimalProtect: true,
                        groupSeparator: ".",
                        placeholder: '0',
                        radixPoint: ",",
                        radixFocus: true,
                        autoGroup: true,
                        autoUnmask: false,
                        clearMaskOnLostFocus: false,
                        onBeforeMask: function(value, opts) {
                            return value;
                        },
                        removeMaskOnSubmit: true
                    });

                    $('#prods').val("");
                    $('#qty').val("");
                    $('#qty').attr('disabled', 'disabled');
                    $('#category').val("0");
                    $('#price').val("");
                    $('#price_').val("");

                } else {

                    let price_ = $('#price').val();
                    var product_name = $('#prods option:selected').text();
                    var category_name = $('#category_prod option:selected').text();

                    $('#table_body').append("<tr id='" + id_product + "'>" +
                        "<td style='text-align:left;'><input type='hidden' name='product_id[]' id='product_id_" +
                        id_product + "' value='" + id_product + "' readonly><span>" + product_name +
                        "</span></td>" +
                        "<td style='text-align:left;'><span>" + category_name + "</span></td>" +
                        "<td><center><input type='number' style='width:100px !important; height:25px !important; text-align:center;' min=0 class='form-control' name='qty[]' id='qty_" +
                        id_product + "' value='" + qty + "' oninput ='price_update(" + id_product +
                        ")'></center></td>" +
                        "<input type='hidden' id='price_" + id_product +
                        "' value='" + price + "' readonly>" +
                        "<td><center><input type='text' name='price_data[]' style='width:100px !important; height:25px !important; text-align:center;' class='form-control numeric' id='price_show_" +
                        id_product + "' value='" + price_ + "' readonly></center></td>" +
                        "<td><center><button type='button' class='btn btn-link btn-simple-danger' onclick='removedata(" +
                        id_product +
                        ")' title='Hapus'><i class='fa fa-trash' style='color:red;''></i></button></center></td>" +
                        "</tr>");

                    $('#prods').val("");
                    $('#qty').val("");
                    $('#qty').attr('disabled', 'disabled');
                    $('#category').val("0");
                    $('#price').val("");
                    $('#price_').val("");

                    $("html, body").animate({
                        scrollTop: $(
                            'html, body').get(0).scrollHeight
                    }, 2000);

                }

                allprice();

                $('#btn_tambahToTableUser').attr('disabled', true);

            });
        });
    </script>
</body>

</html>
