<?php
include 'conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $first_name   = mysqli_real_escape_string($conn, $_POST['f_name']);
  $last_name    = mysqli_real_escape_string($conn, $_POST['l_name']);
  $user_name    = mysqli_real_escape_string($conn, $_POST['u_name']);
  $raw_password = $_POST['ps'];
  $password     = password_hash($raw_password, PASSWORD_DEFAULT); // Hashed!
  $email        = mysqli_real_escape_string($conn, $_POST['email']);
  $phone        = mysqli_real_escape_string($conn, $_POST['phn']);
  $reg_no       = mysqli_real_escape_string($conn, $_POST['reg_no']);
  $blood_group  = mysqli_real_escape_string($conn, $_POST['blood_group']);
  $location     = mysqli_real_escape_string($conn, $_POST['location']);
  $batch_no     = mysqli_real_escape_string($conn, $_POST['batch_no']);


  $role         = mysqli_real_escape_string($conn, $_POST['role']);

// Remove reg_no and batch_no if role is teacher
if ($role === 'teacher') {
  $reg_no = '';
  $batch_no = '';
}

 $image = 'blank_pp.png'; // default profile picture

  $is_approved = 0;

  $query = "INSERT INTO user_data 
    (first_name, last_name, user_name, password, email, phone, image, reg_no, is_approved, blood_group, location, batch_no, role)
    VALUES 
    ('$first_name', '$last_name', '$user_name', '$password', '$email', '$phone', '$image', '$reg_no', '$is_approved', '$blood_group', '$location', '$batch_no', '$role')";

  if (mysqli_query($conn, $query)) {
    echo "<script>alert('Registration Successful'); window.location='index.php';</script>";
  } else {
    echo "<script>alert('Registration Failed');</script>";
  }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=, initial-scale=1.0">
  <title>Document</title>
</head>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

<style>
  body {
  font-family: 'Inter', sans-serif;
  background: #f4f7fa;
  margin: 0;
  padding: 0;
}

.model_fade {
  
  max-width: 400px;
  margin: auto;
  margin-top: 100px;
  background-color: #fff;
  border-radius: 10px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.15);
  padding: 30px;
}

.modal-content {
  
  border: none;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
  margin: auto;
  max-width: 550px;
  width: 100%;
}

.modal-header {
  text-align: center;
  background: linear-gradient(135deg, #007bff, #00bcd4);
  color: white;
  padding: 16px 24px;
  border-bottom: none;
  position: relative;
}

.modal-header h4 {
  margin: 0;
  font-weight: 600;
}

.modal-header .close {
  position: absolute;
  right: 20px;
  top: 16px;
  font-size: 24px;
  color: #fff;
  opacity: 0.9;
}

.modal-body {
   border: 1px solid #f57c00;
  background-color: #ffffff;
  padding: 30px;
}

.form-group {
  margin-bottom: 20px;
}

.form-group label {
  font-weight: 600;
  margin-bottom: 6px;
  display: block;
  color: #333;
}

/* Shared styling for inputs and selects */
.form-control {
  border-radius: 8px;
  border: 1px solid #ccc;
  padding: 10px 14px;
  font-size: 15px;
  width: 100%;
  transition: border-color 0.3s;
  box-sizing: border-box;
  height: 42px; /* Uniform height for input/select */
}

/* Focus effect */
.form-control:focus {
  border-color: #007bff;
  outline: none;
  box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.15);
}

/* Consistent dropdown style */
select.form-control {
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
  background-color: white;
  background-image: url("data:image/svg+xml;utf8,<svg fill='gray' height='20' viewBox='0 0 24 24' width='20' xmlns='http://www.w3.org/2000/svg'><path d='M7 10l5 5 5-5z'/></svg>");
  background-repeat: no-repeat;
  background-position: right 10px center;
  background-size: 16px;
  padding-right: 35px;
  cursor: pointer;
}

.btn-success {
  
  background-color: #28a745;
  color: white;
  font-weight: 600;
  padding: 10px 18px;
  border-radius: 6px;
  transition: background-color 0.3s;
  border: none;
}

.btn-success:hover {
  background-color: #218838;
}

.btn-danger {
  
  background-color: #dc3545;
  color: white;
  font-weight: 600;
  padding: 10px 18px;
  border-radius: 6px;
  transition: background-color 0.3s;
  border: none;
}

.btn-danger:hover {
  background-color: #c82333;
}

.text-center {
  text-align: center;
}

.col-sm-4, .col-sm-8 {
  width: 100%;
  padding: 0;
}

.form-horizontal .form-group {
  display: flex;
  flex-direction: column;
}

@media (min-width: 576px) {
  .form-horizontal .form-group {
    flex-direction: row;
    align-items: center;
  }

  .form-horizontal .col-sm-4 {
    width: 35%;
    padding-right: 10px;
  }

  .form-horizontal .col-sm-8 {
    width: 65%;
  }

  .form-group .col-sm-offset-4 {
    margin-left: 35%;
  }

  .form-group .text-right {
    text-align: right;
  }
}

/* Center modal vertically and horizontally */
.modal-dialog {
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 100vh;
  margin: 0 auto;
}

.hidden-field {
  display: none !important;
}

</style>


<body>
  


<div class="modal_fade" id="signup" role="dialog">
  <div class="modal-dialog" style="max-width: 550px;">
    <div class="modal-content">
      
 <a href="javascript:history.back()" 
       class="btn btn-light position-absolute top-0 start-0 m-3 px-3 py-1 rounded-pill shadow-sm fw-semibold">
        🔙 Back
    </a>
      <div class="modal-header">
       
        <h4 class="modal-title">Fill the form to Sign Up</h4>
        
      </div>

      <div class="modal-body">
       
        <div id="registration_form" style="margin-top: 30px;">
          <form class="form-horizontal" method="post">
            <div class="form-group">
              <label class="col-sm-4 control-label">First Name:</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="f_name" required>
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-4 control-label">Last Name:</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="l_name" required>
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-4 control-label">User Name:</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="u_name" required>
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-4 control-label">Password:</label>
              <div class="col-sm-8">
                <input type="password" class="form-control" name="ps" required>
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-4 control-label">Email:</label>
              <div class="col-sm-8">
                <input type="email" class="form-control" name="email" required>
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-4 control-label">Phone:</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="phn" required>
              </div>
            </div>

        


            <div class="form-group">
              <label class="col-sm-4 control-label">Blood Group:</label>
              <div class="col-sm-8">
                <select class="form-control" name="blood_group">
                  <option value="">-- Select --</option>
                  <option value="A+">A+</option>
                  <option value="A-">A-</option>
                  <option value="B+">B+</option>
                  <option value="B-">B-</option>
                  <option value="O+">O+</option>
                  <option value="O-">O-</option>
                  <option value="AB+">AB+</option>
                  <option value="AB-">AB-</option>
                </select>
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-4 control-label">Location:</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="location">
              </div>
            </div>

           
           <div class="form-group">
  <label class="col-sm-4 control-label">Role:</label>
  <div class="col-sm-8">
    <select class="form-control" name="role" id="roleSelect" required onchange="toggleBatch()">
      <option value="">-- Select Role --</option>
      <option value="student">Student</option>
      <option value="teacher">Teacher</option>
    </select>
  </div>
</div>



<div class="form-group" id="batchField">
  <label class="col-sm-4 control-label">Batch No:</label>
  <div class="col-sm-8">
    <select class="form-control" name="batch_no">
      <option value="">-- Select --</option>
      <option value="15">15</option>
      <option value="16">16</option>
      <option value="17">17</option>
      <option value="18">18</option>
      <option value="19">19</option>
    </select>
  </div>
</div>


    <div class="form-group" id="regField">
  <label class="col-sm-4 control-label">Registration No:</label>
  <div class="col-sm-8">
    <input type="text" class="form-control" name="reg_no" >
  </div>
</div>
            <div class="form-group">
              <div class="col-sm-offset-4 col-sm-8 text-right">
                <button type="submit" class="btn btn-success">Sign Up</button>
                <button type="reset" class="btn btn-danger">Reset</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
</body>


<script>


function toggleBatch() {
  const role = document.getElementById('roleSelect').value;
  const batchField = document.getElementById('batchField');
  const regField = document.getElementById('regField');

  // Toggle Batch No
  batchField.classList.toggle('hidden-field', role !== 'student');

  // Toggle Registration No
  regField.classList.toggle('hidden-field', role === 'teacher');

  // Optional: reset values if hidden
  if (role === 'teacher') {
    batchField.querySelector('select').value = '';
    regField.querySelector('input').value = '';
  }
}

window.addEventListener('DOMContentLoaded', toggleBatch);


</script>


</html>