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
                <div class="page-inner mt--5">
                    <!-- Button -->
                    <div class="d-flex">
                        @if (Auth::guard('admin')->check())
                            <a class="btn btn-add btn-round ml-auto mb-3" id="add_analysis" href="#">
                                <i class="fa fa-plus"></i>
                                Add Analysis Process
                            </a>
                        @endif
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <div id="add-row_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">
                            <div class="row">
                                <div class="col-sm-12 col-md-6">
                                    <div class="dataTables_length" id="add-row_length"></div>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <div id="add-row_filter"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <table id="add-row" class="display table table-striped table-hover dataTable"
                                        cellspacing="0" width="100%" role="grid" aria-describedby="add-row_info"
                                        style="width: 100%;">
                                        <thead>
                                            <tr role="row">
                                                <th width="10%">
                                                    <center>No</center>
                                                </th>
                                                <th width="15%">
                                                    <center>Year</center>
                                                </th>
                                                <th width="15%">
                                                    <center>Date</center>
                                                </th>
                                                <th width="15%">
                                                    <center>Min Support</center>
                                                </th>
                                                <th width="15%">
                                                    <center>Min Confidence</center>
                                                </th>
                                                <th width="15%">
                                                    <center>Action</center>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($analysis as $key => $data)
                                                <tr role="row" class="odd">
                                                    <td>
                                                        <center>{{ $key + 1 }}</center>
                                                    </td>
                                                    <td class="sorting_1">
                                                        <center>{{ $data->year }}</center>
                                                    </td>
                                                    <td class="sorting_1">
                                                        <center>{{ $data->date }}</center>
                                                    </td>
                                                    <td class="sorting_1">
                                                        <center>{{ $data->min_support }}</center>
                                                    </td>
                                                    <td class="sorting_1">
                                                        <center>{{ $data->min_confidence }}</center>
                                                    </td>
                                                    <td>
                                                        <center>
                                                            <a href="{{ route('admin.analysis.detail', $data->kd_analysis) }}"
                                                                data-toggle="tooltip" title="Detail"
                                                                class="btn btn-link btn-icon btn-lg"
                                                                data-original-title="Detail" control-id="ControlID-16">
                                                                <i class="fa fa-eye"></i>
                                                            </a>
                                                            <button type="submit"
                                                                onclick="destroy({{ $data->kd_analysis }})"
                                                                data-toggle="tooltip" title="Delete"
                                                                class="btn btn-link btn-simple-danger"
                                                                data-original-title="Delete" control-id="ControlID-17">
                                                                <i class="fa fa-trash" style="color:red;"></i>
                                                            </button>
                                                        </center>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 col-md-5">
                                    <div class="dataTables_info" id="add-row_info"></div>
                                </div>
                                <div class="col-sm-12 col-md-7">
                                    <div class="dataTables_paginate paging_simple_numbers" id="add-row_paginate">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @include('layouts.footer')
            <script src="{{ asset('js/app/table.js') }}"></script>
        </div>
    </div>
</body>
<script>
    function destroy(id) {
        var token = $('meta[name="csrf-token"]').attr('content');

        swal({
            title: "",
            text: "Are you sure want to delete this record?",
            icon: "warning",
            buttons: ['Cancel', 'OK'],
            // dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                $.post("{{ route('admin.analysis.delete') }}", {
                    kd_analysis: id,
                    _token: token
                }, function(data) {
                    location.reload();
                })
            } else {
                return false;
            }
        });
    }

    $(document).ready(function() {
        $('#add_analysis').on('click', function() {
            const div = document.createElement("div");
            $(div).html(
                "<input name='_token' value='{{ csrf_token() }}' type='hidden'>" +
                "<input type='date' id='date_analysis' class='form-control' min='{{ $min_date }}' max='{{ $max_date }}'>"+
                "<br><br>"+
                "<input type='number' placeholder='Min Support' class='form-control' id='min_support'><br><br>"+
                "<input type='number' placeholder='Min Confidence' class='form-control' id='min_confidence'><br>"
            );
            swal({
                title: "Add Analysis Process Order",
                content: div,
                buttons: [true, "Process and Save"]
            }).then((result) => {
                if (result == true) {
                    if ($('#date_analysis').val() != '' && $("#min_support").val() != '' && $("#min_confidence").val() != '') {
                        date = $("#date_analysis").val();
                        support = $("#min_support").val();
                        confidence = $("#min_confidence").val();
                        window.location.href = "{{ url('/admin/analysis/create') }}" + "/" +
                             date + "/" + support + "/" + confidence;
                    } else {
                        swal({
                            icon: 'warning',
                            title: 'Oops !',
                            button: false,
                            text: 'Please Complete The Data!',
                            timer: 1500
                        });
                    }
                }
            })
        })
    });

    function getMonth() {
        var tahun = document.getElementById("tahun").value;
        var token = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: "{{ route('admin.analysis.getMonth') }}",
            type: "POST",
            data: {
                '_token': token,
                'tahun': tahun
            },
        }).done(function(result) {
            $('#bulan').empty();
            $('#bulan').removeAttr('disabled');
            $('#min_support').removeAttr('disabled');
            $('#min_confidence').removeAttr('disabled');
            $('#bulan').append($('<option hidden>', {
                value: '',
                text: '- Choose Month -'
            }));
            $.each(JSON.parse(result), function(i, item) {
                $('#bulan').append($('<option>', {
                    value: item.bulan,
                    text: item.nama_bulan
                }));
            });
        });
    }
</script>

@include('layouts.swal')

</html>
