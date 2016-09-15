$(document).ready(function(){
	var url= window.location.href;
	var parames=url.split("?")[1];
	parames=parames.split("&");
	var weekId=parames[0].split('=')[1];	

	$.ajax({
		url:'showSumInfoGroup',
		type:'POST',
		data:{"weekId":weekId},
		success: function(data){
			var sumScore=data.sumScore;
			if(sumScore){
				$("#sumScore").text('本周总结老师评分：'+sumScore);
			}
			$("#week_sum").val(data.summary);
			var assessment=data.assessment;
			var member1Assessment=assessment[0].score;
			var member2Assessment=assessment[1].score;
			var formember1=assessment[0].stuName;
			var formember2=assessment[1].stuName;
			var selfAssessment=data.self.score;
			var sumScore=data.sumScore;
			$("#member1Assessment").text(member1Assessment);
			$("#member2Assessment").text(member2Assessment);
			$("#selfAssessment").text(selfAssessment);
			$("#formember1").text('对'+formember1+'的评价：');
			$("#formember2").text('对'+formember2+'的评价：');

			
		}
	})
	
})