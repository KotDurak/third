$(function(){
    $('#select-project').on('change', function(e){
        var D_elem = $(this);
        var id_project = D_elem.val();
        $.post('/site/project-tasks?id_project=' + D_elem.val(), function(data){
            $('#project-container').html(data);
        });
    });
});