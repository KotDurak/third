$(function(){
    $('#create-chain').on('click', function(e){
        e.preventDefault();
        var D_elem = $(this);
        $('#add-chain').modal('show').find('.modal-dialog').load(D_elem.attr('href'));
    });

    $('.chain-list').on('click', '.step-table input[type="checkbox"]', function(e){
        var D_elem = $(this);
        var D_table = D_elem.closest('.step-table');
        var keys = D_table.yiiGridView('getSelectedRows');
        
    });
});