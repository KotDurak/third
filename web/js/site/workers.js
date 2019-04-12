$(function(){
    $('#select-project').on('change', function(e){
        var D_elem = $(this);
        var id_project = D_elem.val();
        $.post('/site/project-tasks?id_project=' + D_elem.val(), function(data){
            $('#project-container').html(data);
        });
    });

    $('#date-from').on('change', function (e) {
        var D_elem = $(this);
        var val = D_elem.val();
        var data = {
            from:val,
            to:$('#date-to').val()
        };
        $.ajax({
            type:'post',
            url:'/site/users-tasks-date',
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
            url:'/site/users-tasks-date',
            data:data,
            success:function(data){
                $('#date-contaier').html(data);
            }
        });

    });
});