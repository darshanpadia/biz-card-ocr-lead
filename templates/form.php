<h2>Upload Business Card</h2>

<form method="post" enctype="multipart/form-data" id="cardForm">
    <label>Full Name:</label><br>
    <input type="text" name="name" id="nameField" required>
    <br><br>

    <label>Select an image of the business card:</label><br><br>
    <input type="file" name="business_card" id="cardInput" accept="image/*" required>
    <br><br>

    <div id="statusMsg"></div>

    <button type="submit" name="submit_card">Submit</button>
</form>

<?php
// This block ensures admin_url is rendered correctly
$ajax_url = admin_url('admin-ajax.php');
?>

<script>
document.addEventListener('DOMContentLoaded', function () {
    console.log("Script loaded");

    const cardInput = document.getElementById('cardInput');
    const nameField = document.getElementById('nameField');
    const statusMsg = document.getElementById('statusMsg');

    const ajaxUrl = "<?php echo esc_url($ajax_url); ?>";
    console.log("AJAX URL:", ajaxUrl);

    cardInput.addEventListener('change', function () {
        const file = this.files[0];
        if (!file) {
            console.log("No file selected.");
            return;
        }

        console.log("File selected:", file.name);

        const formData = new FormData();
        formData.append('business_card', file);
        formData.append('action', 'bcol_handle_card_upload');

        statusMsg.innerText = 'Uploading and analyzing...';
        console.log("Sending request to:", ajaxUrl);

        fetch(ajaxUrl, {
            method: 'POST',
            body: formData
        })
        .then(res => {
            console.log("Raw response:", res);
            return res.json();
        })
        .then(data => {
            console.log("Azure Data:", data);
            if (data.fullName) {
                nameField.value = data.fullName;
            }
            statusMsg.innerText = 'Data extracted successfully!';
        })
        .catch(async (err) => {
            console.error("Fetch failed:", err);
            statusMsg.innerText = 'Failed to extract data.';
        });
    });
});
</script>
