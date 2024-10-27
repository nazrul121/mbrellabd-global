<div class="table-responsive">
    <table class="table table-hover applicantTable">
        <thead>
            <tr>
                <th>Applicant info</th>
                <th>Cover letter</th>
                <th>CV / Resume</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
       
    </table>
</div>

<div class="modal fade" id="moreModal" tabindex="-1" role="dialog" aria-labelledby="moreModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form id="editForm" class="modal-content" method="post" enctype="multipart/form-data"> @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Cover Letter</h5>
                <button type="button" class="closeModal"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body coverLetter">
               
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary closeModal">Close</button>
            </div>
        </form>
    </div>
</div>



<script>
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    $(document).ready(function () {
      
        $(function () { table.ajax.reload(); });

        let table = $('.applicantTable').DataTable({
            processing: true,serverSide: true,
            "language": { processing: '<img src="'+url+'/storage/images/ajax-loader.gif">'},
            ajax: "{{route('common.career.applicants',$career->id)}}",
            order: [ [0, 'desc'] ],
            columns: [
                {data: 'applicant_info', orderable: false, searchable: false},
                {data: 'cover_latter', orderable: false, searchable: false},
                {data: 'resume', orderable: false, searchable: false},
                {data: 'date', orderable: false, searchable: false},
                {data: 'modify', orderable: false, searchable: false, class:'text-right'}
            ]
        });

        $('.applicantTable').on('click','.more', function(){
            $('#moreModal').modal('show');
            var letter = $(this).data('letter');
            $('.coverLetter').html('<pre style="white-space:pre-line;">'+letter+'</pre>');

        })

        $('.closeModal').on('click', function(){
            $('#moreModal').modal('hide');
            
        })

    });
    
</script>