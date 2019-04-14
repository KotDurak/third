$(function(){
    $('#select-project').on('change', function(e){
        var D_elem = $(this);
        var id_project = D_elem.val();
        $.post('/site/admin-task-projects?id_project=' + D_elem.val(), function(data){
            $('#task-by-project').html(data);
        });
    });
});