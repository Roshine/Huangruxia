$(document).ready(function(){
	var url= window.location.href;
	var parames=url.split("?")[1];
	parames=parames.split("&");
	var tempid=parames[0].split('=')[1];
	var write=parames[1].split('=')[1];
	var details=$("#details");
	if(write=='yes'){
		$("#submit").css("display","none");
		$.ajax({
			url:'ssets/online/pre_class_detail.json',
			dataType: 'json',
			success : function(data){
				var detdata=data.data;
				detdata=detdata[0];
				var Qdesc=detdata.Qdesc;//问题描述
				var feedback=detdata.feedback;
				$("#pre_feedback").html("预习心得：   "+feedback);
				var Qdesc_len=Qdesc.length;//问题长度
				var str='';
				var longstr='';
				for (var i = 0; i < Qdesc_len; i++) {
					var eachqs=Qdesc[i];//每一题的信息
					var id=eachqs.qid;//题目在数据库的id
					var qdescription=eachqs.qdescription;//题目描述
					var qoptions=eachqs.qoptions;//题目描述
					var options_arr=qoptions.split("##");
					var answer=eachqs.answer;
					var option_head='<input type="radio" name="radio';
					var stroptions=option_head+i+'">'+
									options_arr[0]+
									'<br>'+
									option_head+i+'">'+
									options_arr[1]+
									'<br>'+
									option_head+i+'">'+
									options_arr[2]+
									'<br>'+
									option_head+i+'">'+
									options_arr[3]+
									'<br>';

					str='<div class="ques_description"><p class="'+
						answer+
						'">'+
					     parseInt(i+1)+
					     '. '+
					     qdescription+
					     '</p>'+
					     stroptions+
					     '</div>';
					longstr=longstr+str;
				};
				console.log('11');
				details.html(longstr);
				$(".ques_description").each(function(){
					var _this=this;
					var eachp=$(this).find("p");
					var radioselected=eachp.attr("class");
					radioselected=parseInt(radioselected);
					var inputs=$(this).find("input");
					inputs.eq(radioselected).attr("checked","checked");
				});
			}
		})
	}else if(write=='no'){
		var stu_id='';
		var tempid='';
		$.ajax({
			url:'assets/online/pre_class_detail.json',
			dataType: 'json',
			success : function(data){
				var detdata=data.data;
				detdata=detdata[0];
				var Qdesc=detdata.Qdesc;//问题描述
				 stu_id=detdata.stuid;
				 tempid=detdata.tempid;
				var Qdesc_len=Qdesc.length;//问题长度
				var str='';
				var longstr='';
				for (var i = 0; i < Qdesc_len; i++) {
					var eachqs=Qdesc[i];//每一题的信息
					var id=eachqs.qid;//题目在数据库的id
					var qdescription=eachqs.qdescription;//题目描述
					var qoptions=eachqs.qoptions;//题目描述
					var options_arr=qoptions.split("##");
					var dffic='<span>你觉得该题难度是：</span><select>'+
								 '<option value ="1">简单</option>'+ 
								  '<option value ="2">有难度</option>'+
								  '<option value="3">很难</option>'+
								'</select>';
					//var answer=eachqs.answer;
					var option_head='<input type="radio" name="radio';
					var stroptions=option_head+i+
									'" value="A">'+
									options_arr[0]+
									'<br>'+
									option_head+i+
									'" value="B">'+
									options_arr[1]+
									'<br>'+
									option_head+i+
									'" value="C">'+
									options_arr[2]+
									'<br>'+
									option_head+i+
									'" value="D">'+
									options_arr[3]+
									'<br>';

					str='<div class="ques_description"><p '+
						'>'+
					     parseInt(i+1)+
					     '. '+
					     qdescription+
					     ' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp '+
					     dffic+
					     '</p>'+
					     stroptions+
					     '</div>';
					longstr=longstr+str;
				};

				details.html(longstr);
			}
		});
		var feedstr='<textarea class="pre_feedback"></textarea>';
		$("#pre_feedback").html(feedstr);
		$("#submit").css("display","block");
		$("#submit").on('click',function(){
			var results=[];
			var pre_feedback=$('.pre_feedback').val();
			$("input[type='radio']:checked").each(function(){
				results.push($(this).val());
			});
			if(results.length<5){
				alert('你有未选择的题目，请认真完成每一题并选择该题你认为的难易度');
			}else if(pre_feedback==''){	
				alert('请填写预习心得！');
			}else{
				var stu_write_data={
					stu_id:stu_id,
					tempid:tempid,
					results:results,
					pre_feedback:pre_feedback
				};
				console.log(stu_write_data);
				alert("提交成功");
				window.location.href='#/pre_class';
			}
		})
	}	
})