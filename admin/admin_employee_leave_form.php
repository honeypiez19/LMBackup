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
    <title>ใบลาย้อนหลังของพนักงาน</title>

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
    <nav class="navbar bg-body-tertiary" style="background-color: #072ac8; box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3);
  border: none;">
        <div class=" container-fluid">
            <div class="row align-items-center">
                <div class="col-auto">
                    <i class="fa-solid fa-paste fa-2xl"></i>
                </div>
                <div class="col-auto">
                    <h3>ใบลาย้อนหลังของพนักงาน</h3>
                </div>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <div class="d-flex justify-content-between align-items-center">
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


                <!-- ปุ่มระเบียบการลา -->
                <button type="button" class="button-shadow btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#leaveModal" style="width: 150px;">
                    ยื่นใบลาย้อนหลัง
                </button>
            </div>
        </div>


        <!-- Modal ยื่นใบลา -->
        <div class="modal fade" id="leaveModal" tabindex="-1" aria-labelledby="leaveModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="leaveModalLabel">รายละเอียดการลา</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="leaveForm" method="POST" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-6">
                                    <label for="userCode" class="form-label">รหัสพนักงาน</label>
                                    <input type="text" class="form-control" id="userCode" name="userCode"
                                        list="codeList" required>
                                    <datalist id="codeList">
                                        <?php
    $sql = "SELECT * FROM employees WHERE e_level <> 'admin' AND e_status <> 1";
    $result = $conn->query($sql);
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        echo '<option value="' . $row['e_usercode'] . 
        '" data-name="' . $row['e_name'] . 
        '" data-username="' . $row['e_username'] . 
        '" data-depart="' . $row['e_department'] . 
        '" data-level="' . $row['e_level'] . 
        '" data-telPhone="' . $row['e_phone'] . 
        '" data-workplace="' . $row['e_workplace'] . 
        '" data-subDepart="' . $row['e_sub_department'] . 
        '" data-subDepart2="' . $row['e_sub_department2'] . 
        '" data-subDepart3="' . $row['e_sub_department3'] . 
        '" data-subDepart4="' . $row['e_sub_department4'] . 
        '" data-subDepart5="' . $row['e_sub_department5'] . '"
         > ' . $row['e_name'] .'</option>';
    }
    ?>
                                    </datalist>
                                    <input type="text" class="form-control" id="userName" name="userName">
                                    <input type="text" class="form-control" id="depart" name="depart">
                                    <input type="text" class="form-control" id="level" name="level">
                                    <input type="text" class="form-control" id="workplace" name="workplace">
                                    <input type="text" class="form-control" id="subDepart" name="subDepart">
                                    <input type="text" class="form-control" id="subDepart2" name="subDepart2">
                                    <input type="text" class="form-control" id="subDepart3" name="subDepart3">
                                    <input type="text" class="form-control" id="subDepart4" name="subDepart4">
                                    <input type="text" class="form-control" id="subDepart5" name="subDepart5">

                                </div>
                                <div class="col-6">
                                    <label for="name" class="form-label">ชื่อพนักงาน</label>
                                    <input type="text" class="form-control" id="name" name="name" disabled>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-24 alert alert-danger d-none" role="alert" name="alertCheckDays">
                                    ไม่สามารถลาได้ คุณได้ใช้สิทธิ์ครบกำหนดแล้ว
                                </div>
                                <div class="mt-3 col-12">
                                    <label for="leaveType" class="form-label">ประเภทการลา</label>
                                    <span class="badge rounded-pill text-bg-info" name="totalDays">เหลือ -
                                        วัน</span>
                                    <span style="color: red;">*</span>
                                    <select class="form-select" id="leaveType" required>
                                        <option selected>เลือกประเภทการลา</option>
                                        <option value=" 1">ลากิจได้รับค่าจ้าง</option>
                                        <option value="2">ลากิจไม่ได้รับค่าจ้าง</option>
                                        <option value="3">ลาป่วย</option>
                                        <option value="4">ลาป่วยจากงาน</option>
                                        <option value="5">ลาพักร้อน</option>
                                        <option value="8">อื่น ๆ</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mt-3 row">
                                <div class="col-12">
                                    <label for="leaveReason" class="form-label">เหตุผลการลา</label>
                                    <span style="color: red;">*</span>
                                    <textarea class="form-control mt-2" id="leaveReason" rows="3"
                                        placeholder="กรุณาระบุเหตุผล"></textarea>
                                </div>
                            </div>
                            <div class="mt-3 row">
                                <div class="col-6">
                                    <label for="startDate" class="form-label">วันที่เริ่มต้น</label>
                                    <span style="color: red;">*</span>
                                    <input type="text" class="form-control" id="startDate" required>
                                </div>
                                <div class=" col-6">
                                    <label for="startTime" class="form-label">เวลาที่เริ่มต้น</label>
                                    <span style="color: red;">*</span>
                                    <select class="form-select" id="startTime" name="startTime" required>
                                        <option value="08:00" selected>08:00</option>
                                        <option value="08:30">08:30</option>
                                        <option value="09:00">09:00</option>
                                        <option value="09:30">09:30</option>
                                        <option value="10:00">10:00</option>
                                        <option value="10:30">10:30</option>
                                        <option value="11:00">11:00</option>
                                        <!-- <option value="11:30">11:30</option> -->
                                        <option value="12:00">11:45</option>
                                        <option value="13:00">12:45</option>
                                        <!-- <option value="13:00">13:00</option> -->
                                        <option value="13:30">13:30</option>
                                        <option value="14:00">14:00</option>
                                        <option value="14:30">14:30</option>
                                        <option value="15:00">15:00</option>
                                        <option value="15:30">15:30</option>
                                        <option value="16:00">16:00</option>
                                        <!-- <option value="16:30">16:30</option> -->
                                        <option value="17:00">16:40</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mt-3 row">
                                <div class="col-6">
                                    <label for="endDate" class="form-label">วันที่สิ้นสุด</label>
                                    <span style="color: red;">*</span>
                                    <input type="text" class="form-control" id="endDate" required>
                                </div>
                                <div class="col-6">
                                    <label for="endTime" class="form-label">เวลาที่สิ้นสุด</label>
                                    <span style="color: red;">*</span>
                                    <select class="form-select" id="endTime" name="endTime" required>
                                        <option value="08:00">08:00</option>
                                        <option value="08:30">08:30</option>
                                        <option value="09:00">09:00</option>
                                        <option value="09:30">09:30</option>
                                        <option value="10:00">10:00</option>
                                        <option value="10:30">10:30</option>
                                        <option value="11:00">11:00</option>
                                        <!-- <option value="11:30">11:30</option> -->
                                        <option value="12:00">11:45</option>
                                        <option value="13:00">12:45</option>
                                        <!-- <option value="13:00">13:00</option> -->
                                        <option value="13:30">13:30</option>
                                        <option value="14:00">14:00</option>
                                        <option value="14:30">14:30</option>
                                        <option value="15:00">15:00</option>
                                        <option value="15:30">15:30</option>
                                        <option value="16:00">16:00</option>
                                        <!-- <option value="16:30">16:30</option> -->
                                        <option value="17:00" selected>16:40</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mt-3 row">
                                <div class="col-12">
                                    <label for="telPhone" class="form-label">เบอร์โทร</label>
                                    <input type="text" class="form-control" id="telPhone" name="telPhone" disabled>
                                </div>
                            </div>
                            <div class="mt-3 row">
                                <div class="col-12">
                                    <label for="file" class="form-label">ไฟล์แนบ (PNG , JPG, JPEG)</label>
                                    <input class="form-control" type="file" id="file" name="file" />
                                </div>
                            </div>

                            <div class="mt-3 d-flex justify-content-end">
                                <button type="submit" class="btn btn-success" id="btnSubmitForm1" name="submit"
                                    style="white-space: nowrap;">บันทึก</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover" style="border-top: 1px solid rgba(0, 0, 0, 0.1);" id="leaveTable">
                <thead class="table table-secondary">
                    <tr class="text-center align-middle">
                        <th rowspan="2">ลำดับ</th>
                        <th rowspan="2">วันที่ยื่น</th>
                        <th rowspan="2">ประเภทรายการ</th>
                        <th colspan="2">วันเวลา</th>
                        <th rowspan="2">จำนวนวันลา</th>
                        <th rowspan="2">ไฟล์แนบ</th>
                        <th rowspan="2">สถานะรายการ</th>
                        <th rowspan="2">สถานะมาสาย</th>
                        <th rowspan="2">สถานะอนุมัติ_1</th>
                        <th rowspan="2">สถานะอนุมัติ_2</th>
                        <th rowspan="2">สถานะ (เฉพาะ HR)</th>
                        <th rowspan="2"></th>
                    </tr>
                    <tr class="text-center">
                        <th>จาก</th>
                        <th>ถึง</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
// กำหนดจำนวนรายการต่อหน้า
$itemsPerPage = 10;

// คำนวณหน้าปัจจุบัน
if (!isset($_GET['page'])) {
    $currentPage = 1;
} else {
    $currentPage = $_GET['page'];
}

// สร้างคำสั่ง SQL
$sql = "SELECT * FROM leave_list WHERE Month(l_leave_start_date) = '$selectedMonth'
AND Year(l_leave_start_date) = '$selectedYear' 
AND l_leave_id IN (1,2,3,4,5,8)
AND l_remark = 'HR ลาย้อนหลัง'
ORDER BY l_create_datetime DESC ";

// หาจำนวนรายการทั้งหมด
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
        echo '<tr class="text-center align-middle">';

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
            echo $row['l_leave_reason'];
        }
        echo '</td>';

        // 1
        echo '<td hidden>' . $row['l_department'] . '</td>';

        // 2
        echo '<td hidden>' . $row['l_leave_reason'] . '</td>';

        // 3
        echo '<td hidden>' . $row['l_leave_start_date'] . '</td>';

        // 4
        echo '<td hidden>' . $row['l_leave_start_time'] . '</td>';

        // 5
        echo '<td hidden>' . $row['l_leave_end_time'] . '</td>';

        // 6
        echo '<td>' . $rowNumber . '</td>';

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
            echo '<span class="text-primary">' . 'ขาดงาน' . '</span>' . '<br>';
        } elseif ($row['l_leave_id'] == 7) {
            echo '<span class="text-primary">' . 'มาสาย' . '</span>';
        } elseif ($row['l_leave_id'] == 8) {
            echo '<span class="text-primary">' . 'อื่น ๆ' . '</span>' . '<br>' . 'เหตุผล : ' . $row['l_leave_reason'];
        } else {
            echo $row['l_leave_reason'];
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

        // echo '<td>' . $row['l_leave_end_date'] . '<br> ' . $row['l_leave_end_time'] . '</td>';

        // 11
        echo '<td>';
// Query to check holidays in the leave period
        $holiday_query = "SELECT COUNT(*) as holiday_count
                  FROM holiday
                  WHERE h_start_date BETWEEN :start_date AND :end_date
                  AND h_holiday_status = 'วันหยุด'
                  AND h_status = 0";

// Prepare the query
        $holiday_stmt = $conn->prepare($holiday_query);
        $holiday_stmt->bindParam(':start_date', $row['l_leave_start_date']);
        $holiday_stmt->bindParam(':end_date', $row['l_leave_end_date']);
        $holiday_stmt->execute();

// Fetch the holiday count
        $holiday_data = $holiday_stmt->fetch(PDO::FETCH_ASSOC);
        $holiday_count = $holiday_data['holiday_count'];
// คำนวณระยะเวลาการลา
        $l_leave_start_date = new DateTime($row['l_leave_start_date'] . ' ' . $row['l_leave_start_time']);
        $l_leave_end_date = new DateTime($row['l_leave_end_date'] . ' ' . $row['l_leave_end_time']);
        $interval = $l_leave_start_date->diff($l_leave_end_date);

// คำนวณจำนวนวันลา
        $leave_days = $interval->days - $holiday_count;

// คำนวณจำนวนชั่วโมงและนาทีลา
        $leave_hours = $interval->h;
        $leave_minutes = $interval->i;

// ตรวจสอบช่วงเวลาและหักชั่วโมงตามเงื่อนไข
        $start_hour = (int) $l_leave_start_date->format('H');
        $end_hour = (int) $l_leave_end_date->format('H');

        if (!((($start_hour >= 8 && $start_hour < 12) && ($end_hour <= 12)) ||
            (($start_hour >= 13 && $start_hour < 17) && ($end_hour <= 17)))) {
            // ถ้าไม่อยู่ในช่วงที่กำหนด ให้หัก 1 ชั่วโมง
            $leave_hours -= 1;
        }

// ตรวจสอบการหักเวลาเมื่อเกิน 8 ชั่วโมง
        if ($leave_hours >= 8) {
            $leave_days += floor($leave_hours / 8);
            $leave_hours = $leave_hours % 8; // Remaining hours after converting to days
        }

// ตรวจสอบการนาที
        if ($leave_minutes >= 30) {
            $leave_minutes = 30; // ถ้านาทีมากกว่าหรือเท่ากับ 30 นับเป็น 5 นาที
        }

// แสดงผลลัพธ์
        if ($row['l_leave_id'] == 7) {
            echo '';
        } else {
            echo '<span class="text-primary">' . $leave_days . ' วัน ' . $leave_hours . ' ชั่วโมง ' . $leave_minutes . ' นาที</span>';

        }

        echo '</td>';

        // 12
        if (!empty($row['l_file'])) {
            echo '<td><button id="imgBtn" class="btn btn-primary" onclick="window.open(\'../upload/' . $row['l_file'] . '\', \'_blank\')"><i class="fa-solid fa-file"></i></button></td>';
        } else {
            echo '<td><button id="imgNoBtn" class="btn btn-primary" disabled><i class="fa-solid fa-file-excel"></i></button></td>';
        }

        // 13
        echo '<td>';
        if ($row['l_leave_status'] == 0) {
            echo '<span class="text-success">ปกติ</span>';
        } else {
            echo '<span class="text-danger">ยกเลิก</span>';
        }
        echo '</td>';

        // 14
        echo '<td>';
        if ($row['l_late_datetime'] == '') {
            echo '';
        } else {
            echo '<span class="text-success">ยืนยัน</span>';
        }
        echo '</td>';

        // 15
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
         elseif ($row['l_approve_status'] == 6) {
            echo '';
        }
        // ไม่มีสถานะ
        else {
            echo 'ไม่มีสถานะ';
        }
        echo '</td>';

        // 16
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
        }
        elseif ($row['l_approve_status2'] == 6) {
            echo '';
        }
        // ไม่มีสถานะ
        else {
            echo 'ไม่มีสถานะ';
        }
        echo '</td>';

        // 17
        echo '<td>';
        if ($row['l_hr_status'] == 0) {
            echo '<span class="text-warning"><b>รอตรวจสอบ</b></span>';
        } elseif ($row['l_hr_status'] == 1) {
            echo '<span class="text-success"><b>ผ่าน</b></span>';
        } else {
            echo '<span class="text-danger"><b>ไม่ผ่าน</b></span>';
        }
        echo '</td>';

        // 18
        $disabled = $row['l_leave_status'] == 1 ? 'disabled' : '';
        if ($row['l_leave_id'] != 7) {
            echo '<td><button type="button" class="button-shadow btn btn-danger cancel-leave-btn" data-leaveid="' . $row['l_leave_id'] . '" data-createdatetime="' . $row['l_create_datetime'] . '" data-usercode="' . $userCode . '" ' . $disabled . '><i class="fa-solid fa-times"></i> ยกเลิกรายการ</button></td>';
        } else if ($row['l_leave_id'] == 7) {
            echo '<td><button type="button" class="button-shadow btn btn-primary confirm-late-btn" data-createdatetime="' . $row['l_create_datetime'] . '" data-usercode="' . $userCode . '" ' . $disabled . '>ยืนยันรายการ</button></td>';
        } else {
            echo '<td></td>'; // กรณีที่ l_leave_id เท่ากับ 7 ไม่แสดงปุ่มและเว้นคอลัมน์ว่าง
        }

        echo '</tr>';
        $rowNumber--;
        // echo '<td><img src="../upload/' . $row['Img_file'] . '" id="img" width="100" height="100"></td>';
    }
} else {
    echo "<tr><td colspan='12' style='color: red;'>ไม่พบข้อมูล</td></tr>";
}
// ปิดการเชื่อมต่อ
// $conn = null;
?>

                </tbody>
            </table>
        </div>
    </div>
    <script>
    document.getElementById('userCode').addEventListener('input', function() {
        var selectedCode = this.value;
        var nameField = document.getElementById('name');
        var telPhoneField = document.getElementById('telPhone');
        var userNameField = document.getElementById('userName');
        var departField = document.getElementById('depart');
        var levelField = document.getElementById('level');
        var workPlaceField = document.getElementById('workplace');
        var subDepartField = document.getElementById('subDepart');
        var subDepart2Field = document.getElementById('subDepart2');
        var subDepart3Field = document.getElementById('subDepart3');
        var subDepart4Field = document.getElementById('subDepart4');
        var subDepart5Field = document.getElementById('subDepart5');

        if (selectedCode === "") {
            nameField.value = "";
            telPhoneField.value = "";
            userNameField.value = "";
            departField.value = "";
            levelField.value = "";
            workPlaceField.value = "";
            subDepartField.value = "";
            subDepart2Field.value = "";
            subDepart3Field.value = "";
            subDepart4Field.value = "";
            subDepart5Field.value = "";
            return;
        }

        var dataList = document.getElementById('codeList').getElementsByTagName('option');
        for (var i = 0; i < dataList.length; i++) {
            if (dataList[i].value === selectedCode) {
                nameField.value = dataList[i].getAttribute('data-name');
                telPhoneField.value = dataList[i].getAttribute('data-telPhone');
                userNameField.value = dataList[i].getAttribute('data-username');
                departField.value = dataList[i].getAttribute('data-depart');
                levelField.value = dataList[i].getAttribute('data-level');
                workPlaceField.value = dataList[i].getAttribute('data-workplace');
                subDepartField.value = dataList[i].getAttribute('data-subDepart');
                subDepart2Field.value = dataList[i].getAttribute('data-subDepart2');
                subDepart3Field.value = dataList[i].getAttribute('data-subDepart3');
                subDepart4Field.value = dataList[i].getAttribute('data-subDepart4');
                subDepart5Field.value = dataList[i].getAttribute('data-subDepart5');
                break;
            }
        }
    });


    $(document).ready(function() {
        $.ajax({
            url: 'a_u_ajax_get_holiday.php', // สร้างไฟล์ PHP เพื่อตรวจสอบวันหยุด
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                var today = new Date(); // วันที่ปัจจุบัน

                // สร้างปฏิทิน Flatpickr พร้อมปิดวันที่เป็นวันหยุด และไม่สามารถเลือกวันที่ก่อนหน้าวันที่ปัจจุบันได้
                flatpickr("#startDate", {
                    dateFormat: "d-m-Y", // ตั้งค่าเป็น วัน/เดือน/ปี
                    defaultDate: today, // กำหนดวันที่เริ่มต้นเป็นวันที่ปัจจุบัน
                    // minDate: today, // ห้ามเลือกวันที่ในอดีต
                    disable: response.holidays // ปิดวันที่ที่เป็นวันหยุด
                });

                flatpickr("#endDate", {
                    dateFormat: "d-m-Y", // ตั้งค่าเป็น วัน/เดือน/ปี
                    defaultDate: today, // กำหนดวันที่สิ้นสุดเป็นวันที่ปัจจุบัน
                    // minDate: today, // ห้ามเลือกวันที่ในอดีต
                    disable: response.holidays // ปิดวันที่ที่เป็นวันหยุด
                });

                flatpickr("#urgentStartDate", {
                    dateFormat: "d-m-Y", // ตั้งค่าเป็น วัน/เดือน/ปี
                    defaultDate: today, // กำหนดวันที่เริ่มต้นเป็นวันที่ปัจจุบัน
                    // minDate: today, // ห้ามเลือกวันที่ในอดีต
                    disable: response.holidays // ปิดวันที่ที่เป็นวันหยุด
                });

                flatpickr("#urgentEndDate", {
                    dateFormat: "d-m-Y", // ตั้งค่าเป็น วัน/เดือน/ปี
                    defaultDate: today, // กำหนดวันที่สิ้นสุดเป็นวันที่ปัจจุบัน
                    // minDate: today, // ห้ามเลือกวันที่ในอดีต
                    disable: response.holidays // ปิดวันที่ที่เป็นวันหยุด
                });
            }
        });

        $('#leaveForm').on('submit', function(e) {
            e.preventDefault(); // ป้องกันการส่งฟอร์มแบบปกติ

            // เก็บข้อมูลฟอร์มทั้งหมดรวมถึงไฟล์ที่แนบ
            var formData = new FormData(this);
            var userCode = $('#userCode').val();
            var userName = $('#userName').val();
            var depart = $('#depart').val();
            var name = $('#name').val();
            var level = $('#level').val();
            var workplace = $('#workplace').val();
            var subDepart = $('#subDepart').val();
            var subDepart2 = $('#subDepart2').val();
            var subDepart3 = $('#subDepart3').val();
            var subDepart4 = $('#subDepart4').val();
            var subDepart5 = $('#subDepart5').val();
            var telPhone = $('#telPhone').val();
            var leaveType = $('#leaveType').val();
            var leaveReason = $('#leaveReason').val();
            var startDate = $('#startDate').val();
            var startTime = $('#startTime').val();
            var endDate = $('#endDate').val();
            var endTime = $('#endTime').val();
            var files = $('#file')[0].files;

            formData.append('userCode', $('#userCode').val());
            formData.append('userName', $('#userName').val());
            formData.append('name', $('#name').val());
            formData.append('level', $('#level').val());
            formData.append('workplace', $('#workplace').val());
            formData.append('subDepart', $('#subDepart').val());
            formData.append('subDepart2', $('#subDepart2').val());
            formData.append('subDepart3', $('#subDepart3').val());
            formData.append('subDepart4', $('#subDepart4').val());
            formData.append('subDepart5', $('#subDepart5').val());
            formData.append('depart', $('#depart').val());
            formData.append('telPhone', $('#telPhone').val());
            formData.append('leaveType', $('#leaveType').val());
            formData.append('leaveReason', $('#leaveReason').val());
            formData.append('startDate', $('#startDate').val());
            formData.append('startTime', $('#startTime').val());
            formData.append('endDate', $('#endDate').val());
            formData.append('endTime', $('#endTime').val());

            var addUserName = "<?php echo $userName; ?>";
            formData.append('addUserName', addUserName);

            if (files.length > 0) {
                formData.append('file', files[0]);
            }

            if (leaveType == 'เลือกประเภทการลา') {
                Swal.fire({
                    title: "ไม่สามารถลาได้",
                    text: "กรุณาเลือกประเภทการลา",
                    icon: "error"
                });
                return false;
            } else if (leaveReason == '') {
                Swal.fire({
                    title: "ไม่สามารถลาได้",
                    text: "กรุณาระบุเหตุผลการลา",
                    icon: "error"
                });
                return false;
            } else {
                // ลบ - ออกจากวันที่
                // var startDate = $('#startDate').val().replace(/-/g, '');
                // var endDate = $('#endDate').val().replace(/-/g, '');
                // var startTime = $('#startTime').val(); // เช่น "08:00"
                // var endTime = $('#endTime').val(); // เช่น "17:00"

                // // ตรวจสอบว่าค่าวันที่มีค่าหรือไม่
                // if (!startDate || !endDate || !startTime || !endTime) {
                //     Swal.fire({
                //         title: "ข้อผิดพลาด",
                //         text: "กรุณาเลือกวันที่เริ่มต้น, วันที่สิ้นสุด, เวลาเริ่มต้น และเวลาเสร็จสิ้น",
                //         icon: "error"
                //     });
                //     return false; // หยุดการทำงาน
                // }

                // // แปลงวันที่เป็นรูปแบบ Date พร้อมเวลา
                // var start = new Date(startDate.substring(0, 4), startDate.substring(4, 6) - 1, startDate
                //     .substring(6, 8), startTime.split(':')[0], startTime.split(':')[1]);
                // var end = new Date(endDate.substring(0, 4), endDate.substring(4, 6) - 1, endDate
                //     .substring(6, 8), endTime.split(':')[0], endTime.split(':')[1]);

                // // ตรวจสอบว่าการแปลงวันที่สำเร็จหรือไม่
                // if (isNaN(start.getTime()) || isNaN(end.getTime())) {
                //     Swal.fire({
                //         title: "ข้อผิดพลาด",
                //         text: "วันที่หรือเวลาไม่ถูกต้อง กรุณาตรวจสอบอีกครั้ง",
                //         icon: "error"
                //     });
                //     return false; // หยุดการทำงาน
                // }

                // // คำนวณความแตกต่างของวันและเวลา
                // var timeDiff = end - start; // ความแตกต่างเป็นมิลลิวินาที
                // var fullDays = Math.floor(timeDiff / (1000 * 3600 * 8)); // จำนวนวันเต็ม
                // var remainingTimeInMs = timeDiff % (1000 * 3600 * 8); // มิลลิวินาทีที่เหลือจากวันเต็ม
                // var hoursDiff = Math.floor(remainingTimeInMs / (1000 * 3600)); // จำนวนชั่วโมงที่เหลือ
                // var minutesDiff = Math.floor((remainingTimeInMs % (1000 * 3600)) / (1000 *
                //     60)); // คำนวณนาทีที่เหลือ

                // // คำนวณวันที่รวมทั้งหมดเป็นทศนิยม (เช่น 2.5 สำหรับ 2 วันกับ 4 ชั่วโมง)
                // var totalDaysWithHoursAndMinutes = fullDays + (hoursDiff / 8) + (minutesDiff /
                //     480); // ใช้ 8 ชั่วโมงและ 480 นาทีต่อวันเป็นฐาน

                // // console.log(totalDaysWithHoursAndMinutes); // แสดงผลลัพธ์ใน console

                // // เงื่อนไขสำหรับ leaveType = 3
                // if (leaveType == 3) {
                //     if (totalDaysWithHoursAndMinutes > 219145.125) { // หากเวลาลามากกว่า 3 วัน
                //         if (files.length === 0) {
                //             Swal.fire({
                //                 title: "ไม่สามารถลาได้",
                //                 text: "กรุณาแนบไฟล์เมื่อลาเกิน 3 วัน",
                //                 icon: "error"
                //             });
                //             return false;
                //         }
                //     }
                // }

                // // ลากิจ, ลาพักร้อนให้ลาล่วงหน้า 1 วัน
                // if (leaveType == 1 || leaveType == 5) {
                //     var startDate = $('#startDate').val();
                //     var parts = startDate.split('-');
                //     var formattedDate = parts[2] + '-' + parts[1] + '-' + parts[
                //         0]; // เปลี่ยนเป็น 'YYYY-MM-DD'

                //     // สร้าง Date object โดยไม่ต้องตั้งเวลา
                //     var leaveStartDate = new Date(formattedDate + 'T00:00:00'); // ตั้งเวลาเป็น 00:00:00

                //     var currentDate = new Date();
                //     currentDate.setHours(0, 0, 0, 0); // ตั้งเวลาเป็น 00:00:00

                //     console.log("leaveStartDate :" + leaveStartDate);
                //     console.log("currentDate: " + currentDate);

                //     // เช็คว่า startDate เก่ากว่าหรือไม่
                //     if (leaveStartDate <= currentDate) {
                //         Swal.fire({
                //             title: "ไม่สามารถลาได้",
                //             text: "กรุณายื่นลาล่วงหน้าก่อน 1 วัน",
                //             icon: "error"
                //         });
                //         return false; // หยุดการส่งแบบฟอร์ม
                //     }
                // }

                if (endDate < startDate) {
                    Swal.fire({
                        title: "ไม่สามารถลาได้",
                        text: "กรุณาเลือกวันที่เริ่มต้นลาใหม่",
                        icon: "error"
                    });
                    return false;
                } else {
                    $.ajax({
                        url: 'a_ajax_add_emp_leave.php',
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            Swal.fire({
                                title: "บันทึกสำเร็จ",
                                text: "บันทึกคำขอลาสำเร็จ",
                                icon: "success"
                            }).then(() => {
                                location.reload();
                            });
                        },
                        error: function() {
                            Swal.fire({
                                title: "เกิดข้อผิดพลาด",
                                text: "ไม่สามารถบันทึกคำขอลาได้",
                                icon: "error"
                            });
                        },
                        complete: function() {
                            // เปิดการใช้งานปุ่มอีกครั้ง
                            $('#btnSubmitForm1').prop('disabled', false).html(
                                'ยื่นใบลา');
                        }
                    });
                }
            }
        });
    });
    </script>
    <script src="../js/popper.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/bootstrap.bundle.js"></script>
    <script src="../js/bootstrap.bundle.min.js"></script>
</body>

</html>