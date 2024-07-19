<style>
    .modal-dialog {
        min-width: 55vw;
    }
</style>
<div class="modal fade" id="infoStudentModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">

        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Student Info</h5>
            </div>

            <table class="table" id="studentTable">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Email ID</th>
                        <th scope="col">Mobile no.</th>
                        <th scope="col">Qualification</th>
                    </tr>
                </thead>
                <tbody id="studenttable">
                </tbody>
            </table>
        </div>
    </div>
</div>






<script>
    function renderStudentTable(studentData) {
        var html = "";
        studentData.forEach(function(data, index) {
            html += `
            <tr>
                <td>${index + 1}</td>
                <td>${data.name}</td>
                <td>${data.email}</td>
                <td>${data.contact}</td>
                <td>${data.qualification ? data.qualification : 'N/A'}</td>
            </tr>`;
        });
        return html;
    }

    function showStudentModal(html) {
        $('#studenttable').html(html);
        $('#infoStudentModal').modal('show');
    }

    function loadModal(item = '') {
        $.ajax({
            url: "{{ route('studentDashboardData') }}?search=" + item,
            type: "GET",
            success: function(data) {
                var studentData = data.students.data;
                // var filteredData = studentData.filter(function(student) {
                //     return student.name === item;
                // });

                if (studentData.length > 0) {
                    var html = renderStudentTable(studentData);
                    showStudentModal(html);
                } else {
                    var noDataHtml = `<tr><td colspan="4">No Students Found!</td></tr>`;
                    showStudentModal(noDataHtml);
                }
            }
        });
    }

    $(document).on('click', ".user", function(e) {
        // loadTable(e.target.value);
        loadModal($(this).text());
    });
</script>
