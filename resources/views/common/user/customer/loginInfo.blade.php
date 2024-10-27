<p class="loginResult"></p>

<div class="loginInfoArea">
    <p class="text-right updateLogin"><a href="#" class="text-warning">Update login info</a></p>
    <p class="alert alert-info"><b>Email</b>: {{ $user->email }}</p>
    <p class="alert alert-info"><b>Mobile No</b>: {{ $user->phone }}</p>
</div>

<form id="loginForm" action="{{route('common.update-customer-login', $user->id)}}" class="updateForm" method="post" style="display:none"> @csrf
    <div class="form-group">
        <input type="text" class="form-control" value="{{$user->email}}" placeholder="Login email" name="email">
    </div>

    <div class="form-group">
        <input type="text" class="form-control" value="{{$user->phone}}" placeholder="login phone" name="phone" required>
    </div>

    <div class="form-group">
        <input type="password" class="form-control"  placeholder="set new Password" name="password" required>
    </div>

    <button class="mt-2 btn btn-info float-right mr-0"><i class="fa fa-edit"></i> Edit Login info</button>
    
</form>

<script>
    $(function(){
        $('.updateLogin').on('click', function(){
            $('.updateForm').fadeToggle(150);
            $('.loginInfoArea').slideUp();
        });

        $("#loginForm").submit(function(event) {
            event.preventDefault();

            $("[type='submit']").html(' Loading...');$('.loginResult').html('');
            $("[type='submit']").prop('disabled',true);
            var form = $(this);var url = form.attr('action');
            var html = '';
            $.ajax({
                url:url, method:"post", data: new FormData(this),
                contentType: false,cache:false, processData: false,
                dataType:"json",
                success:function(data){
                    if(data.errors) {
                        html = '<div class="alert alert-warning alert-dismissible fade show" role="alert"><strong class="text-danger">Warning! <br/> </strong>';
                        for(var count = 0; count < data.errors.length; count++)
                        { html += '<button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span></button>' + data.errors[count] + '</p>';break;}
                        html += '</div>';
                    }
                    if(data.success){
                        html = '<div class="alert alert-success alert-dismissible fade show" role="alert"><strong class="text-info">Success! </strong> ' + data.success +'</div>';
                        setTimeout(function() { 
                            $('.loginInfoArea').slideDown();
                            $('.updateForm').slideUp();
                        }, 1000);
                    }
                    $("[type='submit']").text('<i class="fa fa-edit"></i> Edit Login info');
                    $("[type='submit']").prop('disabled',false);
                    $('.loginResult').html(html);
                }
            });
        });
    })
</script>