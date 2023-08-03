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
                                            <label for="min_conf"  class="col-md-12">Min Confindence</label>
                                            <input type="number" min="0" id="min_conf" class="form-control">
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <a href="" class="btn btn-primary float-right ml-auto mb-3">
                                                <i class="fa fa-plus"></i>
                                                Generate
                                            </a>
                                        </div>
                                    </div>
                                    <hr>
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
                        ")' title='Hapus'><i class='fa fa-trash' style='color:red;'></i></button></center></td>" +
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
