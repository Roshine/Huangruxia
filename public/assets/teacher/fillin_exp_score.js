$(function(){
	$("#submit").attr('disabled',true);
	var tempId='';
	var score_type='';
	$('.dropdown-menu').on('click','li',function(){
		$("#submit").attr('disabled',false);
		var _class=this.className;
		var classId=_class.split('-')[1];
		var _data='';
		if(_class.match('exp')){
			//实验成绩
			score_type = 0;
			tempId = _class.replace('exp','').split('-')[0];
			$("#showExp").val('实验'+tempId);
		}else if(_class.match('test')){
			score_type = 1 ;//测试成绩
			tempId = _class.replace('test','').split('-')[0];
			$("#showExp").val('测试'+tempId);
		}else if(_class.match('chap')){
			score_type = 2 ;
			tempId = _class.replace('chap','').split('-')[0];
			$("#showExp").val('第'+tempId+'章');
		}
		console.log(tempId);
		$("#showClass").val(classId+'班');
		if(classId && tempId){	
			var url_arr=['getStuListForReportScore','getStuListForTestscore','getStuListForChapterSum'];
			var url = url_arr[score_type];		
			$.ajax({
				url:url,
				type:'POST',
				dataType:'json',
				data:{tempId:tempId,class:classId},
				cache:false,
				success:function(data){
					$("#stu_list").bootstrapTable('destroy'); 
					$('#stu_list').bootstrapTable({
				        data: data,
				        pagination: true,                   //是否显示分页（*）
		           		pageNumber:1,                       //初始化加载第一页，默认第一页
		           		pageSize: 40,                       //每页的记录行数（*）
		           		pageList: [10, 40, 50, 100],        //可供选择的每页的行数（*
		           		showColumns: true,                  //是否显示所有的列
		           		minimumCountColumns: 2,             //最少允许的列数
		           		clickToSelect: true,                //是否启用点击选中行
		           		showRefresh: true, 
		            	// height: 500,                        //行高，如果没有设置height属性，表格自动根据记录条数觉得表格高度
		           		//uniqueId: "stuId",                     //每一行的唯一标识，一般为主键列
			            columns: [
						{
			                field: 'stuId',
			                title: '学号',
			                width: 100
			            }, {
			                field: 'name',
			                title: '姓名',
			                width: 100
			            }, {
			                field: 'score',
			                title: '操作',
			                formatter:markFormatter
			            },
			            {
			            	field:'score',
			            	title:'评分',
			            	formatter:scorebtnFormatter
			            }]
        			});

					$(".scorebtn").on('click',function(){
						var _this=this;
						var score=this.value;
						var score_in=$(this).parent().prev();
						score_in.find('input').val(score);

					})
				},
				error:function(data){
					alert(data);
				}
			});
			function markFormatter(value,row,index){
				if(value){
					return ['<span>得分：</span><input class="show_expscore btn" type="button" value="',
						    value,
						    '">'
							].join('');
				}else{
					return '<input type="text" class="'+row.stuId+'">';
							
				}
			}

			function scorebtnFormatter(value,row,index){
				if(!value){
					return [
					'<button class="scorebtn" type="button" value="60">C</button><button class="scorebtn" type="button" value="70">B</button>',
					'<button class="scorebtn" type="button" value="75">A-</button><button class="scorebtn" type="button" value="80">A</button>',
					'<button class="scorebtn" type="button" value="85">A+</button><button class="scorebtn" type="button" value="90">A++</button>',
					'<button class="scorebtn" type="button" value="95">A+++</button><button class="scorebtn" type="button" value="99">A++++</button>'].join('');
				}else{
					return '--';
				}
			}
		}	
	});
	
	
	$("#submit").on('click',function(){
		var inputs= $("#stu_list input");
		var sendData=[];
		var index_arr=[];
		var score_arr=[];
		for(var i=0,len=inputs.length;i<len;i++){
			var flag=inputs[i].type;
			if(flag=='text' && inputs[i].value.trim()){
				var score=inputs[i].value;
				var stuId=inputs[i].className;
				score_arr.push(score);
				index_arr.push(i);
				sendData.push({stuId:stuId,tempId:tempId,score:score});
			}
		}
		if(sendData.length){
			if(confirm("是否提交登分！")){
				var url_arr = ['fillReportScore','fillTestScore','fillChapterSumScore'];
				var url = url_arr[score_type];
				$.ajax({
					url:'fillReportScore',
					type:'POST',
					data:{"data":sendData},
					dataType:'json',
					success:function(data){
						alert('登分成功！');
						$(".scorebtn").off('click');
						for(var i=0,len=index_arr.length;i<len;i++){
								$("#stu_list").bootstrapTable('updateCell', {"index":index_arr[i],"field":"score","value":score_arr[i]});
						}
						$(".scorebtn").on('click',function(){
							var _this=this;
							var score=this.value;
							var score_in=$(this).parent().prev();
							score_in.find('input').val(score);
						})
					},
					error:function(data){
						alert('请求失败！')
					}
				})
			}	
		}else{
			alert('请填写分数！');
		}
	});


})