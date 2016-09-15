$(document).ready(function(){
	var url= window.location.href;
	var parames=url.split("?")[1];
	parames=parames.split("&");
	var weekId=parames[0].split('=')[1];	
	////提交时
	$("#submit").on('click',function(){
		var summary=$("#week_sum").val();
		if (!summary) {
			alert('请填写本周总结');
		};
		let option=$("#selfAssessment option:selected");
		var selfAssessment=option.val();
		if(selfAssessment == 0){
			alert("请选择本周的自我评价！");
		}else{
			var senddata={
				"weekId":weekId,
				"summary":summary,
				"selfAssessment":selfAssessment
			};
			$.ajax({
				url:"submitSum",
				type:'POST',
				datatype:'json',
				data:senddata,
				success:function(data){
					alert('提交成功！')
					window.location.href='#/week_sum';

				},
				error:function(){

				}
			})
		}
	})
})