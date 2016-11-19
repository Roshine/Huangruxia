/**
 * @Author:	  Roshine
 * @DateTime:	2016-08-09 13:33:02
 * @Description: 学生课前预习默认页为列表页，得到当前已发布的模板列表，包括自己已填和
 * 未填的。
 */
$(document).ready(function(){	
	var templisttable=$('#templisttable');
    $.ajax({
        url:"sumTempList",
        type:'POST',
        dataType:'json',
        success:function(data){
            var groupid=data.groupId;
            if(groupid==0){//没有分组的情况下
                templisttable.bootstrapTable({
                    data: data.data,
                    striped: true,
                    pagination: true,  
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
                    uniqueId: "id",                     //每一行的唯一标识，一般为主键列
                    columns: [{
                        field: 'weekId',
                        title: '周数'
                    },
                    {
                        field: 'startTime',
                        title: '开始时间'
                    }, 
                    {
                        field: 'deadLine',
                        title: '截止日期'
                    },{
                        field: 'submitted',
                        title: '操作',
                        formatter:actionsFormatter,
                        events:actionsEvents
                    }]
                });  
            }else{//分组的情况下
                templisttable.bootstrapTable({
                    data: data.data,
                    striped: true,
                    pagination: true,  
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
                    uniqueId: "id",                     //每一行的唯一标识，一般为主键列
                    columns: [{
                        field: 'weekId',
                        title: '周数'
                    },
                    {
                        field: 'startTime',
                        title: '开始时间'
                    }, 
                    {
                        field: 'deadLine',
                        title: '截止日期'
                    },{
                        field: 'submitted',
                        title: '操作',
                        formatter:actionsFormatter,
                        events:groupactionsEvents
                    }]
                });  
            }
            
        }
    })
	
			
	function actionsFormatter(value, row, index){
		if((!value) && row.duringTime){//进入填写页面
			return '<input class="btn btn-success fillin" type="button" value="进入填写">';
			
		}else if(value){
			return '<input class="btn btn-info subm_detail" type="button" value="查看填写">';
					
		}else if(!row.duringTime){
			return '<span class="btn btn-warning" disabled="disabled">不在填写时间段</span>';
		}
	};

    window.actionsEvents = {
                'click .fillin': function (e, value, row, index) {
                      window.location.href="#/sum_fillin?weekId="+row.weekId;  
                },
                'click .subm_detail': function (e, value, row, index) {
                        window.location.href="#/sum_subm_detail?weekId="+row.weekId;
                }
    }; 
    window.groupactionsEvents = {
                'click .fillin': function (e, value, row, index) {
                    window.location.href="#/sum_fillin_group?weekId="+row.weekId;
                },
                'click .subm_detail': function (e, value, row, index) {
                    window.location.href="#/sum_subm_detail_group?weekId="+row.weekId;
                }
    };  
})