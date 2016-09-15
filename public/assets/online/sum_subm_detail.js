$(document).ready(function(){
	var url= window.location.href;
	var parames=url.split("?")[1];
	parames=parames.split("&");
	var weekId=parames[0].split('=')[1];	

	$.ajax({
		url:'showSumCollectionInfo',
		type:'POST',
		data:{"weekId":weekId},
		success: function(data){
			$("#week_sum").val(data.summary);
			$("#sumScore").text(data.sumScore);
			var self_a=data.selfAssessment;
			$('#selfAssessment  option[value="'+self_a+'"] ').attr("selected",true)
		}
	})
	
})