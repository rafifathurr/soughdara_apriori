<!DOCTYPE html>
<html lang="en">
@include('home.layouts.head')

<body>
    <div class="wrapper">
        @include('home.layouts.sidebar')
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
                <div class="page-inner">
                    <div class="row">
                       @isset($menus)
                       @foreach($menus as $menu)
                       <div class="col-md-4">
                           <div class="card card-post card-round">
                               <img class="card-img-top" src="{{asset('Uploads/Product/'.$menu->id.'/uploads/'.$menu->upload)}}" alt="Card image cap">
                               <div class="card-body">
                                   <div class="d-flex justify-content-center">
                                       <center>
                                           <div class="info-post">
                                               <p class="username">{{ $menu->product_name }}</p>
                                               <p class="date text-muted"><b>Rp. {{number_format($menu->price,0,',','.')}}</b></p>
                                           </div>
                                       </center>
                                   </div>
                               </div>
                           </div>
                       </div>
                       @endforeach
                       @endisset
                    </div>
                </div>
            </div>
            @include('home.layouts.footer')
        </div>
    </div>
</body>
<script>
</script>
</html>
