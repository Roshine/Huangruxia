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
				if(title && target && startTime && deadline && week){
					$.ajax({
						url:'createHomeworkTempAuto',
						type:'POST',
						//dataType:'json',
						data:senddata,
	                    success:function(data){
	                        if(data.error==0){
	                            alert("模板创建成功！");
	                            window.location.href="#/homework";
	                            //这里是搜集前端的数据并传给后台，成功后再跳转到模板列表
	                        }else if (data.error==-1) {
	                            alert(data.desc.join('  '));
	                        }else if(data.error==-2){
	                            alert("网络错误，请稍后再试！")
	                        }else if(data.error==-3){
								alert("题库中该周的题目不足7道，不可创建")
							}
	                    },
	                    error:function(data){
	                        alert("创建失败");
							console.log(data);
						}
					})
				}else{
					alert("请老师完成每个字段的填写！")
				}
		})
})
