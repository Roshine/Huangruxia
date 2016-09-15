/**
 * @Author:	  Roshine
 * @DateTime:	2016-08-09 14:16:28
 * @Description: 老师的默认页模板课前预习的列表
 */

$(document).ready(function(){
	var templisttable=$("#templisttable");
	$("#createTemp").click(function(){
		window.location.href="#/pre_create_temp";
	});
	window.actionsEvents = {
			    'click .view': function (e, value, row, index) {
			        window.location.href="#/preStuList?tempid="+row.id+"&published="+row.published;
			    },
			    'click .viewtemp': function (e, value, row, index) {
			    	if(row.published==''){
			    		window.location.href="#/pre_temp_detail?tempid="+row.id+"&published=no";
			    	}else{
			    		window.location.href="#/pre_temp_detail?tempid="+row.id+"&published="+row.published;
			    	}
			    },
			    'click .delete': function (e, value, row, index) {
			    	var tempid=row.id;
			    	if(confirm("确定要删除数据吗")){
						$.ajax({
					       	url:'deletePreTemp',
					       	type:'POST',
					       	data:{"pretempid":tempid},
					       	success: function(){
					       		$("#templisttable").bootstrapTable('removeByUniqueId', tempid);
					       	}
					    })
					}else{
							console.log('什么都不执行')
					}
			    },
			    'click .published': function (e, value, row, index) {
			       $.ajax({
				       	url:'publishPre',
				       	type:'POST',
				       	data:{"pretempid":row.id},
				       	success: function(){
				       		$("#templisttable").bootstrapTable('updateCell', {"index":index,"field":"published","value":'yes'});
				       		alert('发布成功！');
				       	}
			       });
			    }
	};	
	
	function ajaxRequest(params) {
	        // data you need
	        console.log(params.data);
	        // just use setTimeout
	        $.ajax({
				url:'preTempList',
				method:'POST',
				data:{"data":params.data},
				success:function(data){
					if(data.error==0){
						params.success(data);
					}
					
				}
			})
	        
	};

	$("#templisttable").bootstrapTable({
		//url:'preTempList',
		//method: 'post',
		//dataType:'json',
		ajax:ajaxRequest,
	    striped: true,
	    pagination: true,                   //是否显示分页（*）
		 pageNumber:1,                       //初始化加载第一页，默认第一页
		 pageSize: 10,                       //每页的记录行数（*）
		 pageList: [10, 25, 50, 100],        //可供选择的每页的行数（*）
		// queryParams: queryParams,
		 sidePagination: "server",
		search: true,                       //是否显示表格搜索，此搜索是客户端搜索，不会进服务端，所以，个人感觉意义不大
		strictSearch: true,
		showColumns: true,                  //是否显示所有的列
		showRefresh: true,                  //是否显示刷新按钮
		 minimumCountColumns: 2,             //最少允许的列数
		 clickToSelect: true,                //是否启用点击选中行
		 height: 500,                        //行高，如果没有设置height属性，表格自动根据记录条数觉得表格高度
		uniqueId: "id",                     //每一行的唯一标识，一般为主键列
	    columns: [{
	        field: 'id',
	        title: '模板编号'
	    }, {
	        field: 'title',
	        title: '课时'
	    }, {
	        field: 'published',
	        title: '操作',
	        formatter:actionsFormatter,
	        events:actionsEvents
	    }]
	});
	function queryParams(params) {
		console.log(params);
		return { 
			"data":{
				"limit":params.limit,
				"offset":params.offset
			}
		};
	}
	function actionsFormatter(value){
		if(value=='no'){
			return ['<input class="btn btn-info viewtemp" type="button" value="查看模板">',
					'&nbsp;&nbsp;&nbsp;&nbsp;',
					'<input class="btn btn-danger delete" type="button" value="删除">',
					'&nbsp;&nbsp;&nbsp;&nbsp;',
					'<input class="btn btn-success published" type="button" value="发布">'
					].join('');
		}else{
			return ['<input class="btn btn-info viewtemp" type="button" value="查看模板">',
					'&nbsp;&nbsp;&nbsp;&nbsp;',
					'<input class="btn btn-info view" type="button" value="查看问卷">',
					'&nbsp;&nbsp;&nbsp;&nbsp;',
					'<input class="btn btn-danger delete" type="button" value="删除">'
					].join('');
		}
	}
})