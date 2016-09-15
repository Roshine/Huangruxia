if (typeof jQuery === 'undefined') {
    throw new Error('genolyses\'s JavaScript requires jQuery');
}

function csrf_init(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
}

//function navbarActiveChange(id)
//{
//    $("#navbar-collapse ul li#"+id).addClass("active");
//}

function menuActiveChange(id)
{
    var node = $('li #' + id);
    node.addClass('active');
    node.parent().parent().addClass('active');
}

//get url中的字段值
function getQueryString(name) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
    var r = window.location.search.substr(1).match(reg);
    if (r != null) return unescape(r[2]); return null;
}