<?php
require_once 'config/connect_db.php';

// ส่วนประมวลผล API
if (isset($_GET['action']) && in_array($_GET['action'], ['convert_innodb', 'optimize']) && isset($_GET['table'])) {
    $table = $_GET['table'];
    $skipExisting = isset($_GET['skip_existing']) && $_GET['skip_existing'] == '1';
    $response = [
        'success'    => false,
        'skipped'    => false,
        'converted'  => false,
        'old_engine' => '',
        'new_engine' => '',
        'before'     => 0,
        'after'      => 0,
        'saved'      => 0,
        'message'    => ''
    ];

    try {
        // ตรวจสอบ TABLE_TYPE และ ENGINE ของตาราง
        $infoStmt = $conn->prepare(
            "SELECT TABLE_TYPE, ENGINE 
             FROM information_schema.TABLES 
             WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = :table"
        );
        $infoStmt->execute(['table' => $table]);
        $info = $infoStmt->fetch(PDO::FETCH_ASSOC);

        // ข้าม VIEW
        if (!$info || strtoupper($info['TABLE_TYPE'] ?? '') === 'VIEW') {
            $response['skipped'] = true;
            $response['success'] = true;
            $response['message'] = "⏭️ ข้าม: `$table` [VIEW]";
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }

        $engine = strtoupper($info['ENGINE'] ?? '');
        $response['old_engine'] = $engine;

        // ถ้าตารางเป็น InnoDB อยู่แล้ว และผู้ใช้เลือกข้าม
        if ($engine === 'INNODB' && $skipExisting) {
            $response['skipped'] = true;
            $response['success'] = true;
            $response['message'] = "⏭️ ข้าม: `$table` [เป็น InnoDB อยู่แล้ว]";
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }

        // วัดขนาดก่อนดำเนินการ (MB)
        $sizeQuery = "SELECT ROUND(((data_length + index_length) / 1024 / 1024), 2) 
                      FROM information_schema.TABLES 
                      WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = :table";
        $stmtSize = $conn->prepare($sizeQuery);
        $stmtSize->execute(['table' => $table]);
        $response['before'] = (float)$stmtSize->fetchColumn();

        // รันคำสั่งแปลง Engine เป็น InnoDB (ถ้าเป็น InnoDB อยู่แล้ว จะเป็นการ Rebuild & Defragment)
        $conn->exec("ALTER TABLE `$table` ENGINE = InnoDB");

        // ANALYZE TABLE เพื่ออัปเดต index statistics
        $conn->exec("ANALYZE TABLE `$table`");

        // ตรวจสอบ ENGINE ล่าสุดหลังแปลง
        $stmtEngine = $conn->prepare(
            "SELECT ENGINE 
             FROM information_schema.TABLES 
             WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = :table"
        );
        $stmtEngine->execute(['table' => $table]);
        $newEngine = strtoupper($stmtEngine->fetchColumn() ?? 'INNODB');
        $response['new_engine'] = $newEngine;

        // วัดขนาดหลังดำเนินการ (MB)
        $stmtSize->execute(['table' => $table]);
        $response['after'] = (float)$stmtSize->fetchColumn();

        $response['success'] = true;
        $saved = max(0, $response['before'] - $response['after']);
        $response['saved'] = round($saved, 2);

        if ($engine !== 'INNODB') {
            $response['converted'] = true;
            $response['message'] = "🔄 แปลงสำเร็จ: `$table` [$engine ➔ $newEngine] | [{$response['before']} MB → {$response['after']} MB]" . ($saved > 0 ? " ลดไป: " . round($saved, 2) . " MB" : "");
        } else {
            $response['converted'] = false;
            $response['message'] = "✅ [InnoDB] `$table` [Rebuild/Optimize สำเร็จ] | [{$response['before']} MB → {$response['after']} MB]" . ($saved > 0 ? " ลดไป: " . round($saved, 2) . " MB" : "");
        }

    } catch (PDOException $e) {
        $response['message'] = "❌ ผิดพลาด: `$table` - " . $e->getMessage();
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// ส่วนการแสดงผล
include('includes/Header.php');

if (strlen($_SESSION['alogin']) == "") {
    header("Location: index.php");
    exit;
} else {
    // ดึงรายการ Tables ทั้งหมดใน Database
    $stmt = $conn->query(
        "SELECT TABLE_NAME, TABLE_TYPE, ENGINE 
         FROM information_schema.TABLES 
         WHERE TABLE_SCHEMA = DATABASE() 
         ORDER BY TABLE_NAME"
    );
    $allTables  = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $tableNames = array_column($allTables, 'TABLE_NAME');
    $totalCount = count($tableNames);
    $viewCount  = count(array_filter($allTables, fn($t) => strtoupper($t['TABLE_TYPE'] ?? '') === 'VIEW'));
    $baseCount  = $totalCount - $viewCount;
    $innodbCount = count(array_filter($allTables, fn($t) => strtoupper($t['TABLE_TYPE'] ?? '') !== 'VIEW' && strtoupper($t['ENGINE'] ?? '') === 'INNODB'));
    $nonInnodbCount = $baseCount - $innodbCount;

    $dashboard_url = isset($_SESSION['dashboard_page']) ? $_SESSION['dashboard_page'] : 'dashboard.php';
    ?>

    <!DOCTYPE html>
    <html lang="th">
    <head>
        <style>
            .sidebar-lock {
                position: fixed;
                top: 0; left: 0;
                width: 250px; height: 100%;
                background: rgba(0,0,0,0.1);
                z-index: 9999;
                cursor: not-allowed;
                display: none;
            }
            .working-overlay {
                pointer-events: none;
                opacity: 0.7;
            }
        </style>
    </head>
    <body id="page-top">
    <div id="lock-overlay" class="sidebar-lock"></div>

    <div id="wrapper">
        <?php include('includes/Side-Bar.php'); ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include('includes/Top-Bar.php'); ?>
                <div class="container-fluid" id="container-wrapper">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h4 mb-0 text-gray-800">Database Engine Converter (InnoDB) & Optimizer</h1>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3 bg-primary text-white d-flex justify-content-between align-items-center">
                                    <h6 class="m-0 font-weight-bold"><i class="fas fa-database mr-2"></i>แปลง Engine ตารางเป็น InnoDB ทุก Table</h6>
                                    <a href="<?php echo $dashboard_url; ?>" class="btn btn-sm btn-light shadow-sm text-primary">
                                        <i class="fas fa-home fa-sm"></i> Home
                                    </a>
                                </div>
                                <div class="card-body">
                                    <!-- Summary Cards -->
                                    <div class="row text-center mb-4">
                                        <div class="col-md-2 col-sm-4 border-right mb-2">
                                            <span class="text-muted small">ตารางทั้งหมด (BASE TABLE)</span>
                                            <div class="h3 font-weight-bold text-dark"><?php echo $baseCount; ?></div>
                                        </div>
                                        <div class="col-md-2 col-sm-4 border-right mb-2">
                                            <span class="text-muted small">ไม่ใช่ InnoDB (รอแปลง)</span>
                                            <div class="h3 font-weight-bold <?php echo $nonInnodbCount > 0 ? 'text-danger' : 'text-muted'; ?>">
                                                <?php echo $nonInnodbCount; ?>
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-sm-4 border-right mb-2">
                                            <span class="text-muted small">เป็น InnoDB อยู่แล้ว</span>
                                            <div class="h3 font-weight-bold text-info"><?php echo $innodbCount; ?></div>
                                        </div>
                                        <div class="col-md-2 col-sm-4 border-right mb-2">
                                            <span class="text-muted small">VIEW (ข้าม)</span>
                                            <div class="h3 font-weight-bold text-secondary"><?php echo $viewCount; ?></div>
                                        </div>
                                        <div class="col-md-2 col-sm-4 border-right mb-2">
                                            <span class="text-muted small">แปลงสำเร็จรอบนี้</span>
                                            <div class="h3 font-weight-bold text-success"><span id="converted-count">0</span></div>
                                        </div>
                                        <div class="col-md-2 col-sm-4 mb-2">
                                            <span class="text-muted small">พื้นที่ประหยัดได้</span>
                                            <div class="h3 font-weight-bold text-primary"><span id="total-saved">0.00</span> MB</div>
                                        </div>
                                    </div>

                                    <!-- Option Settings -->
                                    <div class="row justify-content-center mb-3">
                                        <div class="col-auto">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="skip-innodb-check">
                                                <label class="custom-control-label font-weight-bold text-gray-700" for="skip-innodb-check">
                                                    ข้ามตารางที่เป็น InnoDB อยู่แล้ว (แปลงเฉพาะตารางที่เป็น MyISAM / อื่นๆ เพื่อความรวดเร็ว)
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Buttons -->
                                    <div class="text-center mb-4">
                                        <button id="start-btn" class="btn btn-primary btn-lg px-4 shadow">
                                            <i class="fas fa-play mr-2"></i>เริ่มรันแปลง Engine เป็น InnoDB
                                        </button>
                                        <div id="after-action-btns" class="d-none">
                                            <button id="reset-btn" class="btn btn-warning btn-lg px-4 mr-2 shadow-sm">
                                                <i class="fas fa-undo mr-2"></i>Reset หน้าจอ
                                            </button>
                                            <button id="download-btn" class="btn btn-outline-info btn-lg px-4 mr-2 shadow-sm">
                                                <i class="fas fa-file-alt mr-2"></i>ดาวน์โหลดผลลัพธ์
                                            </button>
                                            <a href="<?php echo $dashboard_url; ?>" class="btn btn-outline-secondary btn-lg px-4 shadow-sm">
                                                <i class="fas fa-home mr-2"></i>กลับหน้าหลัก
                                            </a>
                                        </div>
                                    </div>

                                    <!-- Progress & Log -->
                                    <div id="ui-section" class="d-none">
                                        <div class="progress mb-3" style="height: 25px;">
                                            <div id="progress-bar" class="progress-bar progress-bar-striped progress-bar-animated bg-success" style="width: 0%;">0%</div>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span id="status-text" class="font-weight-bold text-primary small">รอดำเนินการ...</span>
                                            <span id="count-text" class="text-muted small">0 / <?php echo $totalCount; ?></span>
                                        </div>
                                        <div id="log-window" style="background-color: #1e1e1e; color: #dcdccc; padding: 20px; border-radius: 8px; height: 350px; overflow-y: auto; font-family: 'Consolas', monospace; font-size: 13px; line-height: 1.5; text-align: left;">
                                            <div style="color: #666;">--- กดปุ่มด้านบนเพื่อเริ่มกระบวนการ ---</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tables            = <?php echo json_encode($tableNames); ?>;
            const startBtn          = document.getElementById('start-btn');
            const resetBtn          = document.getElementById('reset-btn');
            const downloadBtn       = document.getElementById('download-btn');
            const afterActionBtns   = document.getElementById('after-action-btns');
            const progressBar       = document.getElementById('progress-bar');
            const uiSection         = document.getElementById('ui-section');
            const logWindow         = document.getElementById('log-window');
            const statusText        = document.getElementById('status-text');
            const countText         = document.getElementById('count-text');
            const totalSavedLabel   = document.getElementById('total-saved');
            const convertedLabel    = document.getElementById('converted-count');
            const skipInnodbCheck   = document.getElementById('skip-innodb-check');
            const lockOverlay       = document.getElementById('lock-overlay');
            const sidebar           = document.getElementById('accordionSidebar');

            let logContent = "";
            let totalSaved = 0;
            let convertedCount = 0;

            function setInterfaceLock(isLocked) {
                lockOverlay.style.display = isLocked ? 'block' : 'none';
                if (sidebar) sidebar.classList.toggle('working-overlay', isLocked);
                startBtn.disabled = isLocked;
                skipInnodbCheck.disabled = isLocked;
            }

            function appendLog(message, color = '#dcdccc', isSkipped = false) {
                const time    = new Date().toLocaleTimeString();
                const logLine = `[${time}] ${message}`;
                const div     = document.createElement('div');
                div.style.color        = color;
                div.style.marginBottom = '3px';
                div.style.opacity      = isSkipped ? '0.5' : '1';
                div.innerText          = logLine;
                logWindow.appendChild(div);
                logWindow.scrollTop    = logWindow.scrollHeight;
                logContent            += logLine + "\n";
            }

            startBtn.addEventListener('click', async () => {
                const skipExisting = skipInnodbCheck.checked;
                const confirmMsg = skipExisting
                    ? 'ยืนยันเริ่มแปลง Engine เป็น InnoDB (เฉพาะตารางที่ยังไม่ใช่ InnoDB)?'
                    : 'ยืนยันเริ่มแปลง Engine เป็น InnoDB สำหรับทุกตาราง (รวมถึง Rebuild ตารางที่เป็น InnoDB อยู่แล้ว)?';

                if (!confirm(confirmMsg)) return;

                setInterfaceLock(true);
                afterActionBtns.classList.add('d-none');
                startBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>กำลังดำเนินการ...';
                uiSection.classList.remove('d-none');
                logWindow.innerHTML = '';
                totalSaved = 0;
                convertedCount = 0;
                totalSavedLabel.innerText = "0.00";
                convertedLabel.innerText  = "0";
                logContent = "Database Convert to InnoDB Report\nDate: " + new Date().toLocaleString() + "\n" + "=".repeat(60) + "\n";

                let completed = 0;
                const total   = tables.length;

                for (const table of tables) {
                    statusText.innerText = `กำลังจัดการ: ${table}...`;
                    try {
                        const skipParam = skipExisting ? '1' : '0';
                        const res    = await fetch(`?action=convert_innodb&table=${encodeURIComponent(table)}&skip_existing=${skipParam}`);
                        const result = await res.json();

                        if (result.skipped) {
                            // ข้าม VIEW หรือ ข้ามตารางที่เป็น InnoDB อยู่แล้ว
                            appendLog(result.message, '#888888', true);
                        } else if (result.success) {
                            if (result.converted) {
                                convertedCount++;
                                convertedLabel.innerText = convertedCount;
                                appendLog(result.message, '#8cf68c'); // สีเขียวสว่างสำหรับตารางที่แปลงสำเร็จ
                            } else {
                                appendLog(result.message, '#8cd6f6'); // สีฟ้าอ่อนสำหรับตารางที่เป็น InnoDB อยู่แล้ว (Rebuild)
                            }
                            const saved = Math.max(0, (result.before || 0) - (result.after || 0));
                            totalSaved += saved;
                            totalSavedLabel.innerText = totalSaved.toFixed(2);
                        } else {
                            appendLog(result.message, '#ff6b6b');
                        }

                    } catch (error) {
                        appendLog(`❌ ไม่สามารถประมวลผลตาราง: ${table}`, '#ff6b6b');
                    }

                    completed++;
                    const percent = Math.round((completed / total) * 100);
                    progressBar.style.width  = percent + '%';
                    progressBar.innerText    = percent + '%';
                    countText.innerText      = `${completed} / ${total}`;
                }

                logContent += "=".repeat(60) + "\nTotal Converted: " + convertedCount + " tables\nTotal Space Saved: " + totalSaved.toFixed(2) + " MB\n";
                statusText.innerText = "✅ แปลง Engine เป็น InnoDB เสร็จสมบูรณ์!";
                startBtn.classList.add('d-none');
                afterActionBtns.classList.remove('d-none');
                setInterfaceLock(false);
            });

            resetBtn.addEventListener('click', () => {
                startBtn.classList.remove('d-none');
                startBtn.innerHTML = '<i class="fas fa-play mr-2"></i>เริ่มรันแปลง Engine เป็น InnoDB';
                afterActionBtns.classList.add('d-none');
                uiSection.classList.add('d-none');
                totalSavedLabel.innerText = "0.00";
                convertedLabel.innerText  = "0";
                progressBar.style.width   = '0%';
                progressBar.innerText     = '0%';
                logWindow.innerHTML       = '<div style="color: #666;">--- กดปุ่มด้านบนเพื่อเริ่มกระบวนการ ---</div>';
            });

            downloadBtn.addEventListener('click', () => {
                const blob = new Blob([logContent], { type: 'text/plain' });
                const url  = window.URL.createObjectURL(blob);
                const a    = document.createElement('a');
                a.href     = url;
                a.download = `db_convert_innodb_report_${new Date().toISOString().slice(0,10)}.txt`;
                a.click();
                window.URL.revokeObjectURL(url);
            });
        });
    </script>
    </body>
    </html>
<?php } ?>