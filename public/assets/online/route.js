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

    $.routes.add('/pre_class', function(){
        NProgress.start();
        $('#main').load('./src/online/pre_class.html',function(){
            load_done();
        }).show();
    });
     $.routes.add('/pre_fillin', function(){
        NProgress.start();
        $('#main').load('./src/online/pre_fillin.html',function(){
            load_done();
        }).show();
    });

    $.routes.add('/pre_subm_detail', function(){
        NProgress.start();
        $('#main').load('./src/online/pre_subm_detail.html',function(){
            load_done();
        }).show();
    });

    $.routes.add('/pre_only_view', function(){
        NProgress.start();
        $('#main').load('./src/online/pre_only_view.html',function(){
            load_done();
        }).show();
    });

    $.routes.add('/exp_test', function(){
        NProgress.start();
        $('#main').load('./src/online/exp_test.html',function(){
            load_done();
        }).show();
    });
     $.routes.add('/exp_fillin', function(){
        NProgress.start();
        $('#main').load('./src/online/exp_fillin.html',function(){
            load_done();
        }).show();
    });

    $.routes.add('/exp_subm_detail', function(){
        NProgress.start();
        $('#main').load('./src/online/exp_subm_detail.html',function(){
            load_done();
        }).show();
    });

    $.routes.add('/exp_only_view', function(){
        NProgress.start();
        $('#main').load('./src/online/exp_only_view.html',function(){
            load_done();
        }).show();
    });


    $.routes.add('/homework', function(){
        NProgress.start();
        $('#main').load('./src/online/homework.html',function(){
            load_done();
        }).show();
    });
    $.routes.add('/hom_fillin', function(){
        NProgress.start();
        $('#main').load('./src/online/hom_fillin.html',function(){
            load_done();
        }).show();
    });

    $.routes.add('/hom_subm_detail', function(){
        NProgress.start();
        $('#main').load('./src/online/hom_subm_detail.html',function(){
            load_done();
        }).show();
    });

    $.routes.add('/hom_only_view', function(){
        NProgress.start();
        $('#main').load('./src/online/hom_only_view.html',function(){
            load_done();
        }).show();
    });
    $.routes.add('/test', function(){
        NProgress.start();
        $('#main').load('./src/online/test.html',function(){
            load_done();
        }).show();
    });
    $.routes.add('/week_sum', function(){
        NProgress.start();
        $('#main').load('./src/online/sum_week.html',function(){
            load_done();
        }).show();
    });

    $.routes.add('/sum_fillin', function(){
        NProgress.start();
        $('#main').load('./src/online/sum_fillin.html',function(){
            load_done();
        }).show();
    });

    $.routes.add('/sum_subm_detail', function(){
        NProgress.start();
        $('#main').load('./src/online/sum_subm_detail.html',function(){
            load_done();
        }).show();
    });

    $.routes.add('/sum_fillin_group', function(){
        NProgress.start();
        $('#main').load('./src/online/sum_fillin_group.html',function(){
            load_done();
        }).show();
    });

    $.routes.add('/sum_subm_detail_group', function(){
        NProgress.start();
        $('#main').load('./src/online/sum_subm_detail_group.html',function(){
            load_done();
        }).show();
    });

    $.routes.add('/my_grades', function(){
        NProgress.start();
        $('#main').load('./src/online/my_grades.html',function(){
            load_done();
        }).show();
    });

    $.routes.add('/stuInfo', function(){
        NProgress.start();
        $('#main').load('./src/online/stuInfo.html',function(){
            load_done();
        }).show();
    });

    $.routes.default('/pre_class');
    $.routes.start();
});


