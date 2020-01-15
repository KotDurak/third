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
      //  $.pjax.reload(options);
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

    $('#task-list').on('click', '.task-list input[type="checkbox"]', function(e){
        var D_elem = $(this);
        var D_table = D_elem.closest('.task-list');
        var keys = D_table.yiiGridView('getSelectedRows');
    });

    $('#task-change').on('click', function (e) {
        e.preventDefault();
        var D_elem = $(this);
        var old_url = D_elem.attr('href');
        var new_url = old_url;
        var keys = $('.task-list').yiiGridView('getSelectedRows');
        var delimetr = '?';
        for(var i in keys){
            new_url += delimetr + 'task[]=' + keys[i];
            delimetr = '&';
        }
        $('#change').modal('show').find('.modal-dialog').load(new_url);
        /*$.ajax({
           type:'post',
           url:new_url
        }); */
    });

    $('#task-copy').on('click', function(e){
        e.preventDefault();
        var D_elem = $(this);
        var old_url = D_elem.attr('href');
        var new_url = old_url;
        var keys = $('.task-list').yiiGridView('getSelectedRows');
        var pjax = 'task-list';
        var delimetr = '?';
        for(var i in keys){
            new_url += delimetr + 'task[]=' + keys[i];
            delimetr = '&';
        }
        bootbox.confirm({
            message: "Создать копии задач?",
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
                        url:new_url,
                        type:  'post',
                        success:function(data){
                            $.pjax.reload({container: '#' + $.trim(pjax)});
                        }
                    });
                }
            }
        });
    });
    $('#task-mass-change').on('click', function(e){
        e.preventDefault();
        var D_elem = $(this);
        var old_url = D_elem.attr('href');
        var new_url = old_url;
        var keys = $('.task-list').yiiGridView('getSelectedRows');
        var pjax = 'task-list';
        var delimetr = '?';
        for(var i in keys){
            new_url += delimetr + 'task[]=' + keys[i];
            delimetr = '&';
        }
        bootbox.confirm({
            message: "Отправить эти задачи в архив?",
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
                        url:new_url,
                        type:  'post',
                        success:function(data){
                            $.pjax.reload({container: '#' + $.trim(pjax)});
                        }
                    });
                }
            }
        });
    });
    $('#task-mass-accept').on('click', function(e){
        e.preventDefault();
        var D_elem = $(this);
        var old_url = D_elem.attr('href');
        var new_url = old_url;
        var keys = $('.task-list').yiiGridView('getSelectedRows');
        var pjax = 'task-list';
        var delimetr = '?';
        for(var i in keys){
            new_url += delimetr + 'task[]=' + keys[i];
            delimetr = '&';
        }
        bootbox.confirm({
            message: "Принять эти задачи?",
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
                        url:new_url,
                        type:  'post',
                        success:function(data){
                            $.pjax.reload({container: '#' + $.trim(pjax)});
                        }
                    });
                }
            }
        });
    });
    $('#task-mass-delete').on('click', function(e){
        e.preventDefault();
        var D_elem = $(this);
        var old_url = D_elem.attr('href');
        var new_url = old_url;
        var keys = $('.task-list').yiiGridView('getSelectedRows');
        var pjax = 'task-list';
        var delimetr = '?';
        for(var i in keys){
            new_url += delimetr + 'task[]=' + keys[i];
            delimetr = '&';
        }
        bootbox.confirm({
            message: "Удалить эти задачи?",
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
                        url:new_url,
                        type:  'post',
                        success:function(data){
                            window.location.reload();
                        }
                    });
                }
            }
        });
    });
});