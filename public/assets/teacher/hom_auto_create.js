$(document).ready(function(){	
		
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
					"week":week
				};
				$.ajax({
					url:'createHomeworkTempAuto',
					type:'POST',
					//dataType:'json',
					data:senddata,
					success:function(){
						alert("模板创建成功！");
						window.location.href="#/homework"
						//这里是搜集前端的数据并传给后台，成功后再跳转到模板列表
					},
					error:function(data){
						alert("创建失败");
						console.log(data);
					}
				})
		})
})
