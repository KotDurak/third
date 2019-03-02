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

    $('body').on('click' , '.list-content th a', function(e){
        var D_elem = $(this);
        var url = window.location.href;
        url = url.replace(/list\?/, 'index?');
        window.history.pushState(null, null, url);
        setTimeout(function(){
            window.history.pushState(null, null, url);
        }, 1000);
    });

    $('body').on('click', '.proj-import', function (e) {
        e.preventDefault();
        var D_elem = $(this);
        var url = D_elem.attr('href');
        $('#import').modal('show').find('.modal-dialog').load(url);
    });
});