<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MKCE ERP Login</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <style>
    body { height: 100vh; margin: 0; display: flex; }
    .split-screen { display: flex; width: 100%; height: 100vh; }
    .left, .right { flex: 1; }
    .left {
      background: linear-gradient(135deg, #1e4d92, #3b82f6);
      display: flex; align-items: center; justify-content: center; flex-direction: column;
      color: white;
    }
    .right { background-color: #f8f9fa; display: flex; flex-direction: column; justify-content: space-between; }
    .login-container { max-width: 400px; margin: auto; padding: 30px; background: white; border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
    .btn-student { background-color: #3b82f6; color: white; }
    .btn-faculty { background-color: #1e4d92; color: white; }
    .btn-lostfaculty { background-color: #6c757d; color: white; }
    .footer { background: #1e4d92; color: white; padding: 10px; font-size: 0.9rem; }
    .recover-form { display: none; }
    .recover-form.active { display: block; }
    .login-tabs-content.hide { display: none; }
  </style>
</head>
<body>
  <div class="split-screen">
    <div class="left">
      <img src="erp3.png" alt="MKCE Logo" style="width: 350px;">
      <img src="image/erp7.png" alt="ERP Logo" style="width: 450px; height: 250px;">
    </div>

    <div class="right">
      <div class="login-container">
        <img src="image/mkcenew.png" alt="MKCE Logo" class="mx-auto d-block mb-4" width="120">

        <ul class="nav nav-pills mb-4" id="loginTabs">
          <li class="nav-item flex-fill"><button class="nav-link active w-100 btn-student" data-bs-toggle="pill" data-bs-target="#student">Student</button></li>
          <li class="nav-item flex-fill"><button class="nav-link w-100 btn-faculty" data-bs-toggle="pill" data-bs-target="#faculty">Faculty</button></li>
        </ul>

        <div class="tab-content">
          <!-- Student Login -->
          <div class="tab-pane fade show active" id="student">
            <form action="verify_login.php" method="POST">
              <input type="hidden" name="type" value="student">
              <div class="input-group mb-3">
                <span class="input-group-text"><i class="fas fa-user"></i></span>
                <input type="text" name="Userid" class="form-control" placeholder="Student ID" required>
              </div>
              <div class="input-group mb-3">
                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                <input type="password" name="pass" class="form-control" placeholder="Password" required>
              </div>
              <button type="submit" class="btn btn-student w-100">Login as Student</button>
            </form>
          </div>

          <!-- Faculty Login -->
          <div class="tab-pane fade" id="faculty">
            <form action="verify_login.php" method="POST">
              <input type="hidden" name="type" value="faculty">
              <div class="input-group mb-3">
                <span class="input-group-text"><i class="fas fa-user"></i></span>
                <input type="text" name="Userid" class="form-control" placeholder="Faculty ID" required>
              </div>
              <div class="input-group mb-3">
                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                <input type="password" name="pass" class="form-control" placeholder="Password" required>
              </div>
              <div class="input-group mb-3">
                <span class="input-group-text"><i class="fas fa-building"></i></span>
                <select name="selected_dept" class="form-control" id="deptDropdown">
                  <option value="">Select Department</option>
                </select>
              </div>
              <button type="submit" class="btn btn-faculty w-100 mb-2">Login as Faculty</button>
              <button type="button" id="to-recover" class="btn btn-lostfaculty w-100">Lost Password</button>
            </form>
          </div>
        </div>

        <!-- Recovery Form -->
        <div class="recover-form">
          <h4>Password Recovery</h4>
          <input type="text" id="fid" class="form-control mb-2" placeholder="Faculty ID">
          <input type="email" id="email" class="form-control mb-2" placeholder="Email">
          <button id="to-login" class="btn btn-secondary w-100 mb-2">Back</button>
          <button id="sendEmailButton" class="btn btn-primary w-100">Recover Password</button>
        </div>
      </div>

      <footer class="footer text-center">
        <p class="mb-0">© 2025 Technology Innovation Hub - MKCE</p>
      </footer>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    $(document).ready(function () {
      $("#to-recover").click(() => { $(".login-tabs-content").addClass("hide"); $(".recover-form").addClass("active"); });
      $("#to-login").click(() => { $(".login-tabs-content").removeClass("hide"); $(".recover-form").removeClass("active"); });
    });
  </script>
</body>
</html>
