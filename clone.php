<?php
session_start(); // Ensure session is started

// Clear session on a page refresh (GET request) if session variables are set
if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_SESSION['show_grade_form'])) {
    session_unset();  // Clear all session variables
    session_destroy();  // Destroy the session
    session_start();    // Start a new session
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['submit_student'])) {
        // Store student details in session variables
        $_SESSION['first_name'] = $_POST['first_name'];
        $_SESSION['last_name'] = $_POST['last_name'];
        $_SESSION['age'] = $_POST['age'];
        $_SESSION['gender'] = $_POST['gender'];
        $_SESSION['course'] = $_POST['course'];
        $_SESSION['email'] = $_POST['email'];

        // Set flag to show grade entry form
        $_SESSION['show_grade_form'] = true;

        // Redirect to the same page to refresh and avoid resubmission issues
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } elseif (isset($_POST['submit_grade'])) {
        // Retrieve grades from the form
        $prelim = $_POST['prelim'];
        $midterm = $_POST['midterm'];
        $finals = $_POST['finals'];

        // Calculate the average
        $average = round(($prelim + $midterm + $finals) / 3, 2);

        // Determine if the student passed or failed
        $status = $average >= 75 ? "Passed" : "Failed";

        // Store grades and result in session
        $_SESSION['prelim'] = $prelim;
        $_SESSION['midterm'] = $midterm;
        $_SESSION['finals'] = $finals;
        $_SESSION['average'] = $average;
        $_SESSION['status'] = $status;

        // Redirect to the same page to avoid resubmission issues
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Enrollment & Grades</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

  <div class="container my-5">
    <h1 class="text-center mb-4">Student Enrollment and Grades Processing</h1>

    <?php if (!isset($_SESSION['show_grade_form'])): ?>
      <!-- Enrollment Form -->
      <form action="" method="post" class="bg-white p-4 rounded shadow">
        <h3 class="mb-3">Student Enrollment Form</h3>

        <div class="mb-3">
          <label for="first_name" class="form-label">First Name:</label>
          <input type="text" id="first_name" name="first_name" class="form-control" required>
        </div>

        <div class="mb-3">
          <label for="last_name" class="form-label">Last Name:</label>
          <input type="text" id="last_name" name="last_name" class="form-control" required>
        </div>

        <div class="mb-3">
          <label for="age" class="form-label">Age:</label>
          <input type="number" id="age" name="age" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Gender:</label><br>
          <div class="form-check form-check-inline">
            <input type="radio" id="male" name="gender" value="male" class="form-check-input" required>
            <label for="male" class="form-check-label">Male</label>
          </div>
          <div class="form-check form-check-inline">
            <input type="radio" id="female" name="gender" value="female" class="form-check-input" required>
            <label for="female" class="form-check-label">Female</label>
          </div>
        </div>

        <div class="mb-3">
          <label for="course" class="form-label">Course:</label>
          <select id="course" name="course" class="form-select" required>
            <option value="" disabled selected>Select a course</option>
            <option value="BSIT">BSIT</option>
            <option value="BSBA">BSBA</option>
            <option value="BSHM">BSHM</option>
          </select>
        </div>

        <div class="mb-3">
          <label for="email" class="form-label">Email:</label>
          <input type="email" id="email" name="email" class="form-control" required>
        </div>

        <button type="submit" name="submit_student" class="btn btn-primary w-10">Submit Student Information</button>
      </form>

    <?php elseif (!isset($_SESSION['show_grade_form'])): ?>
      <!-- Grade Entry Form -->
      <form action="" method="post" class="bg-white p-4 rounded shadow mt-4">
        <h3 class="mb-3">Enter Grades for <?php echo $_SESSION['first_name'] . ' ' . $_SESSION['last_name']; ?></h3>

        <div class="mb-3">
          <label for="prelim" class="form-label">Prelim:</label>
          <input type="number" id="prelim" name="prelim" class="form-control" required min="0" max="100">
        </div>

        <div class="mb-3">
          <label for="midterm" class="form-label">Midterm:</label>
          <input type="number" id="midterm" name="midterm" class="form-control" required min="0" max="100">
        </div>

        <div class="mb-3">
          <label for="finals" class="form-label">Finals:</label>
          <input type="number" id="finals" name="finals" class="form-control" required min="0" max="100">
        </div>

        <button type="submit" name="submit_grade" class="btn btn-success w-10">Submit Grade</button>
      </form>

    <?php elseif ($_SESSION['submit_grade']): ?>
      <!-- Display Student and Grades Summary -->
      <div class="bg-white p-4 rounded shadow mt-4">
        <h3>Student Details</h3>
        <p><strong>First Name:</strong> <?php echo $_SESSION['first_name']; ?></p>
        <p><strong>Last Name:</strong> <?php echo $_SESSION['last_name']; ?></p>
        <p><strong>Age:</strong> <?php echo $_SESSION['age']; ?></p>
        <p><strong>Course:</strong> <?php echo $_SESSION['course']; ?></p>
        <p><strong>Email:</strong> <?php echo $_SESSION['email']; ?></p>

        <h3 class="mt-4">Grades Summary</h3>
        <p><strong>Prelim:</strong> <?php echo $_SESSION['prelim']; ?></p>
        <p><strong>Midterm:</strong> <?php echo $_SESSION['midterm']; ?></p>
        <p><strong>Finals:</strong> <?php echo $_SESSION['finals']; ?></p>
        <p><strong>Average Grade</strong></p>
        <p><strong>Average:</strong> <?php echo $_SESSION['average']; ?>
          <span class="<?php echo $_SESSION['status'] == 'Passed' ? 'text-success' : 'text-danger'; ?>">
            (<?php echo $_SESSION['status']; ?>)
          </span>
        </p>
      </div>
    <?php endif; ?>
  </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>