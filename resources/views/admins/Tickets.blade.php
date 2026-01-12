@extends('templates.dashboard')

@section('app_name', config('app.name'))
@section('title', 'Tickets')

@section('content')
<link rel="stylesheet" href="https://unpkg.com/@yaireo/tagify/dist/tagify.css">
<style>
.tag-item {
    display: inline-block;
    background: #0d6efd;
    color: #fff;
    padding: 4px 8px;
    border-radius: 4px;
    margin: 2px;
    font-size: 13px;
}
.tag-item span {
    cursor: pointer;
    margin-left: 6px;
    font-weight: bold;
}
</style>

<div class="row">
    <div class="col-12 my-4">
        <h2 class="h3 mb-1 text-primary">Tickets</h2>
        <p class="mb-3 text-dark">Manage tickets information</p>
        @if (session('success'))
        <div class="mb-3 alert alert-success">
            {{ session('success') }}
        </div>
        @endif
        @if (session('error'))
        <div class="mb-3 alert alert-danger">
            {{ session('error') }}
        </div>
        @endif

        <div class="modal fade" id="NewModal" tabindex="-1" role="dialog" aria-labelledby="NewModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-primary" id="NewModalLabel">New Ticket</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('tickets.create') }}" method="post">
                        <div class="modal-body text-dark">
                            @csrf
                            <div class="form-group">
                                <label for="event_id">Event</label>
                                <select class="form-control" id="event_id" name="event_id" required>
                                    <option value="" selected disabled>Select Event</option>
                                    @foreach($events as $event)
                                    <option value="{{ $event->id }}" {{ old('event_id') == $event->id ? 'selected' : '' }}>{{ $event->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter Ticket Name" required value="{{ old('name') }}">
                            </div>
                            <div class="form-group">
                                <label for="name">Value Option</label>
                                <input class="form-control" id="option" name="tags" placeholder="Enter Value">
                            </div>
                            <div class="form-group">
                                <label for="name">Value Option</label>
                                <input type="text" class="form-control" id="tag-input" placeholder="Enter Value">
                                <div id="tag-container" class="mt-2"></div>
                            </div>
                            <div class="form-group">
                                <label>Description</label>
                                <textarea class="form-control" id="description" name="description" required placeholder="Enter Description" rows="4">{{ old('description') }}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="total">Total Tickets</label>
                                <input type="number" class="form-control" id="total" name="total" placeholder="Enter Total Tickets" required value="{{ old('total') }}" min="1">
                            </div>
                            <div class="form-group">
                                <label for="price">Price (IDR)</label>
                                <input type="number" class="form-control" id="price" name="price" placeholder="Enter Price" required value="{{ old('price') }}" min="0">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn mb-2 btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn mb-2 btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="EditModal" tabindex="-1" role="dialog" aria-labelledby="EditModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-primary" id="EditModalLabel">Edit Ticket</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="loading-spinner text-center py-4">
                        <div class="fa fa-spinner fa-spin fa-2x text-primary"></div>
                        <p class="mt-2 mb-0">Loading...</p>
                    </div>
                    <form action="{{ route('tickets.update') }}" method="post" style="display: none;">
                        <div class="modal-body text-dark">
                            @csrf
                            <input type="hidden" id="edit_id" name="id">
                            <div class="form-group">
                                <label for="edit_event_id">Event</label>
                                <select class="form-control" id="edit_event_id" name="event_id" required>
                                    <option value="" disabled>Select Event</option>
                                    @foreach($events as $event)
                                    <option value="{{ $event->id }}">{{ $event->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="edit_name">Name</label>
                                <input type="text" class="form-control" id="edit_name" name="name" placeholder="Enter Ticket Name" required>
                            </div>
                            <div class="form-group">
                                <label>Description</label>
                                <textarea class="form-control" id="edit_description" name="description" required placeholder="Enter Description" rows="4"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="edit_total">Total Tickets</label>
                                <input type="number" class="form-control" id="edit_total" name="total" placeholder="Enter Total Tickets" required min="1">
                            </div>
                            <div class="form-group">
                                <label for="edit_price">Price (IDR)</label>
                                <input type="number" class="form-control" id="edit_price" name="price" placeholder="Enter Price" required min="0">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn mb-2 btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn mb-2 btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="DeleteModal" tabindex="-1" role="dialog" aria-labelledby="DeleteModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-primary" id="DeleteModalLabel">Delete Ticket</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('tickets.delete') }}" method="post">
                        <div class="modal-body text-dark">
                            @csrf
                            <input type="hidden" id="delete_id" name="id">
                            <p>Are you sure want to delete this ticket?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn mb-2 btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn mb-2 btn-danger">Delete</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="card shadow">
            <div class="card-body">
                <div class="mb-3 d-flex justify-content-end">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#NewModal"><i class="fa fa-plus"></i> New Ticket</button>
                </div>
                <div class="table-responsive">
                    <table id="ticket-table" class="table table-bordered table-striped datatables" style="width:100%">
                        <thead class="thead-light">
                            <tr>
                                <th>NO</th>
                                <th>EVENT NAME</th>
                                <th>TICKET NAME</th>
                                <th>TOTAL</th>
                                <th>PRICE</th>
                                <th>ACTION</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/@yaireo/tagify"></script>
<script>
new Tagify(document.querySelector('input[name=tags]'));
</script>
<script>
    $('#tag-input').on('keyup', function(e) {
        if (e.key === ',') {
            let value = this.value.replace(',', '').trim();
            if (value !== '') {
                let tag = $('<span class="tag-item">')
                    .text(value)
                    .append('<span>&times;</span>');

                $('#tag-container').append(tag);
                this.value = '';
            }
        }
    });

    $(document).on('click', '.tag-item span', function() {
        $(this).parent().remove();
    });
</script>
<script>
    CKEDITOR.replace('description', {
        toolbar: [{
            name: 'basicstyles',
            items: ['Bold', 'Italic', 'Underline', 'Strike', '-', 'RemoveFormat']
        }, {
            name: 'paragraph',
            items: ['NumberedList', 'BulletedList']
        }],
        removePlugins: 'elementspath',
        resize_enabled: false,
        height: 150
    });
    CKEDITOR.replace('edit_description', {
        toolbar: [{
            name: 'basicstyles',
            items: ['Bold', 'Italic', 'Underline', 'Strike', '-', 'RemoveFormat']
        }, {
            name: 'paragraph',
            items: ['NumberedList', 'BulletedList']
        }],
        removePlugins: 'elementspath',
        resize_enabled: false,
        height: 150
    });

    $(function() {
        $('#ticket-table').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: "{{ route('tickets.data') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'event_name',
                    name: 'events.name'
                },
                {
                    data: 'name',
                    name: 'tickets.name'
                },
                {
                    data: 'total',
                    name: 'total'
                },
                {
                    data: 'price',
                    name: 'price',
                    render: function(data, type, row) {
                        return 'IDR ' + parseInt(data).toLocaleString('id-ID');
                    }
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'id',
                    visible: false,
                    searchable: false
                }
            ]
        });
    });

    $(document).on('click', '[data-target="#EditModal"]', function() {
        let id = $(this).data('id');

        $('#EditModal form').hide();
        $('#EditModal .loading-spinner').show();
        $('#EditModal').modal('show');

        $.get("{!! route('tickets.data.id', ':id') !!}".replace(':id', id), function(data) {
            $('#edit_id').val(data.id);
            $('#edit_event_id').val(data.event_id);
            $('#edit_name').val(data.name);
            CKEDITOR.instances.edit_description.setData(data.description);
            $('#edit_total').val(data.total);
            $('#edit_price').val(data.price);

            $('#EditModal .loading-spinner').hide();
            $('#EditModal form').fadeIn();
        }).fail(function() {
            alert("Gagal mengambil data tiket.");
            $('#EditModal').modal('hide');
        });
    });

    $(document).on('click', '[data-target="#DeleteModal"]', function() {
        let id = $(this).data('id');
        $('#delete_id').val(id);
    });
</script>

@endsection