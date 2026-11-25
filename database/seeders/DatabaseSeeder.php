<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        /*
         * DU LIEU DEMO TV4
         * - 5 danh muc
         * - 1 admin, 3 giang vien, 15 hoc vien
         * - 2 khoa hoc demo
         * - Moi khoa hoc co 5 bai hoc va 10 cau hoi
         * - Co du lieu ghi danh, tien do va luot thi mau
         */

        // 1. Tao 5 danh muc
        $categoryNames = [
            'Lap trinh',
            'Co so du lieu',
            'Thiet ke web',
            'Mang may tinh',
            'Phat trien phan mem',
        ];

        $categoryIds = [];

        foreach ($categoryNames as $name) {
            $category = DB::table('categories')
                ->where('name', $name)
                ->first();

            if (!$category) {
                $id = DB::table('categories')->insertGetId([
                    'name' => $name,
                    'description' => 'Danh muc ' . $name,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $id = $category->id;
            }

            $categoryIds[$name] = $id;
        }

        // 2. Tao tai khoan giang vien
        $teachers = [
            [
                'name' => 'Nguyen Van An',
                'email' => 'teacher01@example.com'
            ],
            [
                'name' => 'Tran Thi Binh',
                'email' => 'teacher02@example.com'
            ],
            [
                'name' => 'Le Van Cuong',
                'email' => 'teacher03@example.com'
            ],
        ];

        foreach ($teachers as $teacher) {
            User::updateOrCreate(
                ['email' => $teacher['email']],
                [
                    'name' => $teacher['name'],
                    'password' => '12345678',
                    'role' => 'teacher',
                ]
            );
        }

        // Tao tai khoan admin
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrator',
                'password' => '12345678',
                'role' => 'admin',
            ]
        );

        // 3. Tao 15 tai khoan hoc vien
        $students = [];

        for ($i = 1; $i <= 15; $i++) {
            $email = sprintf('student%02d@example.com', $i);

            $student = User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => 'Hoc vien ' . $i,
                    'password' => '12345678',
                    'role' => 'student',
                ]
            );

            $students[] = $student;
        }

        // 4. Tao dung 2 khoa hoc demo
        $courseData = [
            [
                'title' => 'Lap trinh PHP co ban',
                'category' => 'Lap trinh',
                'teacher' => 'teacher01@example.com',
                'description' => 'Khoa hoc gioi thieu cu phap PHP, bien, dieu kien, vong lap va ham.',
            ],
            [
                'title' => 'Lap trinh Laravel co ban',
                'category' => 'Phat trien phan mem',
                'teacher' => 'teacher02@example.com',
                'description' => 'Khoa hoc huong dan xay dung ung dung web voi Laravel, MVC, migration va Eloquent.',
            ],
        ];

        $courses = [];

        foreach ($courseData as $data) {
            $teacherId = User::where('email', $data['teacher'])
                ->value('id');

            $course = DB::table('courses')
                ->where('title', $data['title'])
                ->first();

            $values = [
                'category_id' => $categoryIds[$data['category']],
                'teacher_id' => $teacherId,
                'title' => $data['title'],
                'description' => $data['description'],
                'quiz_minutes' => 30,
                'status' => 'public',
                'updated_at' => now(),
            ];

            if (!$course) {
                $values['created_at'] = now();

                $courseId = DB::table('courses')
                    ->insertGetId($values);
            } else {
                DB::table('courses')
                    ->where('id', $course->id)
                    ->update($values);

                $courseId = $course->id;
            }

            $courses[] = $courseId;
        }

        // 5. Tao 5 bai hoc cho moi khoa hoc
        $lessonsByCourse = [
            'Lap trinh PHP co ban' => [
                [
                    'title' => 'Gioi thieu PHP va moi truong chay',
                    'content' => 'PHP la ngon ngu lap trinh phia may chu, thuong dung de xay dung website dong. Bai hoc gioi thieu PHP, web server va cach chay mot tep PHP.',
                ],
                [
                    'title' => 'Bien, hang va cac kieu du lieu',
                    'content' => 'Bai hoc trinh bay cach khai bao bien bang dau $, hang, chuoi, so, boolean, mang va mot so phep ep kieu co ban trong PHP.',
                ],
                [
                    'title' => 'Toan tu va cau truc dieu kien',
                    'content' => 'Bai hoc huong dan su dung toan tu so hoc, so sanh, logic va cac cau truc if, elseif, else, switch de xu ly dieu kien.',
                ],
                [
                    'title' => 'Vong lap va mang trong PHP',
                    'content' => 'Bai hoc gioi thieu for, while, foreach va cach tao, truy cap, duyet mang de xu ly danh sach du lieu.',
                ],
                [
                    'title' => 'Ham va xu ly form co ban',
                    'content' => 'Bai hoc huong dan khai bao ham, tham so, gia tri tra ve va tiep nhan du lieu form thong qua GET va POST.',
                ],
            ],

            'Lap trinh Laravel co ban' => [
                [
                    'title' => 'Tong quan Laravel va cau truc MVC',
                    'content' => 'Bai hoc gioi thieu Laravel, cach tao du an va vai tro cua Model, View, Controller trong kien truc MVC.',
                ],
                [
                    'title' => 'Routing va Controller',
                    'content' => 'Bai hoc huong dan khai bao route, su dung tham so tren URL va tao Controller de tiep nhan, xu ly yeu cau.',
                ],
                [
                    'title' => 'Blade Template va truyen du lieu',
                    'content' => 'Bai hoc gioi thieu Blade, layout, include, hien thi bien va truyen du lieu tu Controller sang View.',
                ],
                [
                    'title' => 'Migration va Eloquent ORM',
                    'content' => 'Bai hoc huong dan tao migration, khai bao cot, chay migration va su dung Eloquent Model de thao tac voi co so du lieu.',
                ],
                [
                    'title' => 'CRUD va Validation',
                    'content' => 'Bai hoc trinh bay quy trinh them, xem, sua, xoa du lieu va kiem tra du lieu dau vao bang Validation.',
                ],
            ],
        ];

        foreach ($courseData as $data) {
            $courseId = DB::table('courses')
                ->where('title', $data['title'])
                ->value('id');

            foreach ($lessonsByCourse[$data['title']] as $index => $lessonData) {
                $position = $index + 1;

                $lesson = DB::table('lessons')
                    ->where('course_id', $courseId)
                    ->where('position', $position)
                    ->first();

                $values = [
                    'title' => $lessonData['title'],
                    'content' => $lessonData['content'],
                    'video_url' => null,
                    'updated_at' => now(),
                ];

                if (!$lesson) {
                    $values['course_id'] = $courseId;
                    $values['position'] = $position;
                    $values['created_at'] = now();

                    DB::table('lessons')->insert($values);
                } else {
                    DB::table('lessons')
                        ->where('id', $lesson->id)
                        ->update($values);
                }
            }
        }

        // 6. Tao 10 cau hoi rieng cho moi khoa hoc
        $questionsByCourse = [
            'Lap trinh PHP co ban' => [
                [
                    'q' => 'PHP thuong duoc su dung de lam gi?',
                    'a' => 'Lap trinh phia may chu',
                    'b' => 'Thiet ke mach dien',
                    'c' => 'Bien tap video',
                    'd' => 'Ve do hoa 3D',
                    'correct' => 'A'
                ],
                [
                    'q' => 'Ky tu nao dung de khai bao bien trong PHP?',
                    'a' => '#',
                    'b' => '$',
                    'c' => '@',
                    'd' => '&',
                    'correct' => 'B'
                ],
                [
                    'q' => 'Lenh nao dung de hien thi noi dung trong PHP?',
                    'a' => 'printText()',
                    'b' => 'show()',
                    'c' => 'echo',
                    'd' => 'displayHTML()',
                    'correct' => 'C'
                ],
                [
                    'q' => 'Kieu du lieu nao dung de luu gia tri dung hoac sai?',
                    'a' => 'Boolean',
                    'b' => 'String',
                    'c' => 'Array',
                    'd' => 'Float',
                    'correct' => 'A'
                ],
                [
                    'q' => 'Cau lenh nao dung de re nhanh theo dieu kien?',
                    'a' => 'foreach',
                    'b' => 'function',
                    'c' => 'echo',
                    'd' => 'if',
                    'correct' => 'D'
                ],
                [
                    'q' => 'Vong lap nao thuong dung de duyet tung phan tu cua mang?',
                    'a' => 'switch',
                    'b' => 'foreach',
                    'c' => 'return',
                    'd' => 'echo',
                    'correct' => 'B'
                ],
                [
                    'q' => 'Tu khoa nao dung de khai bao ham trong PHP?',
                    'a' => 'method',
                    'b' => 'defineFunction',
                    'c' => 'function',
                    'd' => 'func',
                    'correct' => 'C'
                ],
                [
                    'q' => 'Mang trong PHP duoc dung de lam gi?',
                    'a' => 'Luu tap hop nhieu gia tri',
                    'b' => 'Chi luu mot so nguyen',
                    'c' => 'Ket noi Internet',
                    'd' => 'Tao giao dien CSS',
                    'correct' => 'A'
                ],
                [
                    'q' => 'Phuong thuc HTTP nao thuong dung de gui du lieu form?',
                    'a' => 'STYLE',
                    'b' => 'LINK',
                    'c' => 'IMAGE',
                    'd' => 'POST',
                    'correct' => 'D'
                ],
                [
                    'q' => 'Ham co the tra ket qua ve bang tu khoa nao?',
                    'a' => 'echo',
                    'b' => 'return',
                    'c' => 'break',
                    'd' => 'continue',
                    'correct' => 'B'
                ],
            ],

            'Lap trinh Laravel co ban' => [
                [
                    'q' => 'Laravel la framework cua ngon ngu nao?',
                    'a' => 'Java',
                    'b' => 'Python',
                    'c' => 'PHP',
                    'd' => 'C#',
                    'correct' => 'C'
                ],
                [
                    'q' => 'MVC la viet tat cua cum tu nao?',
                    'a' => 'Model View Controller',
                    'b' => 'Main Variable Code',
                    'c' => 'Multiple View Class',
                    'd' => 'Method View Command',
                    'correct' => 'A'
                ],
                [
                    'q' => 'Tep route web mac dinh cua Laravel nam o dau?',
                    'a' => 'app/Models',
                    'b' => 'routes/web.php',
                    'c' => 'public/index.html',
                    'd' => 'database/seeders',
                    'correct' => 'B'
                ],
                [
                    'q' => 'Lenh nao tao Controller bang Artisan?',
                    'a' => 'php artisan make:model',
                    'b' => 'php artisan serve',
                    'c' => 'php artisan migrate',
                    'd' => 'php artisan make:controller',
                    'correct' => 'D'
                ],
                [
                    'q' => 'Blade la thanh phan nao cua Laravel?',
                    'a' => 'He quan tri co so du lieu',
                    'b' => 'Cong cu quan ly Git',
                    'c' => 'Template engine',
                    'd' => 'May chu web',
                    'correct' => 'C'
                ],
                [
                    'q' => 'Migration trong Laravel dung de lam gi?',
                    'a' => 'Quan ly thay doi cau truc co so du lieu',
                    'b' => 'Tao hinh anh',
                    'c' => 'Quan ly email',
                    'd' => 'Nen tep du an',
                    'correct' => 'A'
                ],
                [
                    'q' => 'Eloquent ORM ho tro thao tac voi thanh phan nao?',
                    'a' => 'Tep CSS',
                    'b' => 'Co so du lieu thong qua Model',
                    'c' => 'Trinh duyet',
                    'd' => 'May in',
                    'correct' => 'B'
                ],
                [
                    'q' => 'Lenh nao khoi dong may chu phat trien Laravel?',
                    'a' => 'php artisan migrate',
                    'b' => 'php artisan route:list',
                    'c' => 'php artisan serve',
                    'd' => 'php artisan make:model',
                    'correct' => 'C'
                ],
                [
                    'q' => 'Validation duoc dung de lam gi?',
                    'a' => 'Tao tai khoan GitHub',
                    'b' => 'Doi ten bang',
                    'c' => 'Nen co so du lieu',
                    'd' => 'Kiem tra du lieu dau vao',
                    'correct' => 'D'
                ],
                [
                    'q' => 'Thu muc nao chua cac Model trong Laravel?',
                    'a' => 'app/Models',
                    'b' => 'resources/css',
                    'c' => 'routes',
                    'd' => 'storage/logs',
                    'correct' => 'A'
                ],
            ],
        ];

        foreach ($courseData as $data) {
            $courseId = DB::table('courses')
                ->where('title', $data['title'])
                ->value('id');

            foreach ($questionsByCourse[$data['title']] as $index => $question) {
                $questionText = 'Cau ' . ($index + 1) . ': ' . $question['q'];

                $exists = DB::table('questions')
                    ->where('course_id', $courseId)
                    ->where('question_text', $questionText)
                    ->exists();

                if (!$exists) {
                    DB::table('questions')->insert([
                        'course_id' => $courseId,
                        'question_text' => $questionText,
                        'option_a' => $question['a'],
                        'option_b' => $question['b'],
                        'option_c' => $question['c'],
                        'option_d' => $question['d'],
                        'correct_option' => $question['correct'],
                        'points' => 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        // 7. Tao ghi danh mau: moi hoc vien ghi danh ca 2 khoa hoc
        foreach ($students as $student) {
            foreach ($courses as $courseId) {
                $exists = DB::table('enrollments')
                    ->where('user_id', $student->id)
                    ->where('course_id', $courseId)
                    ->exists();

                if (!$exists) {
                    DB::table('enrollments')->insert([
                        'user_id' => $student->id,
                        'course_id' => $courseId,
                        'enrolled_at' => now(),
                        'status' => 'active',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        // 8. Tao tien do mau cho 5 hoc vien o bai hoc dau tien cua khoa PHP
        $phpCourseId = DB::table('courses')
            ->where('title', 'Lap trinh PHP co ban')
            ->value('id');

        $firstLessonId = DB::table('lessons')
            ->where('course_id', $phpCourseId)
            ->where('position', 1)
            ->value('id');

        for ($i = 0; $i < 5; $i++) {
            $student = $students[$i];

            $exists = DB::table('lesson_progress')
                ->where('user_id', $student->id)
                ->where('lesson_id', $firstLessonId)
                ->exists();

            if (!$exists) {
                DB::table('lesson_progress')->insert([
                    'user_id' => $student->id,
                    'lesson_id' => $firstLessonId,
                    'status' => 'completed',
                    'completed_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // 9. Tao 3 luot lam bai mau
        for ($i = 0; $i < 3; $i++) {
            $student = $students[$i];
            $courseId = $courses[$i % 2];

            $exists = DB::table('quiz_attempts')
                ->where('user_id', $student->id)
                ->where('course_id', $courseId)
                ->exists();

            if (!$exists) {
                DB::table('quiz_attempts')->insert([
                    'user_id' => $student->id,
                    'course_id' => $courseId,
                    'started_at' => now()->subMinutes(20),
                    'submitted_at' => now(),
                    'score' => 8,
                    'answers' => json_encode([
                        '1' => 'A',
                        '2' => 'B',
                        '3' => 'C',
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
