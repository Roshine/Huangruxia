$(document).ready(function(){
	var stuid='';
	var mark='';
	var url=window.location.href;
	url=url.split('?');
	var parames=url[1].split('&');
	var parame=parames[0].split('=');
	var senddata=parame[1];
	$.ajax({
		url:'preCollectionList',
		dataType:'json',
		type:'POST',
		data: {"tempid":senddata},
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
            	// height: 500,                        //行高，如果没有设置height属性，表格自动根据记录条数觉得表格高度
           		 uniqueId: "collectionid",                     //每一行的唯一标识，一般为主键列
	            columns: [
	            //     {
	            //     field: 'stuclass',
	            //     title: '班级',
	            //     class: 'col-md-1'
	            // },
                    {
	                field: 'stuname',
	                title: '姓名',
	                class: 'col-md-1'
	            }, {
	                field: 'stuId',
	                title: '学号',
	                class: 'col-md-1'
	            }, {
	                field: 'score',
	                title: '选择题得分',
	                class: 'col-md-1'
	            }, {
	                field: 'feedback',
	                title: '预习心得',
	                class: 'col-md-4'
	            }, {
	                field: 'expscore',
	                title: '操作',
	                formatter:markFormatter,
	                events:actionEvents
	            },{
                    field: 'remarks',
                    title: '备注',
                    class: 'col-md-1'
	            },{
					field: 'submitTime',
					title: '提交时间',
					class: 'col-md-1'
				}]
        	});
			function markFormatter(value, row, index){
				if(value==null){
					return ['<input class="btn btn-info view" type="button" value="查看">',
							'&nbsp;&nbsp;&nbsp;&nbsp;',
							'<input class="expscore" placeholder="0-5分"',
							'id="',
							row.stuId + 'expscore',
							'"  style="width:50px;" type="text" >',
							'&nbsp;&nbsp;&nbsp;&nbsp;',
							'<input class="remarks" placeholder="备注"',
							'id="',
							row.stuId + 'remarks',
							'"  style="width:200px;" type="text" >',
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
			}
			
		}
	});
	window.actionEvents = {
			    'click .view': function (e, value, row, index) {
			    	var collectionid=row.collectionid;
			        //window.location.href="#/preStuDetail?precollectionid="+collectionid+"&mark=yes";
			        window.open("#/preStuDetail?precollectionid="+collectionid+"&mark=yes");
			    },
			    'click .grading': function (e, value, row, index) {
			       var collectionid=row.collectionid;
			       var gradingid=row.stuId;
			       var expscore = $("#"+gradingid+'expscore').val();
                    var remarks = $("#"+gradingid+'remarks').val();
                    if (expscore == ''){
                        alert("还没填分数");
                    }else if(expscore<=5 && expscore>=0){
			       		$.ajax({
					       	url:'fillPreExpMark',
					       	type:'POST',
					       	data: {"precollectionid":collectionid,"expscore":expscore,"remarks":remarks},
					       	success: function(data){
					       		if (data.error == 0) {
									$("#preStuListtable").bootstrapTable('updateCell', {
										"index": index,
										"field": "expscore",
										"value": expscore
									});
									$("#preStuListtable").bootstrapTable('updateCell', {
										"index": index,
										"field": "remarks",
										"value": remarks
									});
								}else {
									alert(data.des);
								}
					       	},
					       	error:function () {
								alert('网络错误！')
							}
					       })
			       }else{
			       		alert("分数为0——5");
			       }
			    }
	};
})