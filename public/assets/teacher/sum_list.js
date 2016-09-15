$(document).ready(function(){
	var list=$(".summary ul li");
	list.on('click',function(){
		var weekId=$(this).attr('id');
		window.location.href='#/sum_stu_list?week='+weekId;
	})
})