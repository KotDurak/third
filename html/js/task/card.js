$(function(){
   $('.rework').on('click', function (e) {
       e.preventDefault();
       var D_elem = $(this);
       var link = D_elem.attr('modal-url');
       $('#add-comment').modal('show').find('.modal-dialog').load(link);
   });


   $('.notice-comment').on('click', function (e) {
        e.preventDefault();
        var D_elem = $(this);
        var link = D_elem.attr('href');
        $('#comment').modal('show').find('.modal-dialog').load(link);
   });

   $('.attr-link').on('click', function(e){
      e.preventDefault();
      var D_elem = $(this);
      var link = D_elem.attr('href');
      $('#change-attr').modal('show').find('.modal-dialog').load(link);
   });

   $('.upload-task').on('click', function(e){
      e.preventDefault();
      var D_elem = $(this);
      var link = D_elem.attr('href');
      $('#upload').modal('show').find('.modal-dialog').load(link);
   });

   $('.add-external').on('click', function(e){
       e.preventDefault();
       var D_elem = $(this);
       var link = D_elem.attr('href');
       $('#external').modal('show').find('.modal-dialog').load(link);
   });

   $('[disabled]').on('click', function (e) {
        e.preventDefault();
        return false;
   });
});