<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

use App\PreCollection;
use App\HomeworkQuestion;

$factory->define(HomeworkQuestion::class, function (Faker\Generator $faker) {
    return [
        'content' => json_encode(array("question" => str_random(20),
                                        "options" => array(str_random(10),str_random(10),str_random(10),str_random(10)))),
        'answer' => random_int(0,3),
        'week' => '6',
    ];
}


//$factory->define(PreCollection::class, function (Faker\Generator $faker) {
//    return [
//        'stuId' => random_int(100,200),
//        'preTempId' => '1',
//        'result' => json_encode(array(random_int(0,3),random_int(0,3),random_int(0,3),random_int(0,3),random_int(0,3))),
//        'resScore' => '4',
//        'experience' => str_random(10),
//        'difficulty' => json_encode(array(random_int(1,5),random_int(1,5),random_int(1,5),random_int(1,5),random_int(1,5)))
//    ];
//}

);
