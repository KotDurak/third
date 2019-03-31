$(function(){
    $('#count-rows').on('change', function (e) {
        var D_elem = $(this);
        var count = D_elem.val();
        var id_project = D_elem.data('project');
        var options = {
            type:'POST',
            url: '/task/list?id_project=' + id_project + '&page_size=' + count,
            container:'#task-list',
        };
        $.pjax.reload(options);
    });

    $('body').on('click', '.ajaxDelete', function(e){
        e.preventDefault();
        var D_elem = $(this);
        var url = D_elem.attr('delete-url');
        var pjax = D_elem.attr('pjax-container');
        bootbox.confirm({
            message: "Хотите удалить эту задачу?",
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

    $('#menu-import').on('click', function(e){
        e.preventDefault();
        var D_elem = $(this);
        var link = D_elem.attr('href');
        $('#import').modal('show').find('.modal-dialog').load(link);
    });

    $('body').on('change', '#chain-select', function(e){
        var D_elem = $(this);
        var id_chain = D_elem.val();
        var url = 'add-fields?id=' + id_chain;
        $.ajax({
            type:'post',
            url: url,
            success:function (data) {
                $('#chain-options').html(data);
            }
        });
    });
});