<?php
session_start();
include '../conn.php';

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Search Users</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {

      margin: 0;
      padding: 0;
      height: 100%;
      background: linear-gradient(120deg, #d1e3ff, #f0f4fb);
      font-family: 'Segoe UI', sans-serif
      background-color: #f0f2f5; 
    
    }
    .search-card {
      background: linear-gradient(to right, #ffffff, #f3f9ff);
      border-radius: 20px;
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
      padding: 40px;
      border: 1px solid #f57c00;
      font-size:  18px;
    }
    .dropdown-toggle::after {
      display: none;
    }
    .dropdown-menu {
      max-height: 250px;
      overflow-y: auto;
      border-radius: 10px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }
    .form-check-input:checked {
      background-color: #0d6efd;
      border-color: #0d6efd;
    }
    .form-check-label {
      cursor: pointer;
    }
    .btn-primary {
      background: linear-gradient(to right, #007bff, #0056b3);
      border: none;
      border-radius: 30px;
    }
    .btn-primary:hover {
      background: linear-gradient(to right, #0056b3, #003c80);
    }
    .result-table {
      background: #ffffff;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    }
    .table-hover tbody tr:hover {
      background-color: #f0f8ff;
    }
    .table th {
      background-color: #007bff;
      color: white;
      text-align: center;
    }
    .table td {
      vertical-align: middle;
      text-align: center;
    }

    .btn-default {
    background-color: #f1f5f9;
    border-color: #cbd5e1;
    color: #1e293b;
    font-weight: 500;
    transition: all 0.2s ease;
}

.btn-default:hover {
    background-color: green;
    color: #0f172a;
}

  </style>
</head>
<body>

<div class="container my-5">



  <div class="search-card mb-5">
    <div class="text-left" style="margin-bottom: 20px;">


    <h3 class="mb-4 text-center">🔍 Search Users</h3>

    <form method="GET" class="row g-3">

    <!-- Name Input -->
<div class="col-md-3">
  <label class="form-label fw-bold">Name</label>
  <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($_GET['name'] ?? '') ?>" placeholder="Enter name...">
</div>

      <!-- Blood Group Dropdown -->
      <div class="col-md-3">
        <label class="form-label fw-bold">Blood Group</label>
        <?php $blood_groups = (array) ($_GET['blood_group'] ?? []); ?>
        <div class="dropdown">
          <button id="btn-blood" class="form-control dropdown-toggle text-start" type="button" data-bs-toggle="dropdown">
            <?= empty($blood_groups) ? 'Select Blood Groups' : implode(', ', $blood_groups) ?>
          </button>
          <ul class="dropdown-menu px-3">
            <?php
            $res = mysqli_query($conn, "SELECT DISTINCT blood_group FROM user_data WHERE is_approved = 1 AND blood_group != ''");
            while ($row = mysqli_fetch_assoc($res)) {
                $bg = $row['blood_group'];
                $checked = in_array($bg, $blood_groups) ? 'checked' : '';
                echo "<li><div class='form-check'>
                        <input class='form-check-input' type='checkbox' name='blood_group[]' value='$bg' $checked>
                        <label class='form-check-label'>$bg</label>
                      </div></li>";
            }
            ?>
          </ul>
        </div>
      </div>

      <!-- Location Dropdown -->
      <div class="col-md-3">
        <label class="form-label fw-bold">Location</label>
        <?php $locations = (array) ($_GET['location'] ?? []); ?>
        <div class="dropdown">
          <button id="btn-location" class="form-control dropdown-toggle text-start" type="button" data-bs-toggle="dropdown">
            <?= empty($locations) ? 'Select Locations' : implode(', ', $locations) ?>
          </button>
          <ul class="dropdown-menu px-3">
            <?php
            $res = mysqli_query($conn, "SELECT DISTINCT location FROM user_data WHERE is_approved = 1 AND location != ''");
            while ($row = mysqli_fetch_assoc($res)) {
                $loc = $row['location'];
                $checked = in_array($loc, $locations) ? 'checked' : '';
                echo "<li><div class='form-check'>
                        <input class='form-check-input' type='checkbox' name='location[]' value='$loc' $checked>
                        <label class='form-check-label'>$loc</label>
                      </div></li>";
            }
            ?>
          </ul>
        </div>
      </div>

      <!-- Batch Dropdown -->
      <div class="col-md-3">
        <label class="form-label fw-bold">Batch No</label>
        <?php $batch_nos = (array) ($_GET['batch_no'] ?? []); ?>
        <div class="dropdown">
          <button id="btn-batch" class="form-control dropdown-toggle text-start" type="button" data-bs-toggle="dropdown">
            <?= empty($batch_nos) ? 'Select Batches' : implode(', ', $batch_nos) ?>
          </button>
          <ul class="dropdown-menu px-3">
            <?php
            $res = mysqli_query($conn, "SELECT DISTINCT batch_no FROM user_data WHERE is_approved = 1 AND batch_no != ''");
            while ($row = mysqli_fetch_assoc($res)) {
                $batch = $row['batch_no'];
                $checked = in_array($batch, $batch_nos) ? 'checked' : '';
                echo "<li><div class='form-check'>
                        <input class='form-check-input' type='checkbox' name='batch_no[]' value='$batch' $checked>
                        <label class='form-check-label'>$batch</label>
                      </div></li>";
            }
            ?>
          </ul>
        </div>
      </div>

      <div class="col-12 text-center mt-3">
        <button type="submit" class="btn btn-primary btn-lg px-5">Search</button>
      </div>
    </form>
  </div>

  <?php
  if ($_GET) {
      $query = "SELECT * FROM user_data WHERE is_approved = 1";

      if (!empty($_GET['name'])) {
    $name = mysqli_real_escape_string($conn, $_GET['name']);
    $query .= " AND (first_name LIKE '%$name%' OR last_name LIKE '%$name%')";
}



      if (!empty($blood_groups)) {
          $bg_safe = array_map(fn($bg) => "'" . mysqli_real_escape_string($conn, $bg) . "'", $blood_groups);
          $query .= " AND blood_group IN (" . implode(',', $bg_safe) . ")";
      }

      if (!empty($locations)) {
          $loc_safe = array_map(fn($l) => "'" . mysqli_real_escape_string($conn, $l) . "'", $locations);
          $query .= " AND location IN (" . implode(',', $loc_safe) . ")";
      }

      if (!empty($batch_nos)) {
          $batch_safe = array_map(fn($b) => "'" . mysqli_real_escape_string($conn, $b) . "'", $batch_nos);
          $query .= " AND batch_no IN (" . implode(',', $batch_safe) . ")";
      }

      $result = mysqli_query($conn, $query);

      if ($result && mysqli_num_rows($result) > 0) {
          echo "<div class='result-table mt-4'>";
          echo "<table class='table table-striped table-hover mb-0'>";
          echo "<thead class='table-dark'><tr>
                  <th>Name</th>
                  <th>Blood Group</th>
                  <th>Batch</th>
                  <th>Location</th>
                </tr></thead><tbody>";
          while ($row = mysqli_fetch_assoc($result)) {
             $full_name = htmlspecialchars($row['first_name'] . ' ' . $row['last_name']);
$user_id = $row['id'];
$image = !empty($row['image']) ? '../user_image/' . htmlspecialchars($row['image']) : 'https://via.placeholder.com/35';

echo "<tr>
        <td>
          <a href='view_user.php?id=$user_id' class='d-flex align-items-center text-decoration-none text-dark'>
            <img src='$image' alt='Profile' style='width: 35px; height: 35px; object-fit: cover; border-radius: 50%; margin-right: 10px;'>
            $full_name
          </a>
        </td>
        <td>{$row['blood_group']}</td>
        <td>{$row['batch_no']}</td>
        <td>{$row['location']}</td>
      </tr>";

          }
          echo "</tbody></table></div>";
      } else {
          echo "<div class='alert alert-warning mt-4 text-center'>🚫 No users found.</div>";
      }
  }
  ?>


</div>


<div class="text-center">
            <a href="world.php" class="btn btn-outline-secondary">← Back to Dashboard</a>
        </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  function updateLabel(buttonId, checkboxName, defaultText) {
    const checkboxes = document.querySelectorAll(`input[name='${checkboxName}[]']`);
    const selected = Array.from(checkboxes).filter(c => c.checked).map(c => c.value);
    document.getElementById(buttonId).textContent = selected.length > 0 ? selected.join(', ') : defaultText;
  }

  document.addEventListener('DOMContentLoaded', function () {
    const mappings = [
      { btn: 'btn-blood', name: 'blood_group', label: 'Select Blood Groups' },
      { btn: 'btn-location', name: 'location', label: 'Select Locations' },
      { btn: 'btn-batch', name: 'batch_no', label: 'Select Batches' }
    ];

    mappings.forEach(map => {
      const checkboxes = document.querySelectorAll(`input[name='${map.name}[]']`);
      checkboxes.forEach(cb => {
        cb.addEventListener('change', () => updateLabel(map.btn, map.name, map.label));
      });
      updateLabel(map.btn, map.name, map.label);
    });
  });
</script>
</body>
</html>
