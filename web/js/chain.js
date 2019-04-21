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

    $('body').on('click', '.ajaxDelete', function(e){
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

    $('body').on('click', '.delete-chain', function(e){
        e.preventDefault();
        var D_elem = $(this);
        var url = D_elem.attr('href');
        var pjax = 'chain-pjax';
        bootbox.confirm({
            message: "Хотите удалить эту цепочку?",
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

    $('body').on('click', '.add-step', function(e){
       e.preventDefault();
       var D_elem = $(this);
        $('#edit-step').modal('show').find('.modal-dialog').load(D_elem.attr('href'));
    });

    $('body').on('keyup', '.index-attr', function(e){
        var D_elem = $(this);
        var data = {
            index: $(this).val()
        };
        $.ajax({
            url:'/chain/check-uniq',
            type:'post',
            dataType:'json',
            data:data,
            success:function(data){
                var D_group = D_elem.closest('.form-group');
                if(!data.acc){
                   D_group.addClass('has-error');
                   var D_text = $('<p class="help-block help-block-error">Такое название для поля index уже существует</p>');
                   D_group.append(D_text);
                   $('#submit').attr('disabled', 'disabled');
                } else{
                    D_group.removeClass('has-error');
                    D_group.find('.help-block-error').remove();
                    $('#submit').removeAttr('disabled');
                }

            }
        });
    });

});