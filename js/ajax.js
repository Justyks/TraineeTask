//Авторизация
$( document ).ready(function() {
    $('#signin_form').submit(function(e) {
        var $form = $(this);
        $.ajax({
            type: $form.attr('method'),
            url: 'index.php',
            dataType: 'json',
            data: $form.serialize()
            }).done(function(response) {
                if(response.accept){
                    document.location.href = '/main';
                }else{
                    document.location.href = '/signin';
                }
            }).fail(function() {
                console.log('fail');
        });
        e.preventDefault(); 
    });
});
//Регистрация
$( document ).ready(function() {
    $('#signup_form').submit(function(e) {
        var $form = $(this);
        $.ajax({
            type: $form.attr('method'),
            url: 'index.php',
            dataType: 'json',
            data: $form.serialize()
            }).done(function(response) {
                if(response.accept){
                    document.location.href = '/main';
                }else{
                    document.location.href = '/signup';
                }
            }).fail(function() {
                console.log('fail');
        });
        e.preventDefault(); 
    });
});

