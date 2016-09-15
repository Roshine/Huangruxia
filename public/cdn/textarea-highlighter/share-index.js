/**
 * Created by coder on 2015/9/23.
 */

$(document).ready(function(){

    navbarActiveChange('share');

    $('#test').click(function(){
        var csrf = getCsrf();
        $.post("./index.php?r=share/test-home",{_csrf:csrf,data:"hello world"},function(){
            alert('ok');
        }).fail(function(){
            alert('fail');
        });
    });



});