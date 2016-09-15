function load_done(){
    NProgress.done();
}

function navbarActiveChange(id)
{
    $("#navbar-collapse ul li").removeClass("active");
    if(id)
        $("#navbar-collapse ul li#"+id).addClass("active");
}

$(document).ready(function(){
    csrf_init();



    NProgress.configure({ showSpinner: false });

    $.routes.add('/login', function(){
        NProgress.start();
        $('#main').load('../src/auth/login.html',function(){
            load_done();
        }).show();
    });

    $.routes.add('/register', function(){
        NProgress.start();
        $('#main').load('../src/auth/register.html',function(){
            load_done();
        }).show();
    });

    $.routes.add('/forget-password', function(){
        NProgress.start();
        $('#main').load('../src/auth/forget_password.html',function(){
            load_done();
        }).show();
    });

    $.routes.add('/reset-password', function(){
        NProgress.start();
        $('#main').load('./src/auth/reset_password.html',function(){
            load_done();
        }).show();
    });

    $.routes.add('/register-active', function(){
        NProgress.start();
        $('#main').load('./src/auth/register_active.html',function(){
            load_done();
        }).show();
    });

    $.routes.default('/login');
    $.routes.start();
});