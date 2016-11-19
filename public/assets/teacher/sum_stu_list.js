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
		                class: 'col-md-6'
		            }, {
		                field: 'sumScore',
		                title: '操作',
		                formatter:markFormatter,
		                events:actionEvents
		            },{
						field: 'remarks',
						title: '备注',
						class: 'col-md-1'
					}, {
                        field: 'submitTime',
                        title: '提交时间',
                        class: 'col-md-1'
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
	            	// height: 500,                        //行高，如果没有设置height属性，表格自动根据记录条数觉得表格高度
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
		                class: 'col-md-5'
		            }, {
		                field: 'sumScore',
		                title: '评分',
		                formatter:marksingleFormatter,
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
							'<input class="expscore" placeholder="0-100分"',
							'id="',
							row.sumCollectionId+'expscore',
							'"  style="width:60px;" type="text" >',
                            '&nbsp;&nbsp;&nbsp;&nbsp;',
                            '<input class="remarks" placeholder="备注"',
                            'id="',
                            row.sumCollectionId+'remarks',
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
			};

			function marksingleFormatter(value, row, index){
				if(value==null){
					return ['<input class="expscore" placeholder="0-100分"',
							'id="',
							row.sumCollectionId+'expscore',
							'"  style="width:60px;" type="text" >',
                            '&nbsp;&nbsp;&nbsp;&nbsp;',
                            '<input class="remarks" placeholder="备注"',
                            'id="',
                            row.sumCollectionId+'remarks',
                            '"  style="width:200px;" type="text" >',
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
			       var sumScore = $("#"+sumCollectionId+'expscore').val();
			       var remarks = $("#"+sumCollectionId+'remarks').val();
			       var stuId=row.stuId;
			       //alert(sumScore);
					if (sumScore == ''){
						alert('还没有填写分数')
					}else if(sumScore<=100 && sumScore>=0) {
						$.ajax({
							url: 'fillSummaryMark',
							type: 'POST',
							data: {"sumCollectionId": sumCollectionId, "sumScore": sumScore,"remarks":remarks},
							success: function (data) {
								if (data.error == 0) {
									if (stuId == undefined) {
										$("#sumStuListGrouptable").bootstrapTable('updateCell', {
											"index": index,
											"field": "sumScore",
											"value": sumScore
										});
										$("#sumStuListGrouptable").bootstrapTable('updateCell', {
											"index": index,
											"field": "remarks",
											"value": remarks
										});
									} else {
										$("#sumStuListtable").bootstrapTable('updateCell', {
											"index": index,
											"field": "sumScore",
											"value": sumScore
										});
										$("#sumStuListtable").bootstrapTable('updateCell', {
											"index": index,
											"field": "remarks",
											"value": remarks
										});
									}
								}else {
									alert(data.des);
								}
							},
							error:function () {
								alert('网络错误')
							}
						})
					}else{
						alert('分数为0——100');
					}
			    }
	};
})