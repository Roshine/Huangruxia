$(document).ready(function(){
	window.history.forward(1);
	var url= window.location.href;
	var parames=url.split("?")[1];
	parames=parames.split("&");
	var tempid=parames[0].split('=')[1];
	var subject=$(".subject");
	//添加no属性，记录在原始Qdesc中的索引
	function addNo(obj){
		var len=obj.length;
		for(var ii=0;ii<len;ii++){
			obj[ii].no=ii;
		}
	}
	//打乱顺序
	function newsort(a,b){
		return Math.random() >.5 ? -1 : 1;
	};

	$.ajax({
		url:'showPreInfoStu',
		dataType: 'json',
		type:'POST',
		data:{"pretempid":tempid},
		success : function(data){
			var detdata=data.data;
			$("#title").text(detdata.title);
			$("#target").text(detdata.target);
			var Qdesc=detdata.Qdesc;//问题描述
			addNo(Qdesc);
			//给每个option加上no属性；
			for(var Qi=0,Qlen=Qdesc.length;Qi<Qlen;Qi++){
				var newOptions=[];
				var options=Qdesc[Qi].options;
				newOptions.push({no:0,option:options[0]});
				newOptions.push({no:1,option:options[1]});
				newOptions.push({no:2,option:options[2]});
				newOptions.push({no:3,option:options[3]});
				Qdesc[Qi].newOptions=newOptions.sort(newsort);
			};
			//console.log(Qdesc);
			Qdesc.sort(newsort);
			var Qdescdiv = $(".Qdesc");
			var optiondiv = $(".options");
			optiondiv.each(function(index,el){
				var eachqs=Qdesc[index];//每一题的信息
				var question=eachqs.question;//题目描述
				subject.eq(index).text(question);
				Qdescdiv.eq(index).addClass('class'+eachqs.no);

				var options=eachqs.newOptions;//选项描述
				var option = $(this).find(".option");
				var inbtn = $(this).find('input');
				for (var i = 0; i < 4; i++) {
					option.eq(i).addClass('op'+options[i].no).text(options[i].option);
					inbtn.eq(i).val(options[i].no);
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
		var flag=false;

		for(var i=0,len=subject.length;i<len;i++){
			var resultDom=$(".class"+i);
			var resultValue=resultDom.find("input[type='radio']:checked").val();
			var diffi=resultDom.find(".difficulty  option:selected").val();
			if(diffi == -1){
				flag=true;
			}
			results[i]=resultValue;
			difficulty[i]=diffi;
		}

		/*$("input[type='radio']:checked").each(function(index,el){
		 var opvalue=parseInt($(this).val());

		 var diffi=$(".difficulty  option:selected").eq(index).val();
		 difficulty.push(parseInt(diffi));
		 });*/

		if(results.length<5){
			alert('你有未选择的题目，请认真完成每一题并选择该题你认为的难易度');
		}else if(flag){
			alert("你有题目的难易度未选择！");
		}else if(pre_feedback==''){
			alert('请填写预习心得！');
		}else if(confirm("确认提交否？提交后不可更改了哦！")){
			$('#submit').attr("disabled","disabled");
			var stu_write_data={
				pretempid:tempid,
				difficulty:difficulty,
				result:results,
				experience:pre_feedback
			};
			console.log(stu_write_data);
			$.ajax({
				url:'submitPre',
				type:'POST',
				data:stu_write_data,
				success:function(){
					alert("提交成功");
					//console.log(stu_write_data);
					window.location.href='#/pre_class';
				}
			})
		}

	})
})