@extends('layouts.v_template')
@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="row justify-content-center">
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card bg-hover-light-success transition ease-in-out delay-150 -translate-y-px-hover duration-300 scale-110-hover" style="border-radius: 10px">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <span class="text-dark text-sm mb-0 text-capitalize text-hover-primary" style="font-size: 18px">Users</span>
                                <h5 class="font-weight-bolder mb-0 fs-1">
                                    {{ $user }}
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card bg-hover-light-success transition ease-in-out delay-150 -translate-y-px-hover duration-300 scale-110-hover" style="border-radius: 10px">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <span class="text-dark text-sm mb-0 text-capitalize text-hover-primary" style="font-size: 18px">Product</span>
                                <h5 class="font-weight-bolder mb-0 fs-1">
                                    {{ $product }}
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card bg-hover-light-success transition ease-in-out delay-150 -translate-y-px-hover duration-300 scale-110-hover" style="border-radius: 10px">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <span class="text-dark text-sm mb-0 text-capitalize text-hover-primary" style="font-size: 18px">Category</span>
                                <h5 class="font-weight-bolder mb-0 fs-1">
                                    {{ $category }}
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
