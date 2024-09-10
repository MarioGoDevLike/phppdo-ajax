<?php

include 'connect.php';
if (isset($_POST['save_student'])) {
    $name = $_POST['userName'];
    $email = $_POST['userEmail'];
    $phone = $_POST['userPhone'];
    $course = $_POST['userCourse'];

    if (empty($name) || empty($email) || empty($phone) || empty($course)) {
        $res = [
            'status' => 422,
            'message' => 'All fields are mandatory',
        ];
        echo json_encode($res);
        return false;
    } else {
        $sql = "INSERT INTO students(name, email, phone, course)VALUES(:name, :email, :phone, :course)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['name' => $name, 'email' => $email, 'phone' => $phone, 'course' => $course]);
        $res = [
            'status' => 200,
            'message' => 'Student has been added!',
        ];
        echo json_encode($res);
        return false;
    }
}



if (isset($_POST['delete_student'])) {
    $studentId = $_POST['studentId'];
    $sql = 'DELETE FROM students WHERE id = ?';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$studentId]);
    $res = [
        'status' => 200,
        'message' => 'Student has been deleted.',
    ];
    echo json_encode($res);
    return false;
}


$sql = 'SELECT * FROM students';
$stmt = $pdo->prepare($sql);
$stmt->execute([]);
$students = $stmt->fetchAll();

$response = [];

foreach ($students as $student) {
    $response[] = [
        'id' => $student->id,
        'name' => $student->name,
        'email' => $student->email,
        'phone' => $student->phone,
        'course' => $student->course
    ];
}

echo json_encode($response);



if (isset($_GET['student_id'])) {
    
    $sql = 'SELECT * FROM students WHERE id = ?';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$_GET['student_id']]);
    $result = $stmt->fetch();
    

    if($result){
        $resp = [
            'status'=>200,
            'message'=>'Fetched successfully',
            'data'=>$result
        ];
    
    }else{
        $resp = [
            'status'=>400,
            'message'=>'No student found.',
        ];
    }
   
    
    echo json_encode($resp);
    return false;
}
