/**
 * @Author:	  Roshine
 * @DateTime:	2016-08-09 13:33:02
 * @Description: 学生课前预习默认页为列表页，得到当前已发布的模板列表，包括自己已填和
 * 未填的。
 */
$(document).ready(function(){
	window.actionsEvents = {
			    'click .fillin': function (e, value, row, index) {//请求填写问卷页面，传送pretempid
			        window.location.href="#/pre_fillin?tempid="+row.pretempid;
			    },
			    'click .subm_detail': function (e, value, row, index) {
			        window.location.href="#/pre_subm_detail?tempid="+row.pretempid;
			    },
			    'click .only_view': function (e, value, row, index) {
			    	window.location.href="#/pre_only_view?tempid="+row.pretempid;
			    }
	};	
	var templisttable=$('#templisttable');
    $.ajax({
        url:"preTempListStu",
        type:'POST',
        dataType:'json',
        success:function(data){
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
                height: 500,                        //行高，如果没有设置height属性，表格自动根据记录条数觉得表格高度
                uniqueId: "id",                     //每一行的唯一标识，一般为主键列
                columns: [{
                    field: 'pretempid',
                    title: '编号'
                }, {
                    field: 'title',
                    title: '课时'
                },
                {
                    field: 'startTime',
                    title: '开始时间'
                }, 
                {
                    field: 'deadLine',
                    title: '截止日期'
                },{
                    field: 'selectscore',
                    title: '选择题得分',
                    class: 'col-md-1'
                },
                {
                    field: 'expscore',
                    title: '心得得分',
                    class: 'col-md-1'
                },{
                    field: 'submitted',
                    title: '操作',
                    formatter:actionsFormatter,
                    events:actionsEvents
                }]
            });  
        }
    })
	
			
	function actionsFormatter(value, row, index){
		if(value=='no'&& row.duringtime=='yes'){//进入填写页面
			return '<input class="btn btn-success fillin" type="button" value="进入填写">';
			
		}else if(value=='yes'){
			return '<input class="btn btn-info subm_detail" type="button" value="查看">';
					
		}else if(value=='no' && row.duringtime=='no'){
			return '<input class="btn btn-warning only_view" type="button" value="查看">';
		}
	};
})