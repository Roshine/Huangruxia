<?php

use App\Group;
use App\HomeworkQuestion;
use App\PreCollection;
use App\PreTemplate;
use App\SumTemplate;
use App\User;
use Illuminate\Database\Seeder;

class UnitQuestionsseeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //填充课后练习题库
//        UnitQuestion::create([
//            'question' => 'question2',
//            'option' => str_random(10),
//            'answer' => str_random(10),
//            'chapter' => '1',
//         ]);
//        填充课前预习题库
//        $UnitQuestion = factory(HomeworkQuestion::class,20)->create();
//        $UnitQuestion = factory(PreCollection::class,10)->create();

        //填充预习模板
//        PreTemplate::create([
//
//            'title'=>'第二课时',
//            'target'=>'预习计算机第二节',
//            'content'=>json_encode(array(['question'=>'信息处理对信息进行接收()',
//                                           'options'=>['存储、转化、发布','转化、存储、传输','传输、存储、转化','存储 转化 传输 发布']],
//                                        ['question'=>'信息处理对信息进行接收()',
//                                            'options'=>['存储、转化、发布','转化、存储、传输','传输、存储、转化','存储 转化 传输 发布']],
//                                        ['question'=>'信息处理对信息进行接收()',
//                                            'options'=>['存储、转化、发布','转化、存储、传输','传输、存储、转化','存储 转化 传输 发布']],
//                                        ['question'=>'信息处理对信息进行接收()',
//                                            'options'=>['存储、转化、发布','转化、存储、传输','传输、存储、转化','存储 转化 传输 发布']],
//                                        ['question'=>'信息处理对信息进行接收()',
//                                            'options'=>['存储、转化、发布','转化、存储、传输','传输、存储、转化','存储 转化 传输 发布']]
//                                        )
//                                    ),
//            'answers'=>json_encode(array(1,2,3,0,1)),
//            'everyAnsNum'=>json_encode(array([0,0,0,0],[0,0,0,0],[0,0,0,0],[0,0,0,0],[0,0,0,0])),
//            'startTime'=>'2016-08-11',
//            'deadLine'=>'2016-08-16'
//        ]);

//        //填充预习回收模板
//        PreCollection::create([
//            'stuId'=>'0121210680406',
//            'preTempId'=>'4',
//            'result'=>json_encode(array(3,2,3,0,1)),
//            'resScore'=>'4',
//            'experience'=>'呵呵呵呵呵呵呵呵呵呵呵呵呵呵呵呵呵呵呵呵呵呵呵呵呵呵呵呵呵呵呵呵呵呵呵呵呵呵呵呵',
//            'expScore'=>'',
//            'marked'=>'no',
//            'difficulty'=>json_encode([1,2,2,3,2])
//        ]);
        //填充每周总结模板
        SumTemplate::create([
            'weekId' => '7',
            'startTime' => '2016-9-10',
            'deadLine' => '2016-8-11'
        ]);
        //填充用户
//        User::create([
//            'stuId' => '0121210680405',
//            'name' => '张凯',
//            'email' => '123@qq.com',
//            'password' => 123456,
//            'groupId' => 1
//        ]);
        //填充小组表
//        Group::create([
//            'groupId' => 2,
//            'leaderId' => '0121210680403',
//            'leaderName' => '张三'
//        ]);

    }

}
