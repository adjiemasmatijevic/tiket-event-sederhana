@extends('templates.dashboard')

@section('app_name', config('app.name'))
@section('title', 'Events')

@section('content')
<div class="row">
    <div class="col-12 my-4">
        <h2 class="h3 mb-1 text-primary">Events</h2>
        <p class="mb-3 text-dark">manage events information</p>
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

        <!-- modal new -->
        <div class="modal fade" id="NewModal" tabindex="-1" role="dialog" aria-labelledby="NewModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-primary" id="NewModalLabel">New Events</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('events.create') }}" method="post" enctype="multipart/form-data">
                        <div class="modal-body text-dark">
                            @csrf
                            <div class="form-group">
                                <label for="image">Image</label>
                                <input type="file"
                                    class="form-control-file"
                                    id="image"
                                    name="image"
                                    accept=".png,.jpg,.jpeg"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" required value="{{ old('name') }}">
                            </div>
                            <div class="form-group">
                                <label>Description</label>
                                <textarea class="form-control" id="description" name="description" required
                                    placeholder="Enter Description" rows="4"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="location">Location</label>
                                <input type="text" class="form-control" id="location" name="location" placeholder="Enter Location" required value="{{ old('location') }}">
                            </div>
                            <div class="form-group">
                                <label for="time_start">Time Start</label>
                                <input type="datetime-local" class="form-control" id="time_start" name="time_start" required value="{{ old('time_start') }}">
                            </div>
                            <div class="form-group">
                                <label for="time_end">Time End</label>
                                <input type="datetime-local" class="form-control" id="time_end" name="time_end" required value="{{ old('time_end') }}">
                            </div>
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select class="form-control" id="status" name="status" required>
                                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="non active" {{ old('status') == 'not active' ? 'selected' : '' }}>Not Active</option>
                                </select>
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

        <!-- modal edit -->
        <div class="modal fade" id="EditModal" tabindex="-1" role="dialog" aria-labelledby="EditModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-primary" id="EditModalLabel">Edit Events</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="loading-spinner text-center py-4">
                        <div class="fa fa-spinner fa-spin fa-2x text-primary"></div>
                        <p class="mt-2 mb-0">Loading...</p>
                    </div>
                    <form action="{{ route('events.update') }}" method="post" enctype="multipart/form-data">
                        <div class="modal-body text-dark">
                            @csrf
                            <input type="hidden" id="edit_id" name="id">
                            <img src="" id="preview_image" alt="image" loading="lazy" class="img-fluid w-100 mb-3 rounded">
                            <div class="form-group">
                                <label for="edit_image">Image</label>
                                <input type="file"
                                    class="form-control-file"
                                    id="edit_image"
                                    name="image"
                                    accept=".png,.jpg,.jpeg">
                            </div>
                            <div class="form-group">
                                <label for="edit_name">Name</label>
                                <input type="text" class="form-control" id="edit_name" name="name" placeholder="Enter Name" required>
                            </div>
                            <div class="form-group">
                                <label>Description</label>
                                <textarea class="form-control" id="edit_description" name="description" required
                                    placeholder="Enter Description" rows="4"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="edit_location">Location</label>
                                <input type="text" class="form-control" id="edit_location" name="location" placeholder="Enter Location" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_time_start">Time Start</label>
                                <input type="datetime-local" class="form-control" id="edit_time_start" name="time_start" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_time_end">Time End</label>
                                <input type="datetime-local" class="form-control" id="edit_time_end" name="time_end" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_status">Status</label>
                                <select class="form-control" id="edit_status" name="status" required>
                                    <option value="active">Active</option>
                                    <option value="not active">Non Active</option>
                                </select>
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

        <!-- modal delete -->
        <div class="modal fade" id="DeleteModal" tabindex="-1" role="dialog" aria-labelledby="DeleteModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-primary" id="DeleteModalLabel">Delete Events</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('events.delete') }}" method="post">
                        <div class="modal-body text-dark">
                            @csrf
                            <input type="hidden" id="delete_id" name="id">
                            <p>Are you sure want to delete this ads?</p>
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
                <!-- table -->
                <div class="mb-3 d-flex justify-content-end">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#NewModal"><i class="fa fa-plus"></i> New Events</button>
                </div>
                <div class="table-responsive">
                    <table id="ad-table" class="table table-bordered table-striped datatables" style="width:100%">
                        <thead class="thead-light">
                            <tr>
                                <th>NO</th>
                                <th>NAME</th>
                                <th>LOCATION</th>
                                <th>TIME START</th>
                                <th>TIME END</th>
                                <th>STATUS</th>
                                <th>ACTION</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <!-- end table -->
            </div>
        </div>
    </div>
</div> <!-- end section -->

<script>
    CKEDITOR.replace('description', {
        toolbar: [{
                name: 'basicstyles',
                items: ['Bold', 'Italic', 'Underline', 'Strike', '-', 'Subscript', 'Superscript', '-', 'RemoveFormat']
            },
            {
                name: 'paragraph',
                items: ['NumberedList', 'BulletedList', '-', 'Blockquote', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock']
            },
            '/',
            {
                name: 'styles',
                items: ['Styles', 'Format', 'Font', 'FontSize']
            }
        ],
        removePlugins: 'elementspath',
        resize_enabled: false,
        height: 250
    });

    CKEDITOR.replace('edit_description', {
        toolbar: [{
                name: 'basicstyles',
                items: ['Bold', 'Italic', 'Underline', 'Strike', '-', 'Subscript', 'Superscript', '-', 'RemoveFormat']
            },
            {
                name: 'paragraph',
                items: ['NumberedList', 'BulletedList', '-', 'Blockquote', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock']
            },
            '/',
            {
                name: 'styles',
                items: ['Styles', 'Format', 'Font', 'FontSize']
            }
        ],
        removePlugins: 'elementspath',
        resize_enabled: false,
        height: 250
    });


    $(function() {
        $('#ad-table').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: "{{ route('events.data') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'location',
                    name: 'location'
                },
                {
                    data: 'time_start',
                    name: 'time_start'
                },
                {
                    data: 'time_end',
                    name: 'time_end'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
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
        if (!$('#EditModal .loading-spinner').length) {
            $('#EditModal .modal-body').prepend(`
            <div class="loading-spinner text-center py-4">
                <div class="fa fa-spinner fa-spin fa-2x text-primary"></div>
                <p class="mt-2 mb-0">Loading...</p>
            </div>
        `);
        } else {
            $('#EditModal .loading-spinner').show();
        }

        $('#EditModal').modal('show');

        $.get("{!! route('events.data.id', ':id') !!}".replace(':id', id), function(data) {
            $('#edit_id').val(data.id);
            $('#preview_image').attr('src', '/storage/event_images/' + data.image + '.webp');
            $('#edit_name').val(data.name);
            CKEDITOR.instances.edit_description.setData(data.description);
            $('#edit_location').val(data.location);
            $('#edit_time_start').val(data.time_start);
            $('#edit_time_end').val(data.time_end);
            $('#edit_status').val(data.status);
            $('#EditModal .loading-spinner').hide();
            $('#EditModal form').fadeIn();
        });
    });

    $(document).on('click', '[data-target="#DeleteModal"]', function() {
        let id = $(this).data('id');
        $('#delete_id').val(id);
    });
</script>
@endsection