<?php
//测试路由
//Route::get('Modifyinformation','StudentsController@Modifyinformation');


/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
//登录注册
Route::get('auth/login','Auth\AuthController@getLogin');
Route::post('auth/login','Auth\AuthController@postLogin');
Route::get('auth/register','Auth\AuthController@getRegister');
Route::post('auth/register','Auth\AuthController@postRegister');
Route::get('auth/logout','Auth\AuthController@logout');


//进入学生或老师界面
Route::get('/online','Online\OnlineController@getOnline');
Route::get('/teacher','Teacher\TeacherController@getTeacher');
Route::get('/test','testcontroller@getTest');


//忘记密码
Route::post('auth/postForgetPassword','Auth\PasswordController@postForgetPassword');
Route::get('reset-password','Auth\PasswordController@getResetPassword');
Route::post('reset-password','Auth\PasswordController@postResetPassword');


//学生个人信息
Route::group(['middleware' => 'auth'],function() {
    Route::post('getStudentInfo', 'StudentsController@getStudentInfo'); //学生获取个人信息
    Route::post('Modifyinformation','StudentsController@Modifyinformation'); //修改个人信息
    Route::post('ResetPassword','ResetPasswordController@Resetpassword');//修改密码
});


//课前预习___学生
Route::group(['middleware' => 'auth'],function() {

    /**
     * in:
     * []
     *
     * out:
     * [
    'error' => 0,
    "data" => [
    'pretempid' => '预习模板id',
    'title' => '模板标题',
    'startTime' => '答题开始时间',
    'deadLine' => '答题截止时间',
    'duringtime' => '是否在答题时间段内',
    'submitted' => '是否提交过',
    'selectscore' => '选择题分数',
    'expscore' => '心得分数'
    ]
    ]
     *
     *
     */
    Route::post('preTempListStu','PreTempController@preTempListStu');   //显示模板列表

    /**
     * in:
     *[
    '$pretempid' => '预习模板id',
    ]
     *
     *out:
     * [
    'error' => 0,
    "data" => [
    "pretempid" => '预习模板id',
    "title" => '模板标题',
    "target" => '目标',
    "startTime" => '答题开始时间',
    "deadLine" => '答题截止时间',
    "Qdesc" => '问题和选项',
    ]
    ]
     *
     *
     */
    Route::post('showPreInfoStu','PreTempController@showPreInfoStu');     //学生获取题目进行答题

    /**
     *in:
     * [
    'pretempid' => '预习模板id',
    'result' => '学生提交答案',
    'experience' => '心得',
    'difficulty' => '题目难度'
    ]
     *
     *out:
     * [
    'error' => 'required'
    ]
     *
     *
     */
    Route::post('submitPre','PreCollectionController@submitPre');     //提交试卷

    /**
     * in:
     * [
    'pretempid' => 'required',
    ]
     *
     * out:
     * [
    'error' => 0,
    "data" => [
    "title" => '模板标题',
    "target" => '目标',
    "startTime" => '答题开始时间',
    "deadLine" => '答题截止时间',
    "Qdesc" => '问题和选项',
    "answer" => '标准答案',
    "result" => '学生提交的答案',
    "selectscore" => '选择题分数',
    "experience" => '心得',
    "marked" => '心得是否评分',
    "expscore" => '心得分数'，
    "submitTime" => '提交时间'
    ]
    ]
     *
     */
    Route::post('checkPreInfo','PreTempController@checkPreInfo');     //查看已答题目

});

//课前预习--老师
Route::group(['middleware' => 'teacherAuth'],function() {

    //课前预习___老师
    /**
     *
     * in:
     * [
     *  'pretempid' => 'required'
     * ]
     *
     *
     * out:
     *  [
    'error' => 0,
    'data' => [
    'pretempid' => '模板id',
    'title' => '模板标题',
    'target' => '预习目标',
    'startTime' => '答题开始时间',
    'deadLine' => '答题截止时间',
    'Qdesc' => '问题和选项',
    'answer' => '标准答案'
    ]
    ];
     *
     * error num.:
     *  parameter error -1
     * database error -2
     */
    Route::post('preTempInfo','PreTempController@preTempInfo');     //查看模板详情

    /**
     *int:
     * [
    'title' => '模板标题',
    'target' => '学习目标',
    'Qdesc' => '问题和选项',
    'answer' => '标准答案'
    ]
     *
     *out:
     *[
     *  'error' => 'required'
     *]
     *
     * error num.:
     *  parameter error -1
     * database error -2
     */
    Route::post('createPreTemp','PreTempController@createPreTemp');      //创建预习模板

    /**
     * in:
     * [
     *  'pretempid' => 'required'
     * ]
     *
     * out:
     * [
     *  'error' => 'required'
     * ]
     *
     * error num.:
     *  parameter error -1
     * database error -2
     */
    Route::post('deletePreTemp','PreTempController@deletePreTemp');    //删除模板

    /**
     * in:
     * [
    'pretempid' => '模板id',
    'title' => '模板标题',
    'target' => '预习目标',
    'Qdesc' => '问题和选项',
    'answer' => '标准答案'
    ]
     *
     * out:
     * [
     *  'error' => 'required'
     * ]
     *
     * error num.:
     *  parameter error -1
     * database error -2
     */
    Route::post('modifyPreTemp','PreTempController@modifyPreTemp');    //修改模板

    /**
     * in:
     * [
     *  'pretempid' => 'required'
     * ]
     *
     * out:
     * [
     *  'error' => 'required'
     * ]
     *
     * error num.:
     *  parameter error -1
     * database error -2
     */
    Route::post('publishPre','PreTempController@publish');         //发布预习模板

    /**
     *in:
     * [
     * 'data.limit' => 'required',
     * 'data.offset' => 'required'
     * ]
     *
     * out:
     * [
     *  'error' => 'required',
     *  'total' => 'all number of result',
     *  'rows' => ''
     * ]
     *
     * error num.:
     *  parameter error -1
     */
    Route::post('preTempList','PreTempController@preTempList');        //显示预习模板列表--老师

    /**
     *in:
    [
    'tempid' => 'required'
    ]
     *
     *out:
     * [
    'error' => 0,
    'data' => [
    'collectionid' => '回收id',
    'stuId' => '学号',
    'stuclass' => '班级',
    'stuname' => '学生名字',
    'marked' => '心得是否评分',
    'score' => '选择题分数',
    'feedback' => '心得',
    'expscore' => '心得分数'
    'difficulty' => '题目难度'
    ]
    ]
     *
     *
     */
    Route::post('preCollectionList','PreCollectionController@preCollectionList');        //显示某模板的学生答卷列表--老师

    /**
     *
     *in：
    [
    'precollectionid' => 'required'
    ]
     *
     *
     *out:
     *[
    'error' => 0,
    'data' => [
    'precollectionid' => '回收id',
    'stuId' => '学号',
    'pretempid' => '预习模板id',
    'target' => '预习目标',
    'Qdesc' => '问题和选项',
    'answer' => '标准答案'，
    'difficulty' => '题目难度'，
    'selectscore' => '选择题分数'
    'feedback' => '心得',
    'mark' => '心得是否评分',
    'expscore' => '心得分数'
    ]
    ]
     *
     */
    Route::post('showPreCollectionInfo','PreCollectionController@showPreCollectionInfo');    //显示答卷详情--老师

    /**
     *in:
     *[
    'precollectionid' => '回收id',
    'expscore' => '心得分数'
    ]
     *
     *
     * out:
     * [
    'error' => 'required'
    ]
     *
     * error num.:
     *  parameter error -1
     * database error -2
     */
    Route::post('fillPreExpMark','PreCollectionController@fillPreExpMark');                 //填写预习心得分数


});



//实验--学生
Route::group(['middleware' => 'auth'],function() {

    /**
     * in:
     * []
     *
     * out:
     * [
    'error' => 0,
    "data" => [
    'expTempId' => '预习模板id',
    'title' => '模板标题',
    'startTime' => '答题开始时间',
    'deadLine' => '答题截止时间',
    'duringtime' => '是否在答题时间段内',
    'submitted' => '是否提交过',
    'selectscore' => '选择题分数',
    'expscore' => '心得分数'
    ]
    ]
     *
     *
     */
    Route::post('expTempListStu','ExpTempController@expTempListStu');   //显示实验模板列表--学生

    /**
     * in:
     * []
     *
     * out:
     * [
    'error' => 0,
    "data" => [
    'expTempId' => '实验模板id',
    "title" => '模板标题',
    "target" => '目标',
    "startTime" => '答题开始时间',
    "deadLine" => '答题截止时间',
    "Qdesc" => '问题和选项',
    ]
    ]
     *
     *
     */
    Route::post('showExpInfoStu','ExpTempController@showExpInfoStu');     //学生获取题目进行答题

    /**
     * in:
     * [
    'expTempId' => 'required',
    ]
     *
     * out:
     * [
    'error' => 0,
    "data" => [
    "title" => '模板标题',
    "target" => '目标',
    "startTime" => '答题开始时间',
    "deadLine" => '答题截止时间',
    "Qdesc" => '问题和选项',
    "answer" => '标准答案',
    "result" => '学生提交的答案',
    "selectscore" => '选择题分数',
    "experience" => '心得',
    "marked" => '心得是否评分',
    "expscore" => '心得分数'，
    "submitTime" => '提交时间'
    ]
    ]
     *
     */
    Route::post('checkExpInfo','ExpTempController@checkExpInfo');     //学生查看已答题目

    /**
     *in:
     * [
    'expTempId' => '实验模板id',
    'result' => '学生提交答案',
    'experience' => '心得',
    'difficulty' => '题目难度'
    ]
     *
     *out:
     * [
    'error' => 'required'
    ]
     *
     *
     */
    Route::post('submitExp','ExpCollectionController@submitExp');     //提交实验试卷

});

//实验--老师
Route::group(['middleware' => 'teacherAuth'],function() {
    //实验___老师
    /**
     *int:
     * [
    'title' => '模板标题',
    'target' => '学习目标',
    'Qdesc' => '问题和选项',
    'answer' => '标准答案'
    ]
     *
     *out:
     *[
     *  'error' => 'required'
     *]
     *
     * error num.:
     *  parameter error -1
     * database error -2
     */
    Route::post('createExpTemp','ExpTempController@createExpTemp');      //创建实验模板

    /**
     * in:
     * [
     *  'expTempId' => 'required'
     * ]
     *
     * out:
     * [
     *  'error' => 'required'
     * ]
     *
     * error num.:
     *  parameter error -1
     * database error -2
     */
    Route::post('deleteExpTemp','ExpTempController@deleteExpTemp');    //删除实验模板

    /**
     *
     * in:
     * [
     *  'expTempId' => 'required'
     * ]
     *
     *
     * out:
     *  [
    'error' => 0,
    'data' => [
    'expTempId' => '模板id',
    'title' => '模板标题',
    'target' => '实验目标',
    'Qdesc' => '问题和选项',
    'answer' => '标准答案'
    ]
     *
     * out:
     * [
     *  'error' => 'required'
     * ]
     *
     * error num.:
     *  parameter error -1
     * database error -2
     */
    Route::post('modifyExpTemp','ExpTempController@modifyExpTemp');    //修改实验模板

    /**
     * in:
     * [
     *  'expTempId' => 'required'
     * ]
     *
     * out:
     * [
     *  'error' => 'required'
     * ]
     *
     * error num.:
     *  parameter error -1
     * database error -2
     */
    Route::post('publishExp','ExpTempController@publish');         //发布实验模板

    /**
     *in:
     * [
     * 'data.limit' => 'required',
     * 'data.offset' => 'required'
     * ]
     *
     * out:
     * [
     *  'error' => 'required',
     *  'total' => 'all number of result',
     *  'rows' => ''
     * ]
     *
     * error num.:
     *  parameter error -1
     */
    Route::post('expTempList','ExpTempController@expTempList');        //显示实验模板列表--老师

    /**
     *
     * in:
     * [
     *  'expTempId' => 'required'
     * ]
     *
     *
     * out:
     *  [
    'error' => 0,
    'data' => [
    'pretempid' => '模板id',
    'title' => '模板标题',
    'target' => '预习目标',
    'startTime' => '答题开始时间',
    'deadLine' => '答题截止时间',
    'Qdesc' => '问题和选项',
    'answer' => '标准答案'
    ]
    ];
     *
     * error num.:
     *  parameter error -1
     * database error -2
     */
    Route::post('expTempInfo','ExpTempController@expTempInfo');     //查看实验模板详情

    /**
     *in:
    [
    'expTempId' => 'required'
    ]
     *
     *out:
     * [
    'error' => 0,
    'data' => [
    'collectionid' => '回收id',
    'stuId' => '学号',
    'stuclass' => '班级',
    'stuname' => '学生名字',
    'marked' => '心得是否评分',
    'score' => '选择题分数',
    'feedback' => '心得',
    'expscore' => '心得分数'
    'difficulty' => '题目难度'
    ]
    ]
     *
     *
     */
    Route::post('expCollectionList','ExpCollectionController@expCollectionList');           //显示某实验模板的学生答卷

    /**
     *
     *in：
    [
    'expcollectionid' => 'required'
    ]
     *
     *
     *out:
     *[
    'error' => 0,
    'data' => [
    'expcollectionid' => '回收id',
    'stuId' => '学号',
    'expTempId' => '实验模板id',
    'target' => '实验目标',
    'Qdesc' => '问题和选项',
    'answer' => '标准答案'，
    'difficulty' => '题目难度'，
    'selectscore' => '选择题分数'
    'feedback' => '心得',
    'mark' => '心得是否评分',
    'expscore' => '心得分数'
    ]
    ]
     *
     */
    Route::post('showExpCollectionInfo','ExpCollectionController@showExpCollectionInfo');   //显示实验答卷详情

    /**
     *in:
     *[
    'expcollectionid' => '回收id',
    'expscore' => '心得分数'
    ]
     *
     *
     * out:
     * [
    'error' => 'required'
    ]
     *
     * error num.:
     *  parameter error -1
     * database error -2
     */
    Route::post('fillExpExpMark','ExpCollectionController@fillExpExpMark');                 //填写实验心得分数

    Route::post('getStuListForReportScore','ExpCollectionController@getStuListForReportScore');           //输入学生实验报告分数时获取学生列表

    Route::post('fillReportScore','ExpCollectionController@addReportScore');          //添加某个学生的实验报告分数

    Route::post('getStuListForExpExam','ExpCollectionController@getStuListForExpExam');          //添加最后的实验考试成绩时获取学生列表

    Route::post('fillExpExam','ExpCollectionController@fillExpExam');          //添加某个学生的实验考试分数

});



//课后作业--学生
Route::group(['middleware' => 'auth'],function() {

    /**
     * in:
     * []
     *
     * out:
     * [
    'error' => 0,
    "data" => [
    'homeworkTempId' => '预习模板id',
    'title' => '模板标题',
    'startTime' => '答题开始时间',
    'deadLine' => '答题截止时间',
    'duringtime' => '是否在答题时间段内',
    'submitted' => '是否提交过',
    'selectscore' => '选择题分数',
    'expscore' => '心得分数'
    ]
    ]
     *
     *
     */
    Route::post('homeworkTempListStu','HomeworkTempController@homeworkTempListStu');   //显示课后作业模板列表--学生

    /**
     * in:
     * [
     * 'homeworkTempId' => 'required'
     * ]
     *
     * out:
     * [
    'error' => 0,
    "data" => [
    'homeworkTempId' => '课后作业模板id',
    "title" => '模板标题',
    "target" => '目标',
    "startTime" => '答题开始时间',
    "deadLine" => '答题截止时间',
    "Qdesc" => '问题和选项',
    ]
    ]
     *
     *
     */
    Route::post('showHomeworkInfoStu','HomeworkTempController@showHomeworkInfoStu');     //学生获取题目进行答题

    /**
     * in:
     * [
    'homeworkTempId' => 'required',
    ]
     *
     * out:
     * [
    'error' => 0,
    "data" => [
    "title" => '模板标题',
    "target" => '目标',
    "startTime" => '答题开始时间',
    "deadLine" => '答题截止时间',
    "Qdesc" => '问题和选项',
    "answer" => '标准答案',
    "result" => '学生提交的答案',
    "selectscore" => '选择题分数',
    "experience" => '心得',
    "marked" => '心得是否评分',
    "expscore" => '心得分数'，
    "submitTime" => '提交时间'
    ]
    ]
     *
     */
    Route::post('checkHomeworkInfo','HomeworkTempController@checkHomeworkInfo');     //学生查看已答题目

    /**
     *in:
     * [
    'homeworkTempId' => '课后作业模板id',
    'result' => '学生提交答案',
    'experience' => '心得',
    'difficulty' => '题目难度'
    ]
     *
     *out:
     * [
    'error' => 'required'
    ]
     *
     *
     */
    Route::post('submitHomework','HomeworkCollectionController@submitHomework');     //学生提交课后作业试卷

});

//课后作业--老师
Route::group(['middleware' => 'teacherAuth'],function() {
    /**
     *int:
     * [
    'title' => '模板标题',
    'target' => '学习目标',
    'Qdesc' => '问题和选项',
    'answer' => '标准答案'
    ]
     *
     *out:
     *[
     *  'error' => 'required'
     *]
     *
     * error num.:
     *  parameter error -1
     * database error -2
     */
    Route::post('createHomeworkTemp','HomeworkTempController@createHomeworkTemp');      //创建课后作业模板--老师出题

    /**
     *int:
     * [
     * 'title' => '模板标题',
     *'target' => '学习目标',
     *'startTime' => '开始时间',
     *'deadLine' => '截止时间',
    ]
     *
     *out:
     *[
     *  'error' => 'required'
     *]
     *
     * error num.:
     *  parameter error -1
     * database error -2
     */
    Route::post('createHomeworkTempAuto','HomeworkTempController@createHomeworkTempAuto');      //创建课后作业模板--自动出题

    /**
     * in:
     * [
     *  'homeworkTempId' => 'required'
     * ]
     *
     * out:
     * [
     *  'error' => 'required'
     * ]
     *
     * error num.:
     *  parameter error -1
     * database error -2
     */
    Route::post('deleteHomeworkTemp','HomeworkTempController@deleteHomeworkTemp');    //删除课后作业模板

    /**
     *
     * in:
     * [
     *  'homeworkTempId' => 'required'
     *  'error' => 0,
     *  'data' => [
     *  'homeworkTempId' => '模板id',
     *  'title' => '模板标题',
     *  'target' => '课后作业目标',
     *  'Qdesc' => '问题和选项',
     *  'answer' => '标准答案'
     * ]
     *
     * out:
     * [
     *  'error' => 'required'
     * ]
     *
     * error num.:
     *  parameter error -1
     * database error -2
     */
    Route::post('modifyHomeworkTemp','HomeworkTempController@modifyHomeworkTemp');    //修改课后作业模板--老师出题

    /**
     *
     * in:
     * [
     *  'homeworkTempId' => '模板id',
     *  'title' => '模板标题',
     *  'target' => '课后作业目标',
     *  'Qdesc' => '问题和选项',
     *  'answer' => '标准答案'
     * ]
     *
     * out:
     * [
     *  'error' => 'required'
     * ]
     *
     * error num.:
     *  parameter error -1
     * database error -2
     */
    Route::post('modifyHomeworkTempAuto','HomeworkTempController@modifyHomeworkTempAuto');    //修改课后作业模板---自动出题

    /**
     * in:
     * [
     *  'homeworkTempId' => 'required'
     * ]
     *
     * out:
     * [
     *  'error' => 'required'
     * ]
     *
     * error num.:
     *  parameter error -1
     * database error -2
     */
    Route::post('publishHomework','HomeworkTempController@publish');         //发布课后作业模板

    /**
     *in:
     * [
     * 'data.limit' => 'required',
     * 'data.offset' => 'required'
     * ]
     *
     * out:
     * [
     *  'error' => 'required',
     *  'total' => 'all number of result',
     *  'rows' => ''
     * ]
     *
     * error num.:
     *  parameter error -1
     */
    Route::post('homeworkTempList','HomeworkTempController@homeworkTempList');        //显示课后作业模板列表--老师

    /**
     *
     * in:
     * [
     *  'homeworkTempId' => 'required'
     * ]
     *
     *
     * out:
     *  [
    'error' => 0,
    'data' => [
    'homeworktempid' => '模板id',
    'title' => '模板标题',
    'target' => '预习目标',
    'startTime' => '答题开始时间',
    'deadLine' => '答题截止时间',
    'Qdesc' => '问题和选项',
    'answer' => '标准答案'
    ]
    ];
     *
     * error num.:
     *  parameter error -1
     * database error -2
     */
    Route::post('homeworkTempInfo','HomeworkTempController@homeworkTempInfo');     //查看课后作业模板详情--老师

    /**
     *in:
    [
    'homeworkTempId' => 'required'
    ]
     *
     *out:
     * [
    'error' => 0,
    'data' => [
    'collectionid' => '回收id',
    'stuId' => '学号',
    'stuclass' => '班级',
    'stuname' => '学生名字',
    'marked' => '心得是否评分',
    'score' => '选择题分数',
    'feedback' => '心得',
    'expscore' => '心得分数'
    'difficulty' => '题目难度'
    ]
    ]
     *
     *
     */
    Route::post('homeworkCollectionList','HomeworkCollectionController@homeworkCollectionList');           //显示课后作业模板的学生答卷列表

    /**
     *
     *in：
    [
    'homeworkCollectionId' => 'required'
    ]
     *
     *
     *out:
     *[
    'error' => 0,
    'data' => [
    'homeworkCollectionId' => '回收id',
    'stuId' => '学号',
    'expTempId' => '课后作业模板id',
    'target' => '课后作业目标',
    'Qdesc' => '问题和选项',
    'answer' => '标准答案'，
    'difficulty' => '题目难度'，
    'selectscore' => '选择题分数'
    'feedback' => '心得',
    'mark' => '心得是否评分',
    'expscore' => '心得分数'
    ]
    ]
     *
     */
    Route::post('showHomeworkCollectionInfo','HomeworkCollectionController@showHomeworkCollectionInfo');   //显示课后作业答卷详情--老师

    /**
     *in:
     *[
    'homeworkCollectionId' => '回收id',
    'expscore' => '心得分数'
    ]
     *
     *
     * out:
     * [
    'error' => 'required'
    ]
     *
     * error num.:
     *  parameter error -1
     * database error -2
     */
    Route::post('fillHomeworkExpMark','HomeworkCollectionController@fillHomeworkExpMark');                 //填写课后作业心得分数

});

//课后作业题库操作
Route::group(['middleware' => 'teacherAuth'],function (){
    /**
     * 存储题目到题库中
     * in:
     * [
     *      'Qdesc' => 'required',
     *      'answer' => 'required',
     *      'week' => 'required'
     * ]
     *
     *out:
     * [
     *      'error' => 'required'
     * ]
     *
     * error num.:
     *  parameter error -1
     * database error -2
     */
    Route::post('createHomeworkQues','HomeworkQuesController@createHomeworkQues');      //存储题目到题库中

    /**
     * 删除题库中的题目
     * in:
     * [
     *      'questionId' => 'required'
     * ]
     *out:
     * [
     *      'error' => 'required'
     * ]
     *
     * error num.:
     *  parameter error -1
     * database error -2
     */
    Route::post('deleteHomeworkQues','HomeworkQuesController@deleteHomeworkQues');      //删除题库中的题目

    /**
     * 显示题目列表
     * in:
     * [
     *      'data.limit' => 'required',
     *      'data.offset' => 'required'
     * ]
     *
     * out:
     * [
     *      'error' => 'required',
     *      'total' => 'all number of result',
     *      'rows' => ''
     * ]
     *
     * error num.:
     *  parameter error -1
     */
    Route::post('homeworkQuesList','HomeworkQuesController@homeworkQuesList');      //显示题目列表

    /**
     * 修改题目
     * in:
     * [
     *      'questionId' => 'required',
     *      'Qdesc' => 'required',
     *      'answer' => 'required',
     *      'week' => 'required'
     * ]
     *
     * out:
     * [
     *      'error' => 'required'
     * ]
     *
     * error num.:
     *  parameter error -1
     * database error -2
     */
    Route::post('updateHomeworkQues','HomeworkQuesController@updateHomeworkQues');  //修改题目
});



//每周总结--学生
Route::group(['middleware' => 'auth'],function() {
    Route::post('sumTempList', 'SumTempController@sumTempList');      //获取每周总结列表--学生
    Route::post('submitSum', 'SumCollectionController@submitSum');    //未分组的学生提交总结
    Route::post('showSumCollectionInfo', 'SumCollectionController@showSumCollectionInfo');//未分组学生查看填写详情
    Route::post('getGroupMember', 'GroupController@getGroupMember');   //已经分组的学生进入每周小结填写页面时获取小组内成员信息
    Route::post('submitSumGroupLeader', 'SumCollectionController@submitSumGroupLeader');    //组长提交每周小结
    Route::post('submitSumGroupMember', 'SumCollectionController@submitSumGroupMember');    //组员提交每周小结（不包含每周总结，只有评价）
    Route::post('showSumInfoGroup', 'SumCollectionController@showSumInfoGroup');     //已经分组的学生查看填写总结的详情
});

//每周总结--老师
Route::group(['middleware' => 'teacherAuth'],function() {
    Route::post('sumTempListTeacher', 'SumTempController@sumTempListTeacher');   //获每周总结模板列表--老师
    Route::post('getSumList', 'SumCollectionController@getSumList');      //获取某一周学生提交的总结列表--老师
    Route::post('showGroupAssessment', 'SumCollectionController@showGroupAssessment');    //查看某一小组的互评
    Route::post('fillSummaryMark','SumCollectionController@fillSummaryMark');    //评分
});



//每章总结--老师
Route::group(['middleware' => 'teacherAuth'],function() {
    Route::post('getStuListForChapterSum','ChapterSumController@getStuListForChapterSum');      //添加每章总结时获取学生列表
    Route::post('fillChapterSumScore','ChapterSumController@fillChapterSumScore');      //添加某个学生的某章总结分数，并且算出该章成绩（添加时确保该章所有周数已评分，否则评分不会被计入章成绩）
});



//随堂测试
Route::group(['middleware' => 'teacherAuth'],function() {
    Route::post('getStuListForTestscore','TestScoreController@getStuListForTestscore');     //添加随堂测试成绩时获取学生列表
    Route::post('fillTestScore','TestScoreController@fillTestScore');    //添加某一位学生的随堂测试成绩
});



//计算每道题的每个答案选的人数--老师
/**
 * in:
 * [
 * 'preTempId' => 'required'
 * ]
 *
 * out:
 * [
 * '$everyAnsNum' => [[0,0,0,0],[0,0,0,0],[0,0,0,0],[0,0,0,0],[0,0,0,0]]
 * ]
 *
 * error num:
 * parameter error -1
 * database error -2
 */
Route::group(['middleware' => 'teacherAuth'],function() {
    Route::post('preCountNum', 'PreTempController@countNum');    //课前预习学生答卷结果统计
    Route::post('expCountNum', 'ExpTempController@countNum');    //实验学生答卷结果统计
    Route::post('homeworkCountNum', 'HomeworkTempController@countNum');  //课后作业学生答卷结果统计
});