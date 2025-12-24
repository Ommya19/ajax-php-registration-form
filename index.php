<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>AJAX Form Management</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; padding: 30px; background: #f4f7f6; }
        .container { max-width: 800px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 15px; }
        label { display: block; font-weight: bold; margin-bottom: 5px; }
        input[type="text"], textarea { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .error { color: #d9534f; font-size: 13px; margin-top: 5px; display: block; }
        button { background: #5cb85c; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; }
        button:hover { background: #4cae4c; }
        table { width: 100%; border-collapse: collapse; margin-top: 30px; background: #fff; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #f8f8f8; }
        img { border-radius: 4px; object-fit: cover; }
    </style>
</head>
<body>

<div class="container">
    <h2>User Information Form</h2>
    <form id="infoForm">
        <input type="hidden" id="userId" name="userId">
        
        <div class="form-group">
            <label>Name</label>
            <input type="text" id="name" name="name">
            <span id="nameErr" class="error"></span>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="text" id="email" name="email">
            <span id="emailErr" class="error"></span>
        </div>

        <div class="form-group">
            <label>Address</label>
            <textarea id="address" name="address" rows="3"></textarea>
            <span id="addressErr" class="error"></span>
        </div>

        <div class="form-group">
            <label>Phone</label>
            <input type="text" id="phone" name="phone">
            <span id="phoneErr" class="error"></span>
        </div>

        <div class="form-group">
            <label>Upload Image</label>
            <input type="file" id="image" name="image" accept="image/*">
            <span id="imageErr" class="error"></span>
        </div>

        <button type="submit" id="submitBtn">Save Information</button>
    </form>

    <table id="dataTable">
        <thead>
            <tr>
                <th>Photo</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<script>
$(document).ready(function() {
    fetchData(); // Load data on page load

    // Form Validation and Submission
    $("#infoForm").on("submit", function(e) {
        e.preventDefault();
        if (validate()) {
            let formData = new FormData(this);
            $.ajax({
                url: 'server.php?action=save',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {
                    alert(res);
                    $("#infoForm")[0].reset();
                    $("#userId").val("");
                    $("#submitBtn").text("Save Information");
                    fetchData();
                }
            });
        }
    });

    function validate() {
        let valid = true;
        $(".error").text("");
        
        if($("#name").val().length < 2) { $("#nameErr").text("Enter a valid name"); valid = false; }
        if(!/^\b[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}\b$/i.test($("#email").val())) { $("#emailErr").text("Invalid email format"); valid = false; }
        if($("#address").val().trim() == "") { $("#addressErr").text("Address is required"); valid = false; }
        if(!/^\d{10}$/.test($("#phone").val())) { $("#phoneErr").text("Phone must be 10 digits"); valid = false; }
        
        return valid;
    }

    function fetchData() {
        $.getJSON('server.php?action=list', function(data) {
            let rows = '';
            $.each(data, function(i, item) {
                rows += `<tr>
                    <td><img src="${item.image_path}" width="50" height="50"></td>
                    <td>${item.name}</td>
                    <td>${item.email}</td>
                    <td>${item.phone}</td>
                    <td><button onclick='editRow(${JSON.stringify(item)})' style="background:#0275d8">Edit</button></td>
                </tr>`;
            });
            $("#dataTable tbody").html(rows);
        });
    }

    window.editRow = function(data) {
        $("#userId").val(data.id);
        $("#name").val(data.name);
        $("#email").val(data.email);
        $("#address").val(data.address);
        $("#phone").val(data.phone);
        $("#submitBtn").text("Update Information");
        window.scrollTo(0,0);
    }
});
</script>
</body>
</html>