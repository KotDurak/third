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
    })
});