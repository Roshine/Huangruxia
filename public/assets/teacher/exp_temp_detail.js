$(document).ready(function(){
	var url=window.location.href;
	url=url.split('?');
	var parames=url[1].split('&');
	var publish=parames[1].split('=');
	var published=publish[1];
	var parame=parames[0].split('=');
	var pretempid=parame[1];//为tempid
	console.log(pretempid);
	$.ajax({
		url: 'expTempInfo',
		dataType: 'json',
		type:'POST',
		data:{"expTempId":pretempid},
		success: function(data){
			var data=data.data;
			var answer=data.answer;
			var target=data.target;
			var title=data.title;
			var startTime=data.startTime;
			var deadline=data.deadLine;
			var startTime2=data.startTime2;
			var deadline2=data.deadLine2;
			var tempid=data.tempid;
			var Qdesc=data.Qdesc;
			$(".Qdesc").each(function(index,el){
				var subject=Qdesc[index].question;
				var options=Qdesc[index].options;
				$(this).find(".subject").val(subject);
				var optionsin=$(this).find(".option");
				optionsin.eq(0).val(options[0]);
				optionsin.eq(1).val(options[1]);
				optionsin.eq(2).val(options[2]);
				optionsin.eq(3).val(options[3]);
			});
			$("#target").val(target);
			$("#title").val(title);
			$("#startTime").val(startTime);
			$("#deadline").val(deadline);
			$("#startTime2").val(startTime2);
			$("#deadline2").val(deadline2);
			$(".answer").each(function(index,el){
				var letter=answer[index];
				switch(letter){
					case '0':
						$(this).val('A');
						break;
					case '1':
						$(this).val('B');
						break;
					case '2':
						$(this).val('C');
						break;
					case '3':
						$(this).val('D');
						break;
					default:
						$(this).val('哟，老师忘了给答案');
				}
			});	
			
		}
	});

	if(published=='no'){
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
				var startTime2=$("#startTime2").val();
				var deadline2=$("#deadline2").val();
				var target=$("#target").val();
				var title=$("#title").val();
				$(".Qdesc").each(function(index,el){
					//var Qdesc_this=el;
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
					"startTime2":startTime2,
					"deadLine2": deadline2,
					"Qdesc": Qdescinfo,
					"answer":answer,
					"expTempId":pretempid
				};
				//console.log(senddata);
				$.ajax({
					url:'modifyExpTemp',
					type:'POST',
					//dataType:'json',
					data:senddata,
					success:function(){
						alert("模板修改成功！");
						window.location.href="#/exp_test"
						//这里是搜集前端的数据并传给后台，成功后再跳转到模板列表
					},
					error:function(data){
						alert("修改失败");
						console.log(data);
					}
				})
			});
		})//click_over
	}else{
		$("#create_submit").attr("disabled","disabled");
	}
	
})