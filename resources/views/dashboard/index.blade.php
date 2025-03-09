@extends('layout')
@section('container')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="form-group col-2">
                    <select name="summary_filter" id="summary_filter" class="form-control" onchange="changeSummary(this.value)">
                        <option value="today">Today</option>
                        <option value="this_week">This Week</option>
                        <option value="this_month">This Month</option>
                        <option value="this_year">This Year</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6 col-12">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-12">
                            <div class="card">
                                <span class="mask bg-primary opacity-10 border-radius-lg"></span>
                                <div class="card-body p-3 position-relative">
                                    <div class="row">
                                        <div class="col-12 text-start">
                                            <div class="icon icon-shape bg-white shadow text-center border-radius-2xl">
                                                <i class="fas fa-receipt text-dark text-gradient text-lg opacity-10"
                                                    aria-hidden="true"></i>
                                            </div>
                                            <h5 class="text-white font-weight-bolder mb-0 mt-3" id="total_transaction">
                                            </h5>
                                            <span class="text-white text-sm">Total Transactions</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-12 mt-4 mt-md-0">
                            <div class="card">
                                <span class="mask bg-dark opacity-10 border-radius-lg"></span>
                                <div class="card-body p-3 position-relative">
                                    <div class="row">
                                        <div class="col-12 text-start">
                                            <div class="icon icon-shape bg-white shadow text-center border-radius-2xl">
                                                <i class="fas fa-burger text-dark text-gradient text-lg opacity-10"
                                                    aria-hidden="true"></i>
                                            </div>
                                            <h5 class="text-white font-weight-bolder mb-0 mt-3" id="total_menu_sold">
                                            </h5>
                                            <span class="text-white text-sm">Total Menu Sold</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-lg-6 col-md-6 col-12">
                            <div class="card">
                                <span class="mask bg-danger opacity-10 border-radius-lg"></span>
                                <div class="card-body p-3 position-relative">
                                    <div class="row">
                                        <div class="col-12 text-start">
                                            <div class="icon icon-shape bg-white shadow text-center border-radius-2xl">
                                                <i class="fas fa-money-bill text-dark text-gradient text-lg opacity-10"
                                                    aria-hidden="true"></i>
                                            </div>
                                            <h5 class="text-white font-weight-bolder mb-0 mt-3" id="gross_profit">
                                            </h5>
                                            <span class="text-white text-sm">Gross Profit</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-12 mt-4 mt-md-0">
                            <div class="card">
                                <span class="mask bg-success opacity-10 border-radius-lg"></span>
                                <div class="card-body p-3 position-relative">
                                    <div class="row">
                                        <div class="col-12 text-start">
                                            <div class="icon icon-shape bg-white shadow text-center border-radius-2xl">
                                                <i class="fas fa-money-bill text-dark text-gradient text-lg opacity-10"
                                                    aria-hidden="true"></i>
                                            </div>
                                            <h5 class="text-white font-weight-bolder mb-0 mt-3" id="net_profit">
                                            </h5>
                                            <span class="text-white text-sm">Net Profit</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-12 mt-4 mt-lg-0">
                    <div class="card shadow h-100">
                        <div class="card-header pb-0 p-3">
                            <h6 class="mb-0">Top Products</h6>
                        </div>
                        <div class="card-body pb-0 p-3">
                            <ul class="list-group" id="top_products">
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', async () => {
            await changeSummary('today');
        });

        const changeSummary = async (val) => {
            let data;
            $.ajax({
                url: "{{ url()->current() }}",
                type: "GET",
                data: {
                    filter: val
                },
                success: (data) => {
                    const needToBeChanged = ['total_transaction', 'total_menu_sold', 'gross_profit',
                        'net_profit'
                    ];
                    needToBeChanged.forEach(id => {
                        document.querySelector(`#${id}`).innerHTML = data[id];
                    });

                    const top_products = document.querySelector('#top_products');
                    top_products.innerHTML = '';
                    data.top_products.forEach((topProduct, i) => {
                        let percentage = (parseInt(topProduct.total_sold) / parseInt(
                            data.top_total_sold)) * 100;
                        let topProductEl = document.createElement("li");
                        topProductEl.className =
                            "list-group-item border-0 d-flex align-items-center px-0 mb-0";
                        topProductEl.innerHTML = `
                        <div class="w-100">
                            <div class="d-flex mb-2">
                                <span class="me-2 text-sm font-weight-bold text-dark">${topProduct.product.name}</span>
                                <span class="ms-auto text-sm font-weight-bold">${topProduct.total_sold} Sold</span>
                            </div>
                            <div>
                                <div class="progress progress-md">
                                    <div class="progress-bar bg-primary"
                                        style="width: ${percentage}%"
                                        role="progressbar"
                                        aria-valuenow="${topProduct.total_sold}"
                                        aria-valuemin="0"
                                        aria-valuemax="${data.top_total_sold}">
                                    </div>
                                </div>
                            </div>
                        </div>`;

                        top_products.appendChild(topProductEl);
                    })


                },
                error: (e) => {
                    Swal.fire({
                        position: "center",
                        icon: "error",
                        title: "Failed to fetch data! message: " + e.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            });
        }
    </script>
@endpush
