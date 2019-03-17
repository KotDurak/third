$(function(){
   $('.rework').on('click', function (e) {
       e.preventDefault();
       var D_elem = $(this);
       var link = D_elem.attr('modal-url');
       $('#add-comment').modal('show').find('.modal-dialog').load(link);
   });


   $('.notice-comment').on('click', function (e) {
        e.preventDefault();

   });
});