$(document).ready(function(){
	var list=$(".summary ul li");
	list.on('click',function(){
		var weekId=$(this).attr('id');
		window.open('#/sum_stu_list?week='+weekId);
		//window.location.href='#/sum_stu_list?week='+weekId;
	})
})