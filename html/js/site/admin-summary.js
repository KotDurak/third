$(function(){
    $('#select-project').on('change', function(e){
        var D_elem = $(this);
        var id_project = D_elem.val();
        $.post('/site/admin-task-projects?id_project=' + D_elem.val(), function(data){
            $('#task-by-project').html(data);
        });
    });

    $('body').on('change', '.select-user', function (e) {
        var D_elem = $(this);
        var id_user = D_elem.val();
        $.post('/site/admin-task-user?id_user=' + D_elem.val(), function (data) {
            $('#task-by-user').html(data);
        });

        $.ajax({
            type:'post',
            url:'/site/user-groups?id_user=' + id_user,
            success:function(data){
                $('#positions').html(data);
            }
        });
    });
/*    $('.select-user').on('change', function (e) {
        var D_elem = $(this);
        var id_user = D_elem.val();
        $.post('/site/admin-task-user?id_user=' + D_elem.val(), function (data) {
            $('#task-by-user').html(data);
        });

        $.ajax({
           type:'post',
           url:'/site/user-groups?id_user=' + id_user,
           success:function(data){
               $('#positions').html(data);
           }
        });
    }); */

    $('#date-from').on('change', function (e) {
        var D_elem = $(this);
        var val = D_elem.val();
        var data = {
            from:val,
            to:$('#date-to').val()
        };
        $.ajax({
            type:'post',
            url:'/site/admin-tasks-date',
            data:data,
            success:function(data){
                $('#date-contaier').html(data);
            }
        });

    });

    $('#date-to').on('change', function(e){
        var D_elem = $(this);
        var val = D_elem.val();
        var data = {
            from:$('#date-from').val(),
            to:val
        };
        $.ajax({
            type:'post',
            url:'/site/admin-tasks-date',
            data:data,
            success:function(data){
                $('#date-contaier').html(data);
            }
        });

    });

    $('#select-group').on('change', function(e){
        var D_elem = $(this);
        var val = D_elem.val();
        var data = {
            id_group:val
        };
        $.ajax({
           type:'post',
           url:'/site/users-select',
           data:data,
           success:function(data){
                $('#users-contaier').html(data);
           }
        });
    });
});