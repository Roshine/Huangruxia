$(function(){
	$('button').on('click',function(){
		var id=this.id;
		var senddata=id.replace(/week/,'');
		$.post('fillChapterScore',{"chapterId":senddata},function(data){
			//console.log(data);
			if(data.error==0){
				alert('本章已有成绩计算成功！')
			}else{
				alert('网络错误！稍后计算或联系管理员。');
			}
		})
	})
})