$(document).ready(function(){
	var url= window.location.href;
	var parames=url.split("?")[1];
	parames=parames.split("&");
	var weekId=parames[0].split('=')[1];
	var groupId=parames[1].split('=')[1];	

	var senddata={
		weekId:weekId,
		groupId:groupId
	};
	$.ajax({
		url:'showGroupAssessment',
		type:'POST',
		data:senddata,
		success: function(data){
			$("#week_sum").val(data.summary);
			$("#sumScore").text(data.sumScore);
			var members=[];
			var peers=[];
			var assessment=data.assessment;
			var ass_len=assessment.length;
			for(let i=0;i<ass_len;i++){
				var stuName=assessment[i]['stuName'];
				var peerName=assessment[i]['peerName'];
				if(members.indexOf(stuName)<0){
					members.push(stuName);
				}
				if(peers.indexOf(peerName)<0){
					peers.push(peerName);
				}	
			}
			console.log(members);
			var members_len=members.length;
			var peers_len=peers.length;
			$('#peer1').text(peers[0]);
			$('#peer2').text(peers[1]);
			$('#peer3').text(peers[2]);
			for (let i = 0; i < members_len; i++) {
				$("#member"+i).text(members[i]);
			};
			//声明二维数组
			var aa=new Array(); //定义一维数组 
			for(let i=0;i<3;i++){ 
			    aa[i]=new Array(); //将每一个子元素又定义为数组 
			    for(let n=0;n<3;n++){ 
				    aa[i][n]='--'; //此时aa[i][n]可以看作是一个二级数组 
				} 
			}
			console.log(aa);

			for (let i = 0; i < members_len; i++) {
				for (let j = 0; j < peers_len; j++) {
					var memb=members[i];
					var peer=peers[j];
					for (var n = 0; n < ass_len; n++) {
						var stuName2=assessment[n]['stuName'];
						var peerName2=assessment[n]['peerName'];
						if (stuName2==memb && peerName2==peer){
							aa[i][j]=assessment[n]['assessment'];
						};
					};
				};
			};
			console.log(aa);
			var filltable=function(){
				for(let i=0;i<4;i++ ){
					for(let j=0;j<4;j++){
						$("#member"+i+'to'+j).text(aa[i][j]);
					}
				}
			}
			filltable();
		}
	})
	
})