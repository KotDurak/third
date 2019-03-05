$(function(){
    $('#create-chain').on('click', function(e){
        e.preventDefault();
        var D_elem = $(this);
        $('#add-chain').modal('show').find('.modal-dialog').load(D_elem.attr('href'));
    });
});