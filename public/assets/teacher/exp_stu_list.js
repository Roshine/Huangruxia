$(document).ready(function(){
	var stuid='';
	var mark='';
	var url=window.location.href;
	url=url.split('?');
	var parames=url[1].split('&');
	var parame=parames[0].split('=');
	var senddata=parame[1];//tempid
	$.ajax({
		url:'expCollectionList',
		//url:'assets/teacher/pre_stulist.json',
		dataType:'json',
		type:'POST',
		data: {"expTempId":senddata},
		success: function(data){
			var stulist=data.data;
			console.log(stulist);
			if(data.error==-2){
				alert("没有学生填写该模板");
			}else{
				
			}
			$('#preStuListtable').bootstrapTable({
		        data: stulist,
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
           		 uniqueId: "collectionid",                     //每一行的唯一标识，一般为主键列
	            columns: [{
	                field: 'stuclass',
	                title: '班级',
	                class: 'col-md-1'
	            }, {
	                field: 'stuname',
	                title: '姓名',
	                class: 'col-md-1'
	            }, {
	                field: 'stuId',
	                title: '学号',
	                class: 'col-md-2'
	            }, {
	                field: 'score',
	                title: '选择题得分',
	                class: 'col-md-1'
	            }, {
	                field: 'feedback',
	                title: '预习心得',
	                class: 'col-md-5'
	            }, {
	                field: 'expscore',
	                title: '操作',
	                formatter:markFormatter,
	                events:actionEvents
	            }]
        	});
			function markFormatter(value, row, index){
				if(value==null){
					return ['<input class="btn btn-info view" type="button" value="查看">',
							'&nbsp;&nbsp;&nbsp;&nbsp;',
							'<input class="expscore"',
							'id="',
							row.stuId,
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
			
		}
	});
	window.actionEvents = {
			    'click .view': function (e, value, row, index) {
			    	var collectionid=row.collectionid;
			        window.location.href="#/exp_stu_detail?collectionId="+collectionid+"&mark=yes";
			    },
			    'click .grading': function (e, value, row, index) {
			       var collectionid=row.collectionid;
			       var gradingid=row.stuId;
			       var expscore = $("#"+gradingid).val();
			       $.ajax({
			       	url:'fillExpExpMark',
			       	type:'POST',
			       	data: {"expcollectionid":collectionid,"expscore":expscore},
			       	success: function(){
			       		$("#preStuListtable").bootstrapTable('updateCell', {"index":index,"field":"expscore","value":expscore});
			       		alert('评分成功！');
			       	}
			       })
			    }
	};
})