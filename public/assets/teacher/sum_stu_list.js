$(document).ready(function(){
	var stuid='';
	var mark='';
	var url=window.location.href;
	url=url.split('?');
	var parames=url[1].split('&');
	var parame=parames[0].split('=');
	var weekId=parame[1];//weekId
	$.ajax({
		url:'getSumList',
		dataType:'json',
		type:'POST',
		data: {"weekId":weekId},
		success: function(data){
			var group=data.group;
			var single=data.single;
			if(data.error==-2){
				alert("没有学生填写该模板");
			}else{
				$('#sumStuListGrouptable').bootstrapTable({
			        data: group,
			        striped: true,
			        pagination: true,                   //是否显示分页（*）
	           		 pageNumber:1,                       //初始化加载第一页，默认第一页
	           		 pageSize: 10,                       //每页的记录行数（*）
	           		 pageList: [10, 25, 50, 100],        //可供选择的每页的行数（*）
	            	search: true,                       //是否显示表格搜索，此搜索是客户端搜索，不会进服务端，所以，个人感觉意义不大
	            	strictSearch: true,
	           		 showColumns: true,                  //是否显示所有的列
	            	showRefresh: true,                  //是否显示刷新按钮
	           		 minimumCountColumns: 2,             //最少允许的列数
	           		 clickToSelect: true,                //是否启用点击选中
	           		// uniqueId: "",                     //每一行的唯一标识，一般为主键列
		            columns: [{
		                field: 'groupId',
		                title: '组号',
		                class: 'col-md-1'
		            }, {
		                field: 'summary',
		                title: '每周总结',
		                class: 'col-md-9'
		            }, {
		                field: 'sumScore',
		                title: '评分',
		                class: 'col-md-2',
		                formatter:markFormatter,
		                events:actionEvents
		            }]
	        	});	
			}

			$('#sumStuListtable').bootstrapTable({
			        data: single,
			        striped: true,
			        pagination: true,                   //是否显示分页（*）
	           		 pageNumber:1,                       //初始化加载第一页，默认第一页
	           		 pageSize: 10,                       //每页的记录行数（*）
	           		 pageList: [10, 25, 50, 100],        //可供选择的每页的行数（*）
	            	search: true,                       //是否显示表格搜索，此搜索是客户端搜索，不会进服务端，所以，个人感觉意义不大
	            	strictSearch: true,
	           		 showColumns: true,                  //是否显示所有的列
	            	showRefresh: true,                  //是否显示刷新按钮
	           		 minimumCountColumns: 2,             //最少允许的列数
	           		 clickToSelect: true,                //是否启用点击选中行
	            	height: 500,                        //行高，如果没有设置height属性，表格自动根据记录条数觉得表格高度
	           		// uniqueId: "",                     //每一行的唯一标识，一般为主键列
		            columns: [{
		                field: 'stuId',
		                title: '学号',
		                class: 'col-md-1'
		            }, {
		                field: 'stuName',
		                title: '姓名',
		                class: 'col-md-1'
		            },{
		                field: 'summary',
		                title: '每周总结',
		                class: 'col-md-8'
		            }, {
		                field: 'sumScore',
		                title: '评分',
		                class: 'col-md-2',
		                formatter:marksingleFormatter,
		                events:actionEvents
		            }]
	        	});	

			function markFormatter(value, row, index){
				if(value==null){
					return ['<input class="btn btn-info view" type="button" value="查看">',
							'&nbsp;&nbsp;&nbsp;&nbsp;',
							'<input class="expscore"',
							'id="',
							row.sumCollectionId,
							'"  style="width:30px;" type="text" >',
							'&nbsp;&nbsp;&nbsp;&nbsp;',
							'<input class="btn btn-success grading" type="button" value="评分">'
							].join('');
				}else{
					return ['<input class="btn btn-info view" type="button" value="查看">',
							'&nbsp;&nbsp;&nbsp;&nbsp;',
							'<span>得分：</span><input class="show_expscore" type="button" value="',
							value,
							'">'
							].join('');
				}
			};

			function marksingleFormatter(value, row, index){
				if(value==null){
					return ['<input class="expscore"',
							'id="',
							row.sumCollectionId,
							'"  style="width:30px;" type="text" >',
							'&nbsp;&nbsp;&nbsp;&nbsp;',
							'<input class="btn btn-success grading" type="button" value="评分">'
							].join('');
				}else{
					return ['<span>得分：</span><input class="show_expscore" type="button" value="',
							value,
							'">'
							].join('');
				}
			};
			
		}
	});
	window.actionEvents = {
			    'click .view': function (e, value, row, index) {
			    	var groupId=row.groupId;
			        window.location.href="#/sum_subm_detail?weekId="+weekId+"&groupId="+groupId;
			    },
			    'click .grading': function (e, value, row, index) {
			       var sumCollectionId=row.sumCollectionId;
			       var sumScore = $("#"+sumCollectionId).val();
			       var stuId=row.stuId;
			       //alert(sumScore);
			       $.ajax({
				       	url:'fillSummaryMark',
				       	type:'POST',
				       	data: {"sumCollectionId":sumCollectionId,"sumScore":sumScore},
				       	success: function(data){
				       		if(stuId==undefined){
				       			$("#sumStuListGrouptable").bootstrapTable('updateCell', {"index":index,"field":"sumScore","value":sumScore});
				       		}else{
				       			$("#sumStuListtable").bootstrapTable('updateCell', {"index":index,"field":"sumScore","value":sumScore});
				       		}
				       		alert('评分成功！');
				       	}
			       })
			    }
	};
})