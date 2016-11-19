$(document).ready(function(){
	var url=window.location.href;
	url=url.split('?');
	var parames=url[1].split('&');
	var publish=parames[1].split('=');
	var published=publish[1];
	var parame=parames[0].split('=');
	var pretempid=parame[1];//为tempid
	$.ajax({
		url: 'homeworkTempInfo',
		dataType: 'json',
		type:'POST',
		data:{"homeworkTempId":pretempid},
		success: function(data){
			var data=data.data;
			var target=data.target;
			var title=data.title;
			var startTime=data.startTime;
			var deadline=data.deadLine;
			var tempid=data.tempid;
			var week=data.week;
			
			$("#target").val(target);
			$("#title").val(title);
			$("#startTime").val(startTime);
			$("#deadline").val(deadline);
			$("#week").val(week);
			
		}
	});

	if(published=='no'){
		$("#create_submit").click(function(){
				var startTime=$("#startTime").val();
				var deadline=$("#deadline").val();
				var target=$("#target").val();
				var title=$("#title").val();
				var week=$("#week").val();
				var senddata={
					"title":title,
					"target": target,
					"startTime":startTime,
					"deadLine": deadline,
					"week":week,
					"homeworkTempId":pretempid
				};
				//console.log(senddata);
				$.ajax({
					url:'modifyHomeworkTempAuto',
					type:'POST',
					//dataType:'json',
					data:senddata,
					success:function(){
						alert("修改成功！");
						window.location.href="#/homework"
						//这里是搜集前端的数据并传给后台，成功后再跳转到模板列表
					},
					error:function(data){
						alert("修改失败");
						console.log(data);
					}
				})
		})
	}else{
		$("#create_submit").attr("disabled","disabled");
	}
	
})