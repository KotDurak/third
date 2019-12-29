$(function(){
    $('#add-group').on('click', function(e){
        e.preventDefault();
        var D_elem = $(this);
        var link = D_elem.attr('href');
        $('#add').modal('show').find('.modal-dialog').load(link);
    });

    $('body').on('click', '.ajax-update', function(e){
        e.preventDefault();
        var D_elem = $(this);
        var link = D_elem.attr('href');
        $('#add').modal('show').find('.modal-dialog').load(link);
    });
});