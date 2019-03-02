$(function(){
    $('#create-project').on('click', function(e){
        e.preventDefault();
        var D_elem = $(this);
        $('.add').modal('show').find('.modal-dialog').load(D_elem.attr('href'));

    });

    $('body').on('click', '.proj-edit', function(e){
        e.preventDefault();
        var D_elem = $(this);
        var url = D_elem.attr('href');

       $('.add').modal('show').find('.modal-dialog').load(url);
    });

    $('body').on('click', '.proj-delete', function(e){
       e.preventDefault();
       var D_elem = $(this);
       var url = D_elem.attr('href');
       $('#delete').modal('show').find('.modal-dialog').load(url);
    });
});