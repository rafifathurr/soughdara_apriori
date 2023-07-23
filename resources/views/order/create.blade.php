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
                                <br>
                                <div class="row">
                                    <div class="col-md-1"></div>
                                    <div class="col-md-8">
                                        <label class="col-md-6">Product <span style="color: red;">*</span></label>
                                        <div class="col-md-12">
                                            <input type="hidden" class="form-control" id="id" name="id"
                                                @if (isset($orders)) value="{{ $orders->id }}" @endisset
                                                    autocomplete="off" required="">
                                                @if ($title == 'Edit Order')
                                                    <select name="prods" id="prods" onchange="getProds()" class="form-control"
                                                        @if (isset($orders)) @endisset {{ $disabled_ }} readonly>
                                                        <option value="" style="display: none;" selected="">- Choose Products -
                                                        </option>
                                                        @foreach ($products as $prod)
                                                            <option @if (isset($orders))
                                                            <?php if ($orders->product_id == $prod->id) {
                                                                echo 'selected';
                                                            } ?> @endisset
                                                            value="{{ $prod->id }}">{{ $prod->product_name }}</option>
                                                        @endforeach
                                                    </select>
                                                @else
                                                    <select name="prods" id="prods" onchange="getProds()" class="form-control"
                                                        @if (isset($orders)) @endisset {{ $disabled_ }}>
                                                        <option value="" style="display: none;" selected="">- Choose Products -
                                                        </option>
                                                        @foreach ($products as $prod)
                                                            <option @if (isset($orders))
                                                            <?php if ($orders->product_id == $prod->id) {
                                                                echo 'selected';
                                                            } ?> @endisset
                                                            value="{{ $prod->id }}">{{ $prod->product_name }}</option>
                                                    @endforeach
                                                    </select> @endif
                                                </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="col-md-6">Qty <span style="color: red;">*</span></label>
                                            <div class="col-md-12">
                                                <input type="hidden" min="0" name="stock" id="stock"
                                                    class="form-control"
                                                    @if (isset($orders)) value="{{ $orders->product->stock }}" @endisset step="1" required="" style="width:35%" {{ $disabled_ }}>
                                                <input type="number" min="0" name="qty" id="qty" class="form-control"
                                                @if (isset($orders)) value="{{ $orders->qty }}" @endisset step="1" required="" style="width:35%" {{ $disabled__ }} {{ $disabled_ }}>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-1"></div>
                                        <div class="col-md-6">
                                            <label class="col-md-6">Entry Price <span style="color: red;">*</span></label>
                                            <div class="col-md-12">
                                                <input type="hidden" name="sell_price_old" id="sell_price_old"
                                                    @if (isset($orders)) value="{{ $orders->sell_price_product }}" @endisset class="form-control" required {{ $disabled_ }}
                                                    style="width:100%">
                                                <input type="text" name="entry_price" id="entry_price"
                                                    @if (isset($orders)) value="{{ $orders->entry_price }}" @endisset class="form-control numeric" autocomplete="off" required="" {{ $disabled_ }}
                                                    style="width:100%">
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            @if (Auth::guard('admin')->check())
                                                <label class="col-md-6">Base Price <span style="color: red;">*</span></label>
                                                <div class="col-md-12">
                                                    <input type="hidden" name="base_price_old" id="base_price_old" class="form-control"
                                                        @if (isset($orders)) value="{{ $orders->base_price_product }}" @endisset readonly>
                                                    <input type="text" name="base_price" id="base_price" class="form-control numeric"
                                                        @if (isset($orders)) value="{{ $orders->base_price_product * $orders->qty }}" @endisset autocomplete="off" required="" style="width:100%" {{ $disabled_ }} readonly>
                                                </div>
                                            @else
                                                <label class="col-md-6">Sell Price <span style="color: red;">*</span></label>
                                                <div class="col-md-12">
                                                    <input type="hidden" name="base_price_old" id="base_price_old" class="form-control"
                                                        @if (isset($orders)) value="{{ $orders->base_price_product }}" @endisset readonly>
                                                    <input type="hidden" name="base_price" id="base_price" class="form-control numeric"
                                                        @if (isset($orders)) value="{{ $orders->base_price_product * $orders->qty }}" @endisset autocomplete="off" required="" style="width:100%" {{ $disabled_ }} readonly>
                                                    <input type="text" name="sell_price" id="sell_price" class="form-control numeric"
                                                        @if (isset($orders)) value="{{ $orders->sell_price_product * $orders->qty }}" @endisset autocomplete="off" required="" style="width:100%" {{ $disabled_ }} readonly>
                                                </div> @endif
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-1"></div>
                                        <div class="col-md-6">
                                            <label class="col-md-6">Source Payment <span
                                                    style="color: red;">*</span></label>
                                            <div class="col-md-12">
                                                <select name="source_pay" id="source_pay" class="form-control"
                                                    required {{ $disabled_ }}>
                                                    <option value="" style="display: none;"
                                                        selected="">- Choose Sources -
                                                    </option>
                                                    @foreach ($sources as $source)
                                                        <option
                                                            @if (isset($orders)) <?php if ($orders->source_id == $source->id) {
                                                                echo 'selected';
                                                            } ?> @endisset
                                                        value="{{ $source->id }}">{{ $source->source }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <label class="col-md-6">Date Order <span style="color: red;">*</span></label>
                                            <div class="col-md-12">
                                                <input type="date" name="tgl" id="tgl" class="form-control tgl_date"
                                                    autocomplete="off" data-date="" data-date-format="DD/MM/YYYY"
                                                    @isset($orders) value="{{ $orders->date }}" @endisset
                                                    required {{ $disabled_ }}>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-1"></div>
                                        <div class="col-md-11">
                                            <label class="col-md-6">Note </label>
                                            <div class="col-md-12">
                                                <textarea class="form-control" name="note" id="note" rows="5" cols="10" style="width:100%" {{ $disabled_ }}>@if (isset($orders)) {{ $orders->desc }} @endisset</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-1"></div>
                                        <div class="col-md-6">
                                            <label class="col-md-6">Platform Fee<span style="color: red;">*</span></label>
                                            <div class="col-md-12">
                                                <input type="text" name="cal_tax" id="cal_tax" class="form-control numeric"
                                                    @if (isset($orders)) value="{{ $orders->tax }}" @endisset autocomplete="off" required {{ $disabled_ }} readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <label class="col-md-6">Calculation Profit <span style="color: red;">*</span></label>
                                            <div class="col-md-12">
                                                <input type="text" name="cal_profit" id="cal_profit"
                                                    @if (isset($orders)) value="{{ $orders->profit }}" @endisset class="form-control numeric" autocomplete="off" required {{ $disabled_ }} readonly>
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
                                                <label for=""><i><b>{{ $orders->createdby->name }}</b></i></label>
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
                                                <label for=""><i><b>{{ $orders->updatedby->name }}</b></i></label>
                                            </div>
                                            <div class="col-md-4">
                                                <label for=""><i><b>{{ $orders->updated_at }}</b></i></label>
                                            </div>
                                            @else
                                            <div class="col-md-2">
                                                <label for=""><i><b>-</b></i></label>
                                            </div>
                                            <div class="col-md-4">
                                                <label for=""><i><b>-</b></i></label>
                                            </div> @endif
                                        </div>
                                    </div>
                                    <br>
                                    @endif
                                    <div class="modal-footer">
                                        <div style="float:right;">
                                            @if ($title == 'Add Order')
                                                <div class="col-md-10" style="margin-right: 20px;">
                                                    @if (Auth::guard('admin')->check())
                                                        <a href="{{ route('admin.order.index') }}"
                                                            type="button" class="btn btn-danger">
                                                            <i class="fa fa-arrow-left"></i>&nbsp;
                                                            Back
                                                        </a>
                                                        <button id="save_data" type="submit"
                                                            class="btn btn-primary"
                                                            style="margin-left:10px;">
                                                            <i class="fa fa-check"></i>&nbsp;
                                                            Save
                                                        </button>
                                                    @else
                                                        <a href="{{ route('user.order.index') }}"
                                                            type="button" class="btn btn-danger">
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
                                                        <a href="{{ route('admin.order.index') }}"
                                                            type="button" class="btn btn-danger">
                                                            <i class="fa fa-arrow-left"></i>&nbsp;
                                                            Back
                                                        </a>
                                                        <button type="submit" class="btn btn-primary"
                                                            style="margin-left:10px;">
                                                            <i class="fa fa-check"></i>&nbsp;
                                                            Save
                                                        </button>
                                                    @else
                                                        <a href="{{ route('user.order.index') }}"
                                                            type="button" class="btn btn-danger">
                                                            <i class="fa fa-arrow-left"></i>&nbsp;
                                                            Back
                                                        </a>
                                                        <button id="save_data" type="submit"
                                                            class="btn btn-primary"
                                                            style="margin-left:10px;">
                                                            <i class="fa fa-check"></i>&nbsp;
                                                            Save
                                                        </button>
                                                    @endif
                                                </div>
                                            @else
                                                <div class="col-md-10" style="margin-right: 20px;">
                                                    @if (Auth::guard('admin')->check())
                                                        <a href="{{ route('admin.order.index') }}"
                                                            type="button" class="btn btn-danger">
                                                            <i class="fa fa-arrow-left"></i>&nbsp;
                                                            Back
                                                        </a>
                                                    @else
                                                        <a href="{{ route('user.order.index') }}"
                                                            type="button" class="btn btn-danger">
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

        function getProds() {
            var token = $('meta[name="csrf-token"]').attr('content');
            var id_prods = document.getElementById("prods").value;
            $.ajax({
                type: 'GET',
                @if (Auth::guard('admin')->check())
                    url: "{{ route('admin.order.getDetailProds') }}",
                @else
                    url: "{{ route('user.order.getDetailProds') }}",
                @endif
                data: {
                    'id_prod': id_prods
                },
                success: function(data) {
                    $("#entry_price").val(0);
                    $("#cal_tax").val(0);
                    $("#cal_profit").val(0);
                    $('#qty').removeAttr('disabled');
                    base_price = data["base_price"];
                    sell_price = data["selling_price"];
                    $('#qty').val(1);
                    $('#base_price').val(base_price);
                    $("#base_price_old").val(base_price);
                    $('#sell_price').val(sell_price);
                    $("#sell_price_old").val(sell_price);
                    max_qty = data["stock"];
                    console.log('MAX Stock : ' + max_qty);
                    console.log('LENGTH max Stock : ' + max_qty.length);
                    var input = document.getElementById("qty");
                    input.setAttribute("max", max_qty);
                }
            });
        }
    </script>
    <script>
        $(document).ready(function() {

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
