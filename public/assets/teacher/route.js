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
        $('#main').load('./src/teacher/pre_class.html',function(){
            load_done();
        }).show();
    });

    $.routes.add('/preStuList', function(){
        NProgress.start();
        $('#main').load('./src/teacher/preStuList.html',function(){
            load_done();
        }).show();
    });

    $.routes.add('/preStuDetail', function(){
        NProgress.start();
        $('#main').load('./src/teacher/preStuDetail.html',function(){
            load_done();
        }).show();
    });

    $.routes.add('/pre_create_temp', function(){
        NProgress.start();
        $('#main').load('./src/teacher/pre_create_temp.html',function(){
            load_done();
        }).show();
    });

    $.routes.add('/pre_temp_detail', function(){
        NProgress.start();
        $('#main').load('./src/teacher/pre_temp_detail.html',function(){
            load_done();
        }).show();
    });
    $.routes.add('/exp_test', function(){
        NProgress.start();
        $('#main').load('./src/teacher/exp_test.html',function(){
            load_done();
        }).show();
    });

    $.routes.add('/exp_stu_list', function(){
        NProgress.start();
        $('#main').load('./src/teacher/exp_stu_list.html',function(){
            load_done();
        }).show();
    });

    $.routes.add('/exp_stu_detail', function(){
        NProgress.start();
        $('#main').load('./src/teacher/exp_stu_detail.html',function(){
            load_done();
        }).show();
    });

    $.routes.add('/exp_create_temp', function(){
        NProgress.start();
        $('#main').load('./src/teacher/exp_create_temp.html',function(){
            load_done();
        }).show();
    });

    $.routes.add('/exp_temp_detail', function(){
        NProgress.start();
        $('#main').load('./src/teacher/exp_temp_detail.html',function(){
            load_done();
        }).show();
    });
    $.routes.add('/homework', function(){
        NProgress.start();
        $('#main').load('./src/teacher/homework.html',function(){
            load_done();
        }).show();
    });

    $.routes.add('/hom_stu_list', function(){
        NProgress.start();
        $('#main').load('./src/teacher/hom_stu_list.html',function(){
            load_done();
        }).show();
    });

    $.routes.add('/hom_stu_detail', function(){
        NProgress.start();
        $('#main').load('./src/teacher/hom_stu_detail.html',function(){
            load_done();
        }).show();
    });

    $.routes.add('/hom_create_temp', function(){
        NProgress.start();
        $('#main').load('./src/teacher/hom_create_temp.html',function(){
            load_done();
        }).show();
    });

    $.routes.add('/hom_temp_detail', function(){
        NProgress.start();
        $('#main').load('./src/teacher/hom_temp_detail.html',function(){
            load_done();
        }).show();
    });

    $.routes.add('/hom_auto_create', function(){
        NProgress.start();
        $('#main').load('./src/teacher/hom_auto_create.html',function(){
            load_done();
        }).show();
    });

    $.routes.add('/hom_auto_detail', function(){
        NProgress.start();
        $('#main').load('./src/teacher/hom_auto_detail.html',function(){
            load_done();
        }).show();
    });

    $.routes.add('/week_sum', function(){
        NProgress.start();
        $('#main').load('./src/teacher/week_sum.html',function(){
            load_done();
        }).show();
    });

    $.routes.add('/sum_stu_list', function(){
        NProgress.start();
        $('#main').load('./src/teacher/sum_stu_list.html',function(){
            load_done();
        }).show();
    });

    $.routes.add('/sum_subm_detail', function(){
        NProgress.start();
        $('#main').load('./src/teacher/sum_subm_detail.html',function(){
            load_done();
        }).show();
    });

    $.routes.add('/fill_grades', function(){
        NProgress.start();
        $('#main').load('./src/teacher/fill_grades.html',function(){
            load_done();
        }).show();
    });


    $.routes.default('/pre_class');
    $.routes.start();
});


