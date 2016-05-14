$(function(){
    $(document)
        .on('click', '.create-comment', function(e) {
            e.preventDefault();
            $('#modal-new-comment').modal('show');
        })
        .on('submit', '#comment-form', function(e) {
            e.preventDefault();

            $.ajax({
                url: $(this).attr('action'),
                type:'post',
                dataType: 'json',
                data: $(this).serialize(),
                success: function(data) {
                    if (data.status) {
                        $('#modal-new-comment .modal-body').html('<div style="color: #3C763D;padding: 30px 0;text-align: center;font-size: 16px;">Ваш отзыв появится после проверки модератором!</div>');
                        $('#modal-new-comment .modal-footer').hide();

                        setTimeout(function() {
                            $('#modal-new-comment').modal('hide');
                        }, 3000);
                    }
                }
            })
        });
});