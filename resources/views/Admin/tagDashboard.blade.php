@extends('layout.admin-dashboard')

@section('content')
    <div id="content" class="p-4 p-md-5 pt-5">
        <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addTagsModal">
            Add Tag
        </button>

        <h2 class="mb-4">Tags </h2>
        <br>

        <table class="table">
            <thead>
                <th>#</th>
                <th>Tag Name</th>
                <th>Delete</th>
            </thead>
            <tbody id="tagsTable">

            </tbody>
        </table>
    </div>


    <!--Add tag Modal -->
    <div class="modal fade" id="addTagsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Add Tags</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="addTag">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="tagId" id="tagId">
                        <input type="text" name="tag_name" id="tag_name">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!--Delete tag Model-->
    <div class="modal fade" id="deleteTagModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Delete Tag</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="deleteTag">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col">
                                <input type="hidden" name="id" id="tag_id">
                                <p>Are You sure to Delete?</p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <span class="error" style="color:red"></span>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Delete Tag</button>
                    </div>
                </form>
            </div>
        </div>
    </div>




    <script>
        $(document).ready(function() {
            const options = {
                weekday: 'short',
                month: 'short',
                day: 'numeric',
                year: 'numeric',
                hour: 'numeric',
                minute: 'numeric'
            };



            function loadTable() {

                $.ajax({
                    url: "{{ route('tagDashboardData') }}",
                    type: "GET",
                    success: function(response) {
                        if (response.success == true && response.data.length > 0) {
                            var tagData = response.data;
                            var html = "";
                            tagData.map(function(data, index) {
                                console.log(data)
                                html += `
                                        <tr>
                                            <td>${index + 1}</td>
                                            <td>${data.tag_name}</td>
                                            <td>
                                            <button type="button" class="btn btn-danger deleteButton" data-toggle="modal"
                                            data-id="${data.id}" data-target="#deleteTagModal">
                                            Delete
                                            </button>
                                            </td>
                                        </tr>
                                        `;
                            })
                            $('#tagsTable').html(html);
                        } else {
                            html = "";
                            html += `<tr><td colspan="2">No Tag Found!</td></tr>`;
                            $('#tagsTable').html(html);
                        }
                    }
                })
            }
            loadTable();

            //Add Student
            $("#addTag").submit(function(e) {
                e.preventDefault();

                var formData = $(this).serialize();

                $.ajax({
                    url: "{{ route('addTag') }}",
                    type: "POST",
                    data: formData,
                    success: function(data) {
                        if (data.success == true) {
                            toastr.options = {
                                'timeOut': 900,
                                'closeButton': true,
                                'progressBar': true,
                                "positionClass": "toast-top-center",
                            };
                            toastr.success("", data.msg);
                            $("#addTag")[0].reset();
                            $('#addTagsModal').modal('hide')
                            loadTable();
                        } else {
                            toastr.options = {
                                'timeOut': 900,
                                'closeButton': true,
                                'progressBar': true,
                                "positionClass": "toast-top-center",
                            };

                            if (data && data.msg && data.msg.tag_name && data.msg.tag_name[0]) {
                                toastr.error(data.msg.tag_name[0]);
                            }
                        }
                    }
                });
            });

            //Delete
            $(document).on('click', ".deleteButton", function() {
                var id = $(this).attr("data-id");
                $("#tag_id").val(id);
            });

            $("#deleteTag").submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                console.log(formData);
                $.ajax({
                    url: "{{ route('deleteTag') }}",
                    type: "POST",
                    data: formData,
                    success: function(data) {
                        if (data.success == true) {
                            toastr.options = {
                                'timeOut': 900,
                                'closeButton': true,
                                'progressBar': true,
                                "positionClass": "toast-top-center",
                            };
                            toastr.error("", data.msg);
                            $('#deleteTagModal').modal('hide')
                            loadTable();
                        } else {
                            alert(data.msg)
                        }
                    }
                });
            });

        });
    </script>
@endsection
