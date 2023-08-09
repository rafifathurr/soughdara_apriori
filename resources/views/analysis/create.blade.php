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
                                <input type="hidden" name="year" value={{ $tahun }}>
                                <input type="hidden" name="month" value={{ $bulan }}>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="min_supp" class="col-md-12">Min Support</label>
                                        <input type="number" value={{ $min_support }} min="0" id="min_supp" name="min_support" class="form-control" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="min_conf" class="col-md-12">Min Confindence</label>
                                        <input type="number" value={{ $min_confidence }} min="0" id="min_conf" class="form-control" readonly>
                                    </div>
                                </div>
                                <hr>
                                <div id="details" style="display:none;">
                                </div>
                            </div>
                        </div>
                    </section>
                </section>
            </div>
            @include('layouts.footer')
        </div>
    </div>
    <script>
    </script>
</body>

</html>
