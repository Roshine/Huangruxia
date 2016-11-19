$(document).ready(function(){
	window.history.forward(1);
	var url= window.location.href;
	var parames=url.split("?")[1];
	parames=parames.split("&");
	var weekId=parames[0].split('=')[1];	
	var member1Id='';
	var member1Name='';
	var member2Id='';
	var member2Name='';
	var selfId='';
	var selfName='';
	var leader=false;
	$.ajax({
		url:'getGroupMember',
		type:'POST',
		dataType:'json',
		success:function(data){
			leader=data.leader;
			var groupmember=data.groupmember;
			 member1Name=groupmember[0].stuName;
			$("#formember1").text('对'+member1Name+'的评价：');
			member1Id=groupmember[0].stuId;
			member2Name=groupmember[1].stuName;
			$("#formember2").text('对'+member2Name+'的评价：');
			member2Id=groupmember[1].stuId;
			selfId=data.self.stuId;
			selfName=data.self.stuName;
			console.log(leader);
			if (leader) {
				$(".sum_week_div").css("display","block");
				
			}else{
				$("#week_sum").css("display","none");
			};
		},
		error:function(data){
			console.log(data);
		}
	});
	$("#submit").on('click',function(){
		var summary=$("#week_sum").val();
		var option1=$("#member1Assessment option:selected");
		var option2=$("#member2Assessment option:selected");
		var option3=$("#selfAssessment option:selected");
		var selfAssessment=option3.val();
		var score1=option1.val();
		var score2=option2.val();
		if(selfAssessment == 0 || score1==0 ||score2==0){
			alert("请完成本周的小组互评！");
		}else{
			var assessment=[
			{
				stuId:member1Id,
				stuName:member1Name,
				score:score1
			},{
				stuId:member2Id,
				stuName:member2Name,
				score:score2
			},{
				stuId:selfId,
				stuName:selfName,
				score:selfAssessment
			}];
			
			if (leader) {
				if (!summary) {
					alert('请填写本周总结');
				}else if(confirm('确认提交否？提交后不可更改了哦！')){
                    $('#submit').attr("disabled","disabled");
					var senddata={
						weekId:weekId,
						summary:summary,
						assessment:assessment
					};
					$.ajax({
						url:"submitSumGroupLeader",
						type:'POST',
						datatype:'json',
						data:senddata,
						success:function(data){
							if(!data.error){
								alert('提交成功！');
								window.location.href='#/week_sum'
							}
						},
						error:function(){

						}
					})
				}	
			}else{
				var senddata={
						weekId:weekId,
						summary:summary,
						assessment:assessment
					};
				$.ajax({
					url:"submitSumGroupMember",
					type:'POST',
					datatype:'json',
					data:senddata,
					success:function(data){
						alert('提交成功！');
						window.location.href='#/week_sum'
					},
					error:function(data){
						console.log(data);
						alert('提交失败，请组长统一提交本周总结！');
					}
				})
			}
		}
	})
})