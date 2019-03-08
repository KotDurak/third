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

    $('.chain-list').on('click', '.ajaxDelete', function(e){
        e.preventDefault();
        var D_elem = $(this);
        var url = D_elem.attr('delete-url');
        var pjax = D_elem.attr('pjax-container');
        bootbox.confirm({
            message: "Хотите удалить этот шаг?",
            buttons: {
                confirm: {
                    label: 'Да',
                    className: 'btn-success'
                },
                cancel: {
                    label: 'Нет',
                    className: 'btn-danger'
                }
            },
            callback: function (result) {
               if(result){
                   $.ajax({
                        url:url,
                        type:  'post',
                        success:function(data){
                            $.pjax.reload({container: '#' + $.trim(pjax)});
                        }

                   });
               }
            }
        });
    });

});