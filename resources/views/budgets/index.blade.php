@extends('layout')
@section('container')
    <div>
        <div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">Create</button>
        </div>
        <table id="dataTable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Price</th>
                    <th>Details</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>
    </div>
    <form method="POST" onsubmit="submitForm(this, 'store')" action="{{ route('budgets.store') }}" class="modal fade"
        id="createModal" tabindex="-1" aria-labelledby="modal" aria-hidden="true">
        @csrf
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Create Product</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Price</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp.</span>
                            <input required type="number" name="price" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="pasword">Details</label>
                        <div class="input-group">
                            <input required type="textarea" id="detail" name="detail" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="pasword">Date</label>
                        <div class="input-group">
                            <input required type="date" id="date" name="date" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </form>
@endsection
@push('js')
    <script>
        let table = $('#dataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ url()->current() }}",
            columnDefs: [{
                "className": "dt-center",
                "targets": "_all"
            }],
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'price',
                    name: 'price',
                    orderable: true,
                    searchable: true
                },
                {
                    data: 'detail',
                    name: 'detail',
                    orderable: true,
                    searchable: true
                },
                {
                    data: 'date',
                    name: 'date',
                    orderable: true,
                    searchable: true
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });
    </script>
@endpush
