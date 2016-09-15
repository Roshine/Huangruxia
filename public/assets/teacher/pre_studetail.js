$(document).ready(function(){
	var url= window.location.href;
	var parames=url.split("?")[1];
	parames=parames.split("&");
	var tempid=parames[0].split('=')[1];
	var subject=$(".subject");
	var answerdom=$(".answer");	
	var difficultydom=$(".difficulty");
	var checkanswer=function(answer){
			var answerlen=answer.length;
			var sanswer=[];
			for(var i=0;i<answerlen; i++){
				var answeritem=answer[i];
				switch(answeritem){
					case '0':
						sanswer.push('A');
						break;
					case '1':
						sanswer.push('B');
						break;
					case '2':
						sanswer.push('C');
						break;
					case '3':
						sanswer.push('D');
						break;
					default:
						sanswer.push('请老师核未给出参考答案');
				}
			}	
			return sanswer;
	}//check_answer
	$.ajax({
		url:'showPreCollectionInfo',
		type:'POST',
		dataType: 'json',
		data:{"precollectionid":tempid},
		success : function(data){
			var detdata=data.data;
			$("#title").text(detdata.title);
			$("#target").text(detdata.target);
			$("#feedback").text(detdata.feedback);
			var answerIni=detdata.answer;
			var answer=checkanswer(answerIni);
			var difficulty=detdata.difficulty;
			var result=detdata.result;
			var Qdesc=detdata.Qdesc;//问题描述
			var options = $(".options");
			options.each(function(index,el){
				var eachqs=Qdesc[index];//每一题的信息
				var question=eachqs.question;//题目描述
				subject.eq(index).text(question);
				answerdom.eq(index).text('参考答案：'+answer[index]);
				var eachdifficulty=difficulty[index];
				difficultydom.eq(index).find('option[value="'+eachdifficulty+'"]').attr("selected",true);
				var options=eachqs.options;//题目描述
				var option = $(this).find(".option");
				for (var i = 0; i < 4; i++) {
					option.eq(i).text(options[i])
				};	
				var checkedin=$(this).find("input");
				var resultindex=parseInt(result[index]);
				checkedin.eq(resultindex).attr("checked","checked");
			})
		},
		error: function(data){
			console.log(data);
		}
	});
	
})