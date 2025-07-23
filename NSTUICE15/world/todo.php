<?php
session_start();
include '../conn.php';

if (!isset($_SESSION['id'])) {
    die("User not logged in.");
}
$user_id = $_SESSION['id'];

// POST HANDLING
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['task'])) {
        $task = trim($_POST['task']);
        if ($task !== '') {
            $stmt = $conn->prepare("INSERT INTO tasks (task, date, time, completed, user_id) VALUES (?, CURDATE(), CURTIME(), 0, ?)");
            if ($stmt) {
                $stmt->bind_param("si", $task, $user_id);
                $stmt->execute();
                $id = $stmt->insert_id;
                $date = date('d M Y');
                echo "<li class='task-item list-group-item d-flex align-items-center justify-content-between' data-id=\"$id\" data-date=\"$date\">
                        <div class='d-flex align-items-center flex-grow-1 gap-3'>
                            <span class='serial fw-bold'></span>
                            <input type='checkbox' class='form-check-input complete-task'>
                            <span class='flex-grow-1 task-text' contenteditable='true'>" . htmlspecialchars($task) . "</span>
                        </div>
                        <div>
                            <button class='btn btn-sm btn-outline-danger delete-button' id=\"$id\">×</button>
                        </div>
                      </li>";
            }
        }
        exit;
    }

    if (isset($_POST['task_id'])) {
        $stmt = $conn->prepare("DELETE FROM tasks WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $_POST['task_id'], $user_id);
        $stmt->execute();
        exit;
    }

    if (isset($_POST['edit_id'], $_POST['edit_text'])) {
        $stmt = $conn->prepare("UPDATE tasks SET task = ? WHERE id = ? AND user_id = ?");
        $stmt->bind_param("sii", $_POST['edit_text'], $_POST['edit_id'], $user_id);
        $stmt->execute();
        exit;
    }

    if (isset($_POST['toggle_id']) && isset($_POST['completed'])) {
        $stmt = $conn->prepare("UPDATE tasks SET completed = ? WHERE id = ? AND user_id = ?");
        $stmt->bind_param("iii", $_POST['completed'], $_POST['toggle_id'], $user_id);
        $stmt->execute();
        exit;
    }
}

// FETCH TASKS
$stmt = $conn->prepare("SELECT * FROM tasks WHERE user_id = ? ORDER BY date DESC, completed ASC, time ASC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$tasks = $result->fetch_all(MYSQLI_ASSOC);

$grouped = [];
foreach ($tasks as $task) {
    $d = date('d M Y', strtotime($task['date']));
    $grouped[$d][] = $task;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>To-Do List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(120deg, #d1e3ff, #f0f4fb);
            font-family: 'Segoe UI', sans-serif;
            margin-bottom: 15px;
        }
        .container {
            max-width: 750px;
            margin-top: 60px;
            
        }
        .card {
             border: 1px solid #f57c00;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }

        .date-header {
    background-color: #e6f0ff !important;
    font-weight: bold;
    color: #0d6efd;
    text-align: center;
    font-size: 18px;
    padding: 10px;
    margin-top: 10px;
    border-radius: 8px;
    border-left: none; /* Remove left border if undesired */
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
}

        .task-item {
            background-color: #fff;
            margin-bottom: 5px;
            border-radius: 6px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        .task-text {
            font-size: 16px;
        }
        .completed .task-text {
            text-decoration: line-through;
            color: #aaa;
        }
        .serial {
            width: 25px;
            color: #0d6efd;
            text-align: right;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="card">
        <div class="card-header bg-primary text-white text-center position-relative">
            <a href="javascript:history.back()" class="btn btn-light position-absolute top-0 start-0 m-3 px-3 py-1 rounded-pill shadow-sm">🔙 Back</a>
            <h4 class="mb-0">📝 My To-Do List</h4>
        </div>
        <div class="card-body">
            <form class="input-group mb-3 add-new-task" autocomplete="off">
                <input type="text" name="new-task" class="form-control" placeholder="Add a new task...">
                <button class="btn btn-success" type="submit">Add</button>
            </form>
            <ul class="list-group list-group-flush" id="task-ul">
                <?php foreach ($grouped as $date => $taskList): ?>
                    <li class="list-group-item date-header" data-date="<?= $date ?>"><?= $date ?></li>
                    <?php $serial = 1; foreach ($taskList as $task): ?>
                        <li class="task-item list-group-item d-flex align-items-center justify-content-between <?= $task['completed'] ? 'completed' : '' ?>" data-id="<?= $task['id'] ?>" data-date="<?= $date ?>">
                            <div class="d-flex align-items-center flex-grow-1 gap-3">
                                <span class="serial"><?= $serial++ ?>.</span>
                                <input type="checkbox" class="form-check-input complete-task" <?= $task['completed'] ? 'checked' : '' ?>>
                                <span class="flex-grow-1 task-text" contenteditable="true"><?= htmlspecialchars($task['task']) ?></span>
                            </div>
                            <div>
                                <button class="btn btn-sm btn-outline-danger delete-button" id="<?= $task['id'] ?>">×</button>
                            </div>
                        </li>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
add_task(); delete_task(); toggle_complete(); edit_task(); renumberTasks();

function add_task() {
    $('.add-new-task').submit(function () {
        let new_task = $('input[name=new-task]').val().trim();
        if (new_task !== '') {
            $.post('', { task: new_task }, function (data) {
                $('input[name=new-task]').val('');
                let date = new Date().toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
                let $dateHeader = $('#task-ul .date-header[data-date="' + date + '"]');
                if ($dateHeader.length) {
                    let $last = $dateHeader.nextUntil('.date-header').last();
                    $(data).hide().insertAfter($last).fadeIn();
                } else {
                    $('#task-ul').prepend('<li class="list-group-item date-header" data-date="'+date+'">'+date+'</li>' + data);
                }
                renumberTasks();
                delete_task(); toggle_complete(); edit_task();
            });
        }
        return false;
    });
}

function delete_task() {
    $('.delete-button').off().on('click', function () {
        const id = $(this).attr('id');
        const $li = $(this).closest('li');
        $.post('', { task_id: id }, function () {
            const date = $li.data('date');
            $li.remove();
            if ($('.task-item[data-date="' + date + '"]').length === 0) {
                $('.date-header[data-date="' + date + '"]').remove();
            }
            renumberTasks();
        });
    });
}

function toggle_complete() {
    $('.complete-task').off().on('change', function () {
        const li = $(this).closest('li');
        const id = li.data('id');
        const completed = $(this).is(':checked') ? 1 : 0;
        $.post('', { toggle_id: id, completed: completed });
        li.toggleClass('completed', completed);
    });
}

function edit_task() {
    $('#task-ul .task-text').off().on('blur', function () {
        const text = $(this).text().trim();
        const id = $(this).closest('li').data('id');
        if (text !== '') {
            $.post('', { edit_id: id, edit_text: text });
        }
    });
}

function renumberTasks() {
    let count = 1;
    let currentDate = '';
    $('#task-ul').children().each(function () {
        if ($(this).hasClass('date-header')) {
            currentDate = $(this).data('date');
            count = 1;
        } else {
            $(this).find('.serial').text(count + '.');
            count++;
        }
    });
}
</script>
</body>
</html>
