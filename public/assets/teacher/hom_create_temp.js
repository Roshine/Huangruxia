$(document).ready(function(){	
		var checkanswer=function(callback){
			var answer=[];
			var answerin=$(".answer");
			var answerlen=answerin.length;
			var message=[];
			for(var i=0;i<answerlen; i++){
				var answeritem=answerin.eq(i).val();
				switch(answeritem){
					case 'A':
						answer.push(0);
						break;
					case 'B':
						answer.push(1);
						break;
					case 'C':
						answer.push(2);
						break;
					case 'D':
						answer.push(3);
						break;
					default:
						message.push('请老师核对第'+parseInt(i+1)+'个题目的参考答案格式');
				}
			}
			if(message==''){//格式正确
				callback(answer);
			}else{
				alert(message.join('  '));
			}
		}//check_answer
		$("#create_submit").click(function(){
			checkanswer(function(answer){
				var answer=answer;
				var Qdescinfo=[];
				var startTime=$("#startTime").val();
				var deadline=$("#deadline").val();
				var target=$("#target").val();
				var title=$("#title").val();
				var week=$("#week").val();
				$(".Qdesc").each(function(index,el){
					var subject=$(this).find(".subject").val();
					var optionsin=$(this).find(".option");
					var options=[];
					var option='';
					for (var i = 0; i < 4; i++) {
						 option=optionsin.eq(i).val();
						 options.push(option);
					};
					Qdescinfo.push({"question":subject,"options":options})
				});
				var senddata={
					"title":title,
					"target": target,
					"startTime":startTime,
					"deadLine": deadline,
					"Qdesc": Qdescinfo,
					"answer":answer,
					"week":week
				};
				$.ajax({
					url:'createHomeworkTemp',
					type:'POST',
					//dataType:'json',
					data:senddata,
					success:function(data){
						if(data.error==0){
							alert("模板创建成功！");
							window.location.href="#/homework";
							//这里是搜集前端的数据并传给后台，成功后再跳转到模板列表
						}else if (data.error==-1) {
							alert("请核对填写格式");
						}else if(data.error==-2){
							alert("网络错误，请稍后再试！")
						}
					},
					error:function(data){
						alert("创建失败");
						console.log(data);
					}
				})
			})	
		})
})
