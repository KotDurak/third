$(function(){

    $('body').on ('click','.ajax-ban' ,function(e){
        e.preventDefault();
        var D_elem = $(this);
        var url = D_elem.attr('href');
        var pjax = D_elem.attr('pjax-container');
        bootbox.confirm({
            message: D_elem.text() + " этого сотрудника?",
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

  /*  $('#add-user').on('click', function (e) {
        e.preventDefault();
        var D_elem = $(this);
        var link = D_elem.attr('href');
         $('#add').modal('show').find('.modal-dialog').load(link);

    }); */
});