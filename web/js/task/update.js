$(function(){
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