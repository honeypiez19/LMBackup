<?php
session_start();
date_default_timezone_set('Asia/Bangkok');

include '../connect.php';
if (!isset($_SESSION['s_usercode'])) {
    header('Location: ../login.php');
    exit();
}

$userCode = $_SESSION['s_usercode'];
// echo $userCode;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>หน้าหลัก</title>

    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
    <link rel="icon" href="../logo/logo.png">
    <link rel="stylesheet" href="../css/jquery-ui.css">
    <link rel="stylesheet" href="../css/flatpickr.min.css">

    <script src="../js/jquery-3.7.1.min.js"></script>
    <script src="../js/jquery-ui.min.js"></script>
    <script src="../js/flatpickr"></script>
    <script src="../js/sweetalert2.all.min.js"></script>

    <!-- <script src="https://kit.fontawesome.com/84c1327080.js" crossorigin="anonymous"></script> -->

    <script src="../js/fontawesome.js"></script>
</head>

<body>
    <?php require 'admin_navbar.php'?>

    <div class="container-fluid">
        <form class="mt-3 mb-3 row" method="post">
            <label for="" class="mt-2 col-auto">เลือกปี</label>
            <div class="col-auto">
                <?php
$currentYear = date('Y'); // ปีปัจจุบัน

if (isset($_POST['year'])) {
    $selectedYear = $_POST['year'];
} else {
    $selectedYear = $currentYear;
}

echo "<select class='form-select' name='year' id='selectedYear'>";
for ($i = 0; $i <= 4; $i++) {
    $year = $currentYear - $i;
    echo "<option value='$year'" . ($year == $selectedYear ? " selected" : "") . ">$year</option>";
}
echo "</select>";
?>
            </div>

            <label for="" class="mt-2 col-auto">เลือกเดือน</label>
            <div class="col-auto">
                <?php
$months = [
    '01' => 'มกราคม',
    '02' => 'กุมภาพันธ์',
    '03' => 'มีนาคม',
    '04' => 'เมษายน',
    '05' => 'พฤษภาคม',
    '06' => 'มิถุนายน',
    '07' => 'กรกฎาคม',
    '08' => 'สิงหาคม',
    '09' => 'กันยายน',
    '10' => 'ตุลาคม',
    '11' => 'พฤศจิกายน',
    '12' => 'ธันวาคม',
];

$selectedMonth = date('m'); // เดือนปัจจุบัน

if (isset($_POST['month'])) {
    $selectedMonth = $_POST['month'];
}

echo "<select class='form-select' name='month' id='selectedMonth'>";
foreach ($months as $key => $monthName) {
    echo "<option value='$key'" . ($key == $selectedMonth ? " selected" : "") . ">$monthName</option>";
}
echo "</select>";
?>
            </div>

            <div class="col-auto">
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </div>
        </form>
    </div>

    <div class="container">
        <div class="mt-3 row">
            <div class="col-3 filter-card" data-status="all">
                <div class="card text-bg-primary mb-3">
                    <!-- <div class="card-header">รายการลาทั้งหมด</div> -->
                    <div class="card-body">
                        <h5 class="card-title">
                            <?php
$sql = "SELECT COUNT(l_list_id) AS totalLeaveItems FROM leave_list WHERE Month(l_create_datetime) = '$selectedMonth' 
AND Year(l_create_datetime) = '$selectedYear' 
AND l_leave_id <> 6 AND l_leave_id <> 7";
$totalLeaveItems = $conn->query($sql)->fetchColumn();
?>
                            <div class="d-flex justify-content-between">
                                <?php echo $totalLeaveItems; ?>
                                <i class="mt-4 fas fa-file-alt ml-2 fa-2xl"></i>
                            </div>
                        </h5>
                        <p class="card-text">
                            รายการลาทั้งหมด
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-3 filter-card" data-status="0">
                <div class="card text-bg-warning mb-3">
                    <!-- <div class="card-header">รายการลาทั้งหมด</div> -->
                    <div class="card-body">
                        <h5 class="card-title">
                            <?php
$sql = "SELECT COUNT(l_list_id) AS totalLeaveItems FROM leave_list WHERE l_hr_status = 0 AND Month(l_create_datetime) = '$selectedMonth' 
AND Year(l_create_datetime) = '$selectedYear' 
AND l_leave_id <> 6 AND l_leave_id <> 7";
$totalLeaveItems = $conn->query($sql)->fetchColumn();

?>
                            <div class="d-flex justify-content-between">
                                <?php echo $totalLeaveItems; ?>
                                <i class="mt-4 fa-solid fa-clock-rotate-left fa-2xl" style="color: #ffffff;"></i>
                            </div>
                        </h5>
                        <p class="card-text">
                            รายการลาที่รอตรวจสอบ
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-3 filter-card" data-status="1">
                <div class="card text-bg-success mb-3">
                    <!-- <div class="card-header">รายการลาทั้งหมด</div> -->
                    <div class="card-body">
                        <h5 class="card-title">
                            <?php
$sql = "SELECT COUNT(l_list_id) AS totalLeaveItems FROM leave_list WHERE l_hr_status = 1 AND Month(l_create_datetime) = '$selectedMonth' 
AND Year(l_create_datetime) = '$selectedYear' 
AND l_leave_id <> 6 AND l_leave_id <> 7";
$totalLeaveItems = $conn->query($sql)->fetchColumn();
?>
                            <div class="d-flex justify-content-between">
                                <?php echo $totalLeaveItems; ?>
                                <i class="mt-4 fa-solid fa-thumbs-up fa-2xl"></i>
                            </div>
                        </h5>
                        <p class="card-text">
                            รายการลาที่ตรวจสอบผ่าน
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-3 filter-card" data-status="2">
                <div class="card text-bg-danger mb-3">
                    <!-- <div class="card-header">รายการลาทั้งหมด</div> -->
                    <div class="card-body">
                        <h5 class="card-title">
                            <?php
$sql = "SELECT COUNT(l_list_id) AS totalLeaveItems FROM leave_list WHERE l_hr_status = 2 AND Month(l_create_datetime) = '$selectedMonth' 
AND Year(l_create_datetime) = '$selectedYear' 
AND l_leave_id <> 6 AND l_leave_id <> 7";
$totalLeaveItems = $conn->query($sql)->fetchColumn();
?>
                            <div class="d-flex justify-content-between">
                                <?php echo $totalLeaveItems; ?>
                                <i class="mt-4 fa-solid fa-thumbs-down fa-2xl"></i>
                            </div>
                        </h5>
                        <p class="card-text">
                            รายการลาที่ตรวจสอบไม่ผ่าน
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ตารางข้อมูลการลา -->
    <div class="container-fluid">
        <table class="table table-hover" style="border-top: 1px solid rgba(0, 0, 0, 0.1);" id="leaveTable">
            <thead>
                <tr class="text-center align-middle">
                    <th rowspan="2">ลำดับ</th>
                    <th rowspan="1">รหัสพนักงาน</th>
                    <th rowspan="1">ชื่อ - นามสกุล</th>
                    <th rowspan="2">วันที่ยื่นใบลา</th>
                    <th rowspan="1">รายการลา</th>
                    <th colspan="2" class="text-center">วันเวลาที่ลา</th>
                    <th rowspan="2">ไฟล์แนบ</th>
                    <th rowspan="2">สถานะใบลา</th>
                    <th rowspan="2">สถานะอนุมัติ_1</th>
                    <th rowspan="2">วันเวลาอนุมัติ_1</th>
                    <th rowspan="2">เหตุผล_1</th>
                    <th rowspan="2">หัวหน้า</th>
                    <th rowspan="2">สถานะอนุมัติ_2</th>
                    <th rowspan="2">วันเวลาอนุมัติ_2</th>
                    <th rowspan="2">เหตุผล_2</th>
                    <th rowspan="2">ผู้จัดการขึ้นไป</th>
                    <th rowspan="2">สถานะ (เฉพาะ HR)</th>
                    <th rowspan="2"></th>
                </tr>
                <tr class="text-center">
                    <th><input type="text" class="form-control" id="codeSearch"></th>
                    <th><input type="text" class="form-control" id="nameSearch"></th>
                    <th><input type="text" class="form-control" id="leaveSearch"></th>
                    <!-- <th><input type="text" class="form-control" id="leaveSearch"></th> -->
                    <th>จาก</th>
                    <th>ถึง</th>
                </tr>
            </thead>
            <tbody class="text-center">
                <?php
$itemsPerPage = 10;

// คำนวณหน้าปัจจุบัน
if (!isset($_GET['page'])) {
    $currentPage = 1;
} else {
    $currentPage = $_GET['page'];
}

$sql = "SELECT * FROM leave_list WHERE Month(l_create_datetime) = '$selectedMonth' 
AND Year(l_create_datetime) = '$selectedYear' 
AND l_leave_id <> 6 
AND l_leave_id <> 7 ORDER BY l_create_datetime DESC

";
$result = $conn->query($sql);
$totalRows = $result->rowCount();

// คำนวณหน้าทั้งหมด
$totalPages = ceil($totalRows / $itemsPerPage);

// คำนวณ offset สำหรับ pagination
$offset = ($currentPage - 1) * $itemsPerPage;

// เพิ่ม LIMIT และ OFFSET ในคำสั่ง SQL
$sql .= " LIMIT $itemsPerPage OFFSET $offset";

// ประมวลผลคำสั่ง SQL
$result = $conn->query($sql);

// แสดงผลลำดับของแถว
$rowNumber = $totalRows - ($currentPage - 1) * $itemsPerPage; // กำหนดลำดับของแถว

// แสดงข้อมูลในตาราง
if ($result->rowCount() > 0) {
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        echo '<tr class="align-middle">';

        // 0
        echo '<td hidden>';
        if ($row['l_leave_id'] == 1) {
            echo '<span class="text-primary">' . 'ลากิจได้รับค่าจ้าง' . '</span>';
        } elseif ($row['l_leave_id'] == 2) {
            echo '<span class="text-primary">' . 'ลากิจไม่ได้รับค่าจ้าง' . '</span>';
        } elseif ($row['l_leave_id'] == 3) {
            echo '<span class="text-primary">' . 'ลาป่วย' . '</span>';
        } elseif ($row['l_leave_id'] == 4) {
            echo '<span class="text-primary">' . 'ลาป่วยจากงาน' . '</span>';
        } elseif ($row['l_leave_id'] == 5) {
            echo '<span class="text-primary">' . 'ลาพักร้อน' . '</span>';
        } elseif ($row['l_leave_id'] == 6) {
            echo '<span class="text-primary">' . 'ขาดงาน' . '</span>';
        } elseif ($row['l_leave_id'] == 7) {
            echo '<span class="text-primary">' . 'มาสาย' . '</span>';
        } elseif ($row['l_leave_id'] == 8) {
            echo '<span class="text-primary">' . 'อื่น ๆ' . '</span>';
        } else {
            echo $row['l_leave_id'];
        }
        echo '</td>';

        // 1
        echo '<td hidden>' . $row['l_name'] . '</td>';

        // 2
        echo '<td hidden>' . $row['l_department'] . '</td>';

        // 3
        echo '<td hidden>' . $row['l_leave_reason'] . '</td>';

        // 4
        echo '<td>' . $rowNumber . '</td>';

        // 5
        echo '<td>' . $row['l_usercode'] . '</td>';

        // 6
        echo '<td>' . '<span class="text-primary">' . $row['l_name'] . '</span>' . '<br>' . 'แผนก : ' . $row['l_department'] . '</td>'; // คอลัมน์ 2 ชื่อพนักงาน + แผนก

        // 7
        echo '<td>' . $row['l_create_datetime'] . '</td>';

        // 8
        echo '<td>';
        if ($row['l_leave_id'] == 1) {
            echo '<span class="text-primary">' . 'ลากิจได้รับค่าจ้าง' . '</span>' . '<br>' . 'เหตุผล : ' . $row['l_leave_reason'];
        } elseif ($row['l_leave_id'] == 2) {
            echo '<span class="text-primary">' . 'ลากิจไม่ได้รับค่าจ้าง' . '</span>' . '<br>' . 'เหตุผล : ' . $row['l_leave_reason'];
        } elseif ($row['l_leave_id'] == 3) {
            echo '<span class="text-primary">' . 'ลาป่วย' . '</span>' . '<br>' . 'เหตุผล : ' . $row['l_leave_reason'];
        } elseif ($row['l_leave_id'] == 4) {
            echo '<span class="text-primary">' . 'ลาป่วยจากงาน' . '</span>' . '<br>' . 'เหตุผล : ' . $row['l_leave_reason'];
        } elseif ($row['l_leave_id'] == 5) {
            echo '<span class="text-primary">' . 'ลาพักร้อน' . '</span>' . '<br>' . 'เหตุผล : ' . $row['l_leave_reason'];
        } elseif ($row['l_leave_id'] == 6) {
            echo '<span class="text-primary">' . 'ขาดงาน' . '</span>' . '<br>' . 'เหตุผล : ' . $row['l_leave_reason'];
        } elseif ($row['l_leave_id'] == 7) {
            echo '<span class="text-primary">' . 'มาสาย' . '</span>' . '<br>' . 'เหตุผล : ' . $row['l_leave_reason'];
        } elseif ($row['l_leave_id'] == 8) {
            echo '<span class="text-primary">' . 'อื่น ๆ' . '</span>' . '<br>' . 'เหตุผล : ' . $row['l_leave_reason'];
        } else {
            echo 'ไม่พบประเภทการลาและเหตุผลการลา';
        }
        echo '</td>';

        // 9
        if ($row['l_leave_start_time'] == '12:00:00') {
            echo '<td>' . $row['l_leave_start_date'] . '<br> ' . '11:45:00' . '</td>';
        } else if ($row['l_leave_start_time'] == '13:00:00') {
            echo '<td>' . $row['l_leave_start_date'] . '<br> ' . '12:45:00' . '</td>';
        } else if ($row['l_leave_start_time'] == '17:00:00') {
            echo '<td>' . $row['l_leave_start_date'] . '<br> ' . '16:40:00' . '</td>';
        } else {
            echo '<td>' . $row['l_leave_start_date'] . '<br> ' . $row['l_leave_start_time'] . '</td>';
        }

        // echo '<td>' . $row['l_leave_start_date'] . '<br> ' . $row['l_leave_start_time'] . '</td>';

        // 10
        if ($row['l_leave_end_time'] == '12:00:00') {
            echo '<td>' . $row['l_leave_end_date'] . '<br> ' . '11:45:00' . '</td>';

        } else if ($row['l_leave_end_time'] == '13:00:00') {
            echo '<td>' . $row['l_leave_end_date'] . '<br> ' . '12:45:00' . '</td>';
        } else if ($row['l_leave_end_time'] == '17:00:00') {
            echo '<td>' . $row['l_leave_end_date'] . '<br> ' . '16:40:00' . '</td>';
        } else {
            echo '<td>' . $row['l_leave_end_date'] . '<br> ' . $row['l_leave_end_time'] . '</td>';
        }

        // 11
        echo '</td>';
        if (!empty($row['l_file'])) {
            echo '<td><button id="imgBtn" class="btn btn-primary" onclick="window.open(\'../upload/' . $row['l_file'] . '\', \'_blank\')"><i class="fa-solid fa-file"></i></button></td>';
        } else {
            echo '<td><button id="imgNoBtn" class="btn btn-primary" disabled><i class="fa-solid fa-file-excel"></i></button></td>';
        }
        echo '</td>';

        // 12
        echo '<td>';
        if ($row['l_leave_status'] == 0) {
            echo '<span class="text-success">ปกติ</span>';
        } else {
            echo '<span class="text-danger">ยกเลิกใบลา</span>';
        }
        echo '</td>';

        // 13
        echo '<td>';
        // รอหัวหน้าอนุมัติ
        if ($row['l_approve_status'] == 0) {
            echo '<div class="text-warning"><b>รอหัวหน้าอนุมัติ</b></div>';
        }
        // รอผจกอนุมัติ
        elseif ($row['l_approve_status'] == 1) {
            echo '<div class="text-warning"><b>รอผู้จัดการอนุมัติ</b></div>';
        }
        // หัวหน้าอนุมัติ
        elseif ($row['l_approve_status'] == 2) {
            echo '<div class="text-success"><b>หัวหน้าอนุมัติ</b></div>';
        }
        // หัวหน้าไม่อนุมัติ
        elseif ($row['l_approve_status'] == 3) {
            echo '<div class="text-danger"><b>หัวหน้าไม่อนุมัติ</b></div>';
        }
        //  ผจก อนุมัติ
        elseif ($row['l_approve_status'] == 4) {
            echo '<div class="text-success"><b>ผู้จัดการอนุมัติ</b></div>';
        }
        //  ผจก ไม่อนุมัติ
        elseif ($row['l_approve_status'] == 5) {
            echo '<div class="text-danger"><b>ผู้จัดการไม่อนุมัติ</b></div>';
        }
        // อื่น ๆ
        elseif ($row['l_approve_status'] == 6) {
            echo '';
        }
        // ไม่มีสถานะ
        else {
            echo 'ไม่พบสถานะ';
        }
        echo '</td>';

        // 14
        echo '<td>' . $row['l_approve_datetime'] . '</td>';

        // 15
        echo '<td>' . $row['l_reason'] . '</td>';

        // 16
        echo '<td>' . $row['l_approve_name'] . '</td>';

        // 17
        echo '<td>';
        // รอหัวหน้าอนุมัติ
        if ($row['l_approve_status2'] == 0) {
            echo '<div class="text-warning"><b>รอหัวหน้าอนุมัติ</b></div>';
        }
        // รอผจกอนุมัติ
        elseif ($row['l_approve_status2'] == 1) {
            echo '<div class="text-warning"><b>รอผู้จัดการอนุมัติ</b></div>';
        }
        // หัวหน้าอนุมัติ
        elseif ($row['l_approve_status2'] == 2) {
            echo '<div class="text-success"><b>หัวหน้าอนุมัติ</b></div>';
        }
        // หัวหน้าไม่อนุมัติ
        elseif ($row['l_approve_status2'] == 3) {
            echo '<div class="text-danger"><b>หัวหน้าไม่อนุมัติ</b></div>';
        }
        //  ผจก อนุมัติ
        elseif ($row['l_approve_status2'] == 4) {
            echo '<div class="text-success"><b>ผู้จัดการอนุมัติ</b></div>';
        }
        //  ผจก ไม่อนุมัติ
        elseif ($row['l_approve_status2'] == 5) {
            echo '<div class="text-danger"><b>ผู้จัดการไม่อนุมัติ</b></div>';
        } elseif ($row['l_approve_status2'] == 6) {
            echo '';
        }
        // ไม่มีสถานะ
        else {
            echo 'ไม่พบสถานะ';
        }
        echo '</td>';

        // 18
        echo '<td>' . $row['l_approve_datetime2'] . '</td>';

        // 19
        echo '<td>' . $row['l_reason2'] . '</td>';

        // 20
        echo '<td>' . $row['l_approve_name2'] . '</td>';

        // 21
        echo '<td >';
        if ($row['l_hr_status'] == 0) {
            echo '<div class="text-warning"><b>รอตรวจสอบ</b></div>';
        } elseif ($row['l_hr_status'] == 1) {
            echo '<div class="text-success"><b>ผ่าน</b></div>';
        } elseif ($row['l_hr_status'] == 2) {
            echo '<div class="text-danger"><b>ไม่ผ่าน</b></div>';
        } else {
            echo $row['l_hr_status'];
        }
        echo '</td>';

        // 22 ปุ่มตรวจสอบ
        if ($row['l_approve_status'] == 2 || $row['l_approve_status'] == 3) {
            echo "<td><button type='button' class='btn btn-primary leaveChk' data-bs-toggle='modal' data-bs-target='#leaveModal'>ตรวจสอบ</button></td>";
        } else {
            echo "<td><button type='button' class='btn btn-primary leaveChk' data-bs-toggle='modal' data-bs-target='#leaveModal'>ตรวจสอบ</button></td>";
        }
        echo '</tr>';
        $rowNumber--;
    }
} else {
    echo '<tr><td colspan="18" style="text-align: left; color:red;">ไม่พบข้อมูล</td></tr>';
}
?>
            </tbody>
        </table>
        <?php
echo '<div class="pagination">';
echo '<ul class="pagination">';

// สร้างลิงก์ไปยังหน้าแรกหรือหน้าก่อนหน้า
if ($currentPage > 1) {
    echo '<li class="page-item"><a class="page-link" href="?page=1">&laquo;</a></li>';
    echo '<li class="page-item"><a class="page-link" href="?page=' . ($currentPage - 1) . '">&lt;</a></li>';
}

// สร้างลิงก์สำหรับแต่ละหน้า
for ($i = 1; $i <= $totalPages; $i++) {
    if ($i == $currentPage) {
        echo '<li class="page-item active"><span class="page-link">' . $i . '</span></li>';
    } else {
        echo '<li class="page-item"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
    }
}

// สร้างลิงก์ไปยังหน้าถัดไปหรือหน้าสุดท้าย
if ($currentPage < $totalPages) {
    echo '<li class="page-item"><a class="page-link" href="?page=' . ($currentPage + 1) . '">&gt;</a></li>';
    echo '<li class="page-item"><a class="page-link" href="?page=' . $totalPages . '">&raquo;</a></li>';
}

echo '</ul>';
echo '</div>';

?>
        <!-- Modal เช็คการลา -->
        <div class="modal fade" id="leaveModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title
                        01f s-5" id="staticBackdropLabel">ข้อมูลการลา</h4>
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">X</button>
                    </div>
                    <div class="modal-body">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger">ไม่ผ่าน</button>
                        <button type="button" class="btn btn-success">ผ่าน</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
    $(".leaveChk").click(function() {
        var rowData = $(this).closest("tr").find("td");

        $('#leaveModal .modal-body').html(
            '<table class="table table-bordered">' +
            '<tr>' +
            '<th>รหัสพนักงาน</th>' +
            '<td>' + $(rowData[5]).text() + '</td>' +
            '</tr>' +
            '<tr>' +
            '<th>ชื่อ - นามสกุล</th>' +
            '<td>' + $(rowData[1]).text() + '</td>' +
            '</tr>' +
            '<tr>' +
            '<th>แผนก</th>' +
            '<td>' + $(rowData[2]).text() + '</td>' +
            '</tr>' +
            '<tr>' +
            '<th>วันที่ยื่นใบลา</th>' +
            '<td>' + $(rowData[7]).text() + '</td>' +
            '</tr>' +
            '<tr>' +
            '<th>ประเภทการลา</th>' +
            '<td>' + $(rowData[0]).text() + '</td>' +
            '</tr>' +
            '<tr>' +
            '<th>เหตุผลการลา</th>' +
            '<td>' + $(rowData[3]).text() + '</td>' +
            '</tr>' +
            '<tr>' +
            '<th>วันเวลาที่ลา</th>' +
            '<td>' + $(rowData[9]).text() + ' ถึง ' + $(rowData[10]).text() + '</td>' +
            '</tr>' +
            '<tr>' +
            '<th>สถานะใบลา</th>' +
            '<td>' + $(rowData[12]).html() + '</td>' +
            '</tr>' +
            '</table>'
        );

        $('.modal-footer .btn-success').off('click').on('click', function() {
            var userCode = $(rowData[5]).text(); // รหัสพนักงาน
            var createDate = $(rowData[7]).text(); // วันที่ยื่นใบลา
            var leaveType = $(rowData[0]).text(); // ประเภทการลา
            var empName = $(rowData[1]).text(); // ชื่อพนักงาน
            var depart = $(rowData[2]).text(); // แผนก
            var leaveReason = $(rowData[3]).text(); // เหตุผลการลา
            var leaveStartDate = $(rowData[9]).text(); // วันเวลาที่ลาเริ่มต้น
            var leaveEndDate = $(rowData[10]).text(); // วันเวลาที่ลาสิ้นสุด
            var leaveStatus = $(rowData[12]).text(); // สถานะใบลา
            var checkFirm = '1'; // ผ่าน

            var userName = '<?php echo $userName; ?>';

            // alert(userCode)
            $.ajax({
                url: 'a_ajax_upd_status.php',
                method: 'POST',
                data: {
                    createDate: createDate,
                    userCode: userCode,
                    userName: userName,
                    leaveType: leaveType,
                    leaveReason: leaveReason,
                    leaveStartDate: leaveStartDate,
                    leaveEndDate: leaveEndDate,
                    depart: depart,
                    checkFirm: checkFirm,
                    empName: empName,
                    leaveStatus: leaveStatus

                },
                success: function(response) {
                    $('#leaveModal').modal('hide');
                    location.reload(); // Reload the page after successful update
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        });
        $('.modal-footer .btn-danger').off('click').on('click', function() {
            var userCode = $(rowData[5]).text(); // รหัสพนักงาน
            var createDate = $(rowData[7]).text(); // วันที่ยื่นใบลา
            var leaveType = $(rowData[0]).text(); // ประเภทการลา
            var empName = $(rowData[1]).text(); // ชื่อพนักงาน
            var depart = $(rowData[2]).text(); // แผนก
            var leaveReason = $(rowData[3]).text(); // เหตุผลการลา
            var leaveStartDate = $(rowData[9]).text(); // วันเวลาที่ลาเริ่มต้น
            var leaveEndDate = $(rowData[10]).text(); // วันเวลาที่ลาสิ้นสุด
            var leaveStatus = $(rowData[12]).text(); // สถานะใบลา

            var checkFirm = '2'; // ไม่ผ่าน
            var userName = '<?php echo $userName; ?>';

            $.ajax({
                url: 'a_ajax_upd_status.php',
                method: 'POST',
                data: {
                    createDate: createDate,
                    userCode: userCode,
                    userName: userName,
                    leaveType: leaveType,
                    leaveReason: leaveReason,
                    leaveStartDate: leaveStartDate,
                    leaveEndDate: leaveEndDate,
                    depart: depart,
                    checkFirm: checkFirm,
                    empName: empName,
                    leaveStatus: leaveStatus

                },
                success: function(response) {
                    $('#leaveModal').modal('hide');
                    location.reload();
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        });
    });

    $(".filter-card").click(function() {
        var status = $(this).data("status");
        var selectedMonth = $("#selectedMonth").val();

        $.ajax({
            url: 'a_ajax_get_leave_data.php',
            method: 'GET',
            data: {
                status: status,
                month: selectedMonth
            },
            dataType: 'json',
            success: function(data) {
                $("tbody").empty();
                if (data.length === 0) {
                    $("tbody").append(
                        '<tr><td colspan="19" class="text-danger" style="text-align: left;">ไม่พบข้อมูล</td></tr>'
                    );
                } else {
                    $.each(data, function(index, row) {
                        var leaveType = '';
                        if (row['l_leave_id'] == 1) {
                            leaveType = 'ลากิจได้รับค่าจ้าง';
                        } else if (row['l_leave_id'] == 2) {
                            leaveType = 'ลากิจไม่ได้รับค่าจ้าง';
                        } else if (row['l_leave_id'] == 3) {
                            leaveType = 'ลาป่วย';
                        } else if (row['l_leave_id'] == 4) {
                            leaveType = 'ลาป่วยจากงาน';
                        } else if (row['l_leave_id'] == 5) {
                            leaveType = 'ลาพักร้อน';
                        } else if (row['l_leave_id'] == 6) {
                            leaveType = 'ขาดงาน';
                        } else if (row['l_leave_id'] == 7) {
                            leaveType = 'มาสาย';
                        } else if (row['l_leave_id'] == 8) {
                            leaveType = 'อื่น ๆ';
                        } else {
                            leaveType = row['l_leave_id'];
                        }

                        // สถานะใบลา
                        var leaveStatus = '';
                        if (row['l_leave_status'] == 0) {
                            leaveStatus = '<div class="text-success">ปกติ</div>';
                        } else if (row['l_leave_status'] == 1) {
                            leaveStatus = '<div class="text-danger">ยกเลิกใบลา</div>';
                        } else {
                            leaveStatus = 'ไม่พบสถานะใบลา';
                        }

                        var confirmStatus = '';
                        if (row['l_hr_status'] == 0) {
                            confirmStatus =
                                '<div class="text-warning"><b>รอตรวจสอบ</b></div>';
                        } else if (row['l_hr_status'] == 1) {
                            confirmStatus = '<div class="text-success"><b>ผ่าน</b></div>';
                        } else if (row['l_hr_status'] == 2) {
                            confirmStatus = '<div class="text-danger"><b>ไม่ผ่าน</b></div>';
                        } else {
                            confirmStatus = row['l_hr_status'];
                        }

                        var approveStatus;
                        if (row['l_approve_status'] == 0) {
                            approveStatus =
                                '<div class="text-warning"><b>รอหัวหน้าอนุมัติ</b></div>';
                        } else if (row['l_approve_status'] == 1) {
                            approveStatus =
                                '<div class="text-warning"><b>รอผู้จัดการอนุมัติ</b></div>';
                        } else if (row['l_approve_status'] == 2) {
                            approveStatus =
                                '<div class="text-success"><b>หัวหน้าอนุมัติ</b></div>';
                        } else if (row['l_approve_status'] == 3) {
                            approveStatus =
                                '<div class="text-danger"><b>หัวหน้าไม่อนุมัติ</b></div>';
                        } else if (row['l_approve_status'] == 4) {
                            approveStatus =
                                '<div class="text-success"><b>ผู้จัดการอนุมัติ</b></div>';
                        } else if (row['l_approve_status'] == 5) {
                            approveStatus =
                                '<div class="text-danger"><b>ผู้จัดการไม่อนุมัติ</b></div>';
                        } else if (row['l_approve_status'] == 6) {
                            approveStatus =
                                '';
                        } else {
                            approveStatus = 'ไม่พบสถานะ';
                        }

                        var approveReason = row['l_reason'] !== null ? row['l_reason'] : '';

                        var approveStatus2;
                        if (row['l_approve_status2'] == 0) {
                            approveStatus2 =
                                '<div class="text-warning"><b>รอหัวหน้าอนุมัติ</b></div>';
                        } else if (row['l_approve_status2'] == 1) {
                            approveStatus2 =
                                '<div class="text-warning"><b>รอผู้จัดการอนุมัติ</b></div>';
                        } else if (row['l_approve_status2'] == 2) {
                            approveStatus2 =
                                '<div class="text-success"><b>หัวหน้าอนุมัติ</b></div>';
                        } else if (row['l_approve_status2'] == 3) {
                            approveStatus2 =
                                '<div class="text-danger"><b>หัวหน้าไม่อนุมัติ</b></div>';
                        } else if (row['l_approve_status2'] == 4) {
                            approveStatus2 =
                                '<div class="text-success"><b>ผู้จัดการอนุมัติ</b></div>';
                        } else if (row['l_approve_status2'] == 5) {
                            approveStatus2 =
                                '<div class="text-danger"><b>ผู้จัดการไม่อนุมัติ</b></div>';
                        } else if (row['l_approve_status'] == 6) {
                            approveStatus2 =
                                '';
                        } else {
                            approveStatus2 = 'ไม่พบสถานะ';
                        }

                        // เวลาเริ่มต้น
                        var startTime;
                        if (row['l_leave_start_time'] == '12:00:00') {
                            startTime = '11:45:00';
                        } else if (row['l_leave_start_time'] == '13:00:00') {
                            startTime = '12:45:00';
                        } else if (row['l_leave_start_time'] == '17:00:00') {
                            startTime = '16:40:00';
                        } else {
                            startTime = row['l_leave_start_time'];
                        }

                        // เวลาสิ้นสุด
                        var endTime;
                        if (row['l_leave_end_time'] == '12:00:00') {
                            endTime = '11:45:00';
                        } else if (row['l_leave_end_time'] == '13:00:00') {
                            endTime = '12:45:00';
                        } else if (row['l_leave_end_time'] == '17:00:00') {
                            endTime = '16:40:00';
                        } else {
                            endTime = row['l_leave_end_time'];
                        }

                        var newRow = '<tr class="align-middle">' +
                            // 0
                            '<td hidden>' +
                            (row['l_leave_id'] == 1 ?
                                '<span class="text-primary">ลากิจได้รับค่าจ้าง</span>' :
                                '') +
                            (row['l_leave_id'] == 2 ?
                                '<span class="text-primary">ลากิจไม่ได้รับค่าจ้าง</span>' :
                                '') +
                            (row['l_leave_id'] == 3 ?
                                '<span class="text-primary">ลาป่วย</span>' :
                                '') +
                            (row['l_leave_id'] == 4 ?
                                '<span class="text-primary">ลาป่วยจากงาน</span>' :
                                '') +
                            (row['l_leave_id'] == 5 ?
                                '<span class="text-primary">ลาพักร้อน</span>' :
                                '') +
                            (row['l_leave_id'] == 6 ?
                                '<span class="text-primary">ขาดงาน</span>' :
                                '') +
                            (row['l_leave_id'] == 7 ?
                                '<span class="text-primary">มาสาย</span>' :
                                '') +
                            (row['l_leave_id'] == 8 ?
                                '<span class="text-primary">อื่น ๆ</span>' :
                                '') +
                            '</td>' +

                            // 1
                            '<td hidden>' + (row['l_name'] ? row['l_name'] : '') +
                            '</td>' +

                            // 2
                            '<td hidden>' + (row['l_department'] ? row['l_department'] :
                                '') +
                            '</td>' +

                            // 3
                            '<td hidden>' + (row['l_leave_reason'] ? row['l_leave_reason'] :
                                '') +
                            '</td>' +

                            // 4
                            '<td>' + (index + 1) + '</td>' +

                            // 5
                            '<td>' + (row['l_usercode'] ? row['l_usercode'] : '') +
                            '</td>' +

                            // 6
                            '<td>' + '<span class="text-primary">' + (row['l_name'] ? row[
                                'l_name'] : '') + '</span><br>' +
                            'แผนก : ' + (row['l_department'] ? row['l_department'] : '') +
                            '</td>' +

                            // 7
                            '<td>' + (row['l_create_datetime'] ? row['l_create_datetime'] :
                                '') + '</td>' + // Creation Date Time
                            '<td>';

                        // 8
                        if (row['l_leave_id'] == 1) {
                            newRow +=
                                '<span class="text-primary">ลากิจได้รับค่าจ้าง</span><br>เหตุผล : ' +
                                row['l_leave_reason'];
                        } else if (row['l_leave_id'] == 2) {
                            newRow +=
                                '<span class="text-primary">ลากิจไม่ได้รับค่าจ้าง</span><br>เหตุผล : ' +
                                row['l_leave_reason'];
                        } else if (row['l_leave_id'] == 3) {
                            newRow +=
                                '<span class="text-primary">ลาป่วย</span><br>เหตุผล : ' +
                                row['l_leave_reason'];
                        } else if (row['l_leave_id'] == 4) {
                            newRow +=
                                '<span class="text-primary">ลาป่วยจากงาน</span><br>เหตุผล : ' +
                                row['l_leave_reason'];
                        } else if (row['l_leave_id'] == 5) {
                            newRow +=
                                '<span class="text-primary">ลาพักร้อน</span><br>เหตุผล : ' +
                                row['l_leave_reason'];
                        } else if (row['l_leave_id'] == 6) {
                            newRow +=
                                '<span class="text-primary">ขาดงาน</span><br>เหตุผล : ' +
                                row['l_leave_reason'];
                        } else if (row['l_leave_id'] == 7) {
                            newRow +=
                                '<span class="text-primary">มาสาย</span><br>เหตุผล : ' +
                                row['l_leave_reason'];
                        } else if (row['l_leave_id'] == 8) {
                            newRow +=
                                '<span class="text-primary">อื่น ๆ</span><br>เหตุผล : ' +
                                row['l_leave_reason'];
                        } else {
                            newRow +=
                                '<span class="text-danger">ไม่พบประเภทการลาและเหตุผลการลา</span>';
                        }
                        newRow += '</td>' +

                            // 9
                            '<td>' + (row['l_leave_start_date'] ? row[
                                'l_leave_start_date'] : '') + '<br>' +
                            ' ' + (startTime ? startTime : '') +
                            '</td>' +

                            // 10
                            '<td>' + (row['l_leave_end_date'] ? row['l_leave_end_date'] :
                                '') + '<br>' +
                            ' ' + (endTime ? endTime : '') +
                            '</td>';
                        // 11
                        if (row['l_file']) {
                            newRow +=
                                '<td><button id="imgBtn" class="btn btn-primary" onclick="window.open(\'../upload/' +
                                row['l_file'] +
                                '\', \'_blank\')"><i class="fa-solid fa-file"></i></button></td>';
                        } else {
                            newRow +=
                                '<td><button id="imgNoBtn" class="btn btn-primary" disabled><i class="fa-solid fa-file-excel"></i></button></td>';
                        }
                        newRow +=
                            // 12
                            '<td>' + leaveStatus + '</td>' +

                            // 13
                            '<td>' + approveStatus + '</td>' +

                            // 14
                            '<td>' + (row['l_approve_datetime'] !== null ? row[
                                'l_approve_datetime'] : '') + '</td>' +

                            // 15
                            '<td>' + (row['l_reason'] ? row['l_reason'] : '') + '</td>' +

                            // 16
                            '<td>' + (row['l_approve_name'] ? row['l_approve_name'] : '') +
                            '</td>' +

                            // 17
                            '<td>' + approveStatus2 + '</td>' +

                            // 18
                            '<td>' + (row['l_approve_datetime2'] !== null ? row[
                                'l_approve_datetime2'] : '') + '</td>' +

                            // 19
                            '<td>' + (row['l_reason2'] ? row['l_reason2'] : '') + '</td>' +

                            // 20
                            '<td>' + (row['l_approve_name2'] ? row['l_approve_name2'] :
                                '') + '</td>' +

                            // 21
                            '<td>' + confirmStatus + '</td>' +

                            // 22
                            '<td>';
                        if (row['l_approve_status'] == 1 || row['l_approve_status'] == 3) {

                            newRow +=
                                // '<button type="button" class="btn btn-primary leaveChk" data-bs-toggle="modal" data-bs-target="#leaveModal" disabled>ตรวจสอบ</button>';
                                '<button type="button" class="btn btn-primary leaveChk" data-bs-toggle="modal" data-bs-target="#leaveModal" >ตรวจสอบ</button>';
                        } else {
                            newRow +=
                                '<button type="button" class="btn btn-primary leaveChk" data-bs-toggle="modal" data-bs-target="#leaveModal">ตรวจสอบ</button>';
                        }

                        newRow += '</td></tr>';

                        $("tbody").append(newRow);
                    });
                    $(".leaveChk").click(function() {
                        var rowData = $(this).closest("tr").find("td");

                        // console.log($(rowData[0]).text());
                        // console.log($(rowData[1]).text());
                        // console.log($(rowData[2]).text());
                        // console.log($(rowData[3]).text());
                        // console.log($(rowData[4]).text());
                        // console.log($(rowData[5]).text());
                        // console.log($(rowData[6]).text());
                        // console.log($(rowData[7]).text());
                        // console.log($(rowData[8]).text());
                        // console.log($(rowData[9]).text());
                        // console.log($(rowData[10]).text());
                        // console.log($(rowData[11]).text());
                        // console.log($(rowData[12]).text());

                        // Populate modal content
                        $('#leaveModal .modal-body').html(
                            '<table class="table table-bordered">' +
                            '<tr>' +
                            '<th>รหัสพนักงาน</th>' +
                            '<td>' + $(rowData[5]).text() + '</td>' +
                            '</tr>' +
                            '<tr>' +
                            '<th>ชื่อ - นามสกุล</th>' +
                            '<td>' + $(rowData[1]).text() + '</td>' +
                            '</tr>' +
                            '<tr>' +
                            '<th>แผนก</th>' +
                            '<td>' + $(rowData[2]).text() + '</td>' +
                            '</tr>' +
                            '<tr>' +
                            '<th>วันที่ยื่นใบลา</th>' +
                            '<td>' + $(rowData[7]).text() + '</td>' +
                            '</tr>' +
                            '<tr>' +
                            '<th>ประเภทการลา</th>' +
                            '<td>' + $(rowData[0]).text() + '</td>' +
                            '</tr>' +
                            '<tr>' +
                            '<th>เหตุผลการลา</th>' +
                            '<td>' + $(rowData[3]).text() + '</td>' +
                            '</tr>' +
                            '<tr>' +
                            '<th>วันเวลาที่ลา</th>' +
                            '<td>' + $(rowData[9]).text() + ' ถึง ' + $(rowData[10])
                            .text() + '</td>' +
                            '</tr>' +
                            '<tr>' +
                            '<th>สถานะใบลา</th>' +
                            '<td>' + $(rowData[12]).html() + '</td>' +
                            '</tr>' +
                            '</table>'
                        );

                        $('#leaveModal').modal('show');
                        $('.modal-footer .btn-success').off('click').on('click',
                            function() {
                                var leaveType = $(rowData[0]).text(); // ประเภทการลา
                                var empName = $(rowData[1]).text(); // ชื่อพนักงาน
                                var depart = $(rowData[2]).text(); // แผนก
                                var leaveReason = $(rowData[3]).text(); // เหตุผลการลา
                                var userCode = $(rowData[5]).text(); // รหัสพนักงาน
                                var createDate = $(rowData[7]).text(); // วันที่ยื่นใบลา
                                var leaveStartDate = $(rowData[9])
                                    .text(); // วันเวลาที่ลาเริ่มต้น
                                var leaveEndDate = $(rowData[10])
                                    .text(); // วันเวลาที่ลาสิ้นสุด
                                var leaveStatus = $(rowData[12]).text(); // สถานะใบลา

                                var checkFirm = '1'; // ผ่าน
                                var userName = '<?php echo $userName; ?>';

                                $.ajax({
                                    url: 'a_ajax_upd_status.php',
                                    method: 'POST',
                                    data: {
                                        leaveType: leaveType,
                                        empName: empName,
                                        depart: depart,
                                        leaveReason: leaveReason,
                                        userCode: userCode,
                                        createDate: createDate,
                                        leaveStartDate: leaveStartDate,
                                        leaveEndDate: leaveEndDate,
                                        checkFirm: checkFirm,
                                        userName: userName,
                                        empName: empName,
                                        leaveStatus: leaveStatus
                                        // name: name,
                                        // createDate: createDate,
                                        // userCode: userCode,
                                        // userName: userName,
                                        // leaveType: leaveType,
                                        // leaveReason: leaveReason,
                                        // leaveStartDate: leaveStartDate,
                                        // leaveEndDate: leaveEndDate,
                                        // depart: depart,
                                        // checkFirm: checkFirm
                                    },
                                    success: function(response) {
                                        $('#leaveModal').modal('hide');
                                        location
                                            .reload();
                                    },
                                    error: function(xhr, status, error) {
                                        console.error(error);
                                    }
                                });
                            });
                        $('.modal-footer .btn-danger').off('click').on('click', function() {
                            var leaveType = $(rowData[0]).text(); // ประเภทการลา
                            var empName = $(rowData[1]).text(); // ชื่อพนักงาน
                            var depart = $(rowData[2]).text(); // แผนก
                            var leaveReason = $(rowData[3]).text(); // เหตุผลการลา
                            var userCode = $(rowData[5]).text(); // รหัสพนักงาน
                            var createDate = $(rowData[7]).text(); // วันที่ยื่นใบลา
                            var leaveStartDate = $(rowData[9])
                                .text(); // วันเวลาที่ลาเริ่มต้น
                            var leaveEndDate = $(rowData[10])
                                .text(); // วันเวลาที่ลาสิ้นสุด
                            var leaveStatus = $(rowData[12]).text(); // สถานะใบลา

                            var checkFirm = '2'; // ไม่ผ่าน
                            var userName = '<?php echo $userName; ?>';

                            console.log(leaveType)
                            console.log(name)
                            console.log(depart)
                            console.log(leaveReason)
                            console.log(userCode)
                            console.log(createDate)
                            console.log(leaveStartDate)
                            console.log(leaveEndDate)
                            console.log(checkFirm)
                            console.log(userName)

                            $.ajax({
                                url: 'a_ajax_upd_status.php',
                                method: 'POST',
                                data: {
                                    leaveType: leaveType,
                                    empName: empName,
                                    depart: depart,
                                    leaveReason: leaveReason,
                                    userCode: userCode,
                                    createDate: createDate,
                                    leaveStartDate: leaveStartDate,
                                    leaveEndDate: leaveEndDate,
                                    checkFirm: checkFirm,
                                    userName: userName,
                                    empName: empName,
                                    leaveStatus: leaveStatus
                                },
                                success: function(response) {
                                    $('#leaveModal').modal('hide');
                                    location.reload();
                                },
                                error: function(xhr, status, error) {
                                    console.error(error);
                                }
                            });
                        });
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching data:', error);
            }
        });
    });

    $("#nameSearch").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("tbody tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });

    $("#leaveSearch").on("keyup", function() {
        var value2 = $(this).val().toLowerCase();
        $("tbody tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value2) > -1);
        });
    });

    $("#codeSearch").on("keyup", function() {
        var value3 = $(this).val().toLowerCase();
        $("tbody tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value3) > -1);
        });
    });
    </script>
    <script src="../js/popper.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/bootstrap.bundle.js"></script>
    <script src="../js/bootstrap.bundle.min.js"></script>
</body>

</html>