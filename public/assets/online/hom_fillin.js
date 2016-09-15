$(document).ready(function(){
	var url= window.location.href;
	var parames=url.split("?")[1];
	parames=parames.split("&");
	var tempid=parames[0].split('=')[1];
	var subject=$(".subject");
	console.log(tempid);	
	$.ajax({
		url:'showHomeworkInfoStu',
		dataType: 'json',
		type:'POST',
		data:{"homeworkTempId":tempid},
		success : function(data){
			console.log(data)
			var detdata=data.data;
			$("#title").text(detdata.title);
			$("#target").text(detdata.target);
			var Qdesc=detdata.Qdesc;//问题描述
			var options = $(".options");
			options.each(function(index,el){
				var eachqs=Qdesc[index];//每一题的信息
				var questionIds=eachqs.questionId;//每一题的id
				var question=eachqs.question;//题目描述
				subject.eq(index).attr("id",'question'+questionIds).text(question);
				var options=eachqs.options;//题目描述
				var option = $(this).find(".option");
				for (var i = 0; i < 4; i++) {
					option.eq(i).text(options[i])
				};	
			})
		},
		error: function(data){
			console.log(data);
		}
	});
	
		////提交时
		$("#submit").on('click',function(){
			var results=[];
			var difficulty=[];
			var pre_feedback=$('#pre_feedback').val();
			$("input[type='radio']:checked").each(function(index,el){
				results.push(parseInt($(this).val()));
				var diffi=$(".difficulty  option:selected").eq(index).val();
				difficulty.push(parseInt(diffi));
			});
			
			if(results.length<7){
				alert('你有未选择的题目，请认真完成每一题并选择该题你认为的难易度');
			}else if(pre_feedback==''){	
					alert('请填写课后作业心得！');
				}else if(confirm("确认提交否？提交后不可更改了哦！")){
					var squestionId=[];
					$('.subject').each(function(index,el){
						var subjectid=$(this).attr('id');
						var id=subjectid.replace(/question/,'');
						squestionId.push(parseInt(id));
					});
					var stu_write_data={
						homeworkTempId:tempid,
						questionId: squestionId,
						difficulty:difficulty,
						result:results,
						experience:pre_feedback
					};
					console.log(stu_write_data);
					$.ajax({
						url:'submitHomework',
						type:'POST',
						data:stu_write_data,
						success:function(){
							console.log(stu_write_data);
							alert("提交成功");
							window.location.href='#/homework';
						}
					})
				}
			
		})
})