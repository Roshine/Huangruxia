<?php

namespace App\Http\Controllers;

use App\HomeworkQuestion;
use Dotenv\Validator;
use Illuminate\Http\Request;

class HomeworkQuesController extends Controller
{
    /**
     * @param Request $request
     * @return array
     * 存储题目到题库中
     */
    public function createHomeworkQues(Request $request){
        $validator = Validator::make($request->all,[
            'Qdesc' => 'required',
            'answer' => 'required',
            'week' => 'required'
        ]);

        if ($validator->fails()){
            return[
                'error' => -1,
                'desc' => $validator->errors(),
            ];
        }

        $content = $request->Qdesc;
        $answer = $request->answer;
        $week = $request->week;
        $res = HomeworkQuestion::create([
            'content' => $content,
            'answer' => $answer,
            'week' => $week
        ]);

        return [
            'error' => (!$res ? -2 : 0)
        ];
    }

    /**
     * @param Request $request
     * @return array
     * 删除题目
     */
    public function deleteHomeworkQues(Request $request){
        $validator = Validator::make($request->all(),[
           'questionId' => 'required'
        ]);

        if ($validator->fails()){
            return [
                'error' => -1,
                'desc' => $validator->errors(),
            ];
        }

        $res = HomeworkQuestion::where('id',$request->questionId)->delete();

        return [
            'error' => (!$res ? -2 : 0)
        ];
    }

    public function updateHomeworkQues(Request $request){
        $validator = Validator::make($request->all(),[
            'questionId' => 'required',
            'Qdesc' => 'required',
            'answer' => 'required',
            'week' => 'required'
        ]);

        if ($validator->fails()){
            return [
                'error' => -1
            ];
        }

        $res = HomeworkQuestion::where('id',$request->questionId)->update([
            'content' => $request->Qdesc,
            'answer' => $request->answer,
            'week' => $request->week
        ]);

        return [
            'error' => (!$res ? -2 : 0)
        ];
    }

    /**
     * @param Request $request
     * @return array
     * 显示题库中的题目
     */
    public function homeworkQuesList(Request $request){
        $validator = Validator::make($request->all(), [
            'data.limit' => 'required',
            'data.offset' => 'required'
        ]);

        if ($validator->fails()) {
            return [
                'error' => -1,
                'desc' => $validator->errors(),
            ];
        }

        $query = HomeworkQuestion::select('id','content','answer','week')->orderBy('weeek','desc');

        $num = $query->count();

        $HomeworkQuses = $query->skip($request['data']['offset'])
            ->take($request['data']['limit'])
            ->get();

        return [
            'error' => 0,
            'total' => $num,
            'rows' => $HomeworkQuses
        ];
    }

    /**
     * 根据week返回7个题目
     */
    public function getcontent($request){
        $ids = $this->norand($request);
        $conandid = HomeworkQuestion::whereIn('id',$ids)->select('id','content')->get();
        $contents = [];
        foreach ($conandid as $item) {
            $content = json_decode($item->content);
            $contents[]=[
                "questionId" => $item->id,
                "question" => $content->question,
                "options" => $content->options
            ];
        }
        return $contents;
    }

    /**
     * 根据week随机选择7道题并返回id
     * @param $chapter
     * @return array
     */
    public function norand($week){
        $rand_obj=HomeworkQuestion::where('week',$week)->lists('id');
        $rand_array=$this->objarray_to_array($rand_obj);
        shuffle($rand_array);
        $ids=array_slice($rand_array,0,7);
        return $ids;
    }

    /**
     * 对象数组转为普通数组
     *
     * JSON字串经decode解码后为一个对象数组，
     * 为此必须转为普通数组后才能进行后续处理，
     * 此函数支持多维数组处理。
     *
     * @param array
     * @return array
     */
    public function objarray_to_array($obj) {
        $ret = array();
        foreach ($obj as $key => $value) {
            if (gettype($value) == "array" || gettype($value) == "object"){
                $ret[$key] = $this->objarray_to_array($value);
            }else{
                $ret[$key] = $value;
            }
        }
        return $ret;
    }
}
