<h2>Upload Business Card</h2>

<form method="post" enctype="multipart/form-data" id="cardForm" action="form_handler.php">
    <label>Full Name:</label><br>
    <input type="text" name="name" id="nameField" required>
    <br><br>

    <label>Select an image of the business card:</label><br><br>
    <input type="file" name="business_card" id="cardInput" accept="image/*" required>
    <br><br>

    <div id="statusMsg"></div>

    <button type="submit" name="submit_card">Submit</button>
</form>

<script>
document.getElementById('cardInput').addEventListener('change', function () {
    const file = this.files[0];
    if (!file) return;

    const formData = new FormData();
    formData.append('business_card', file);
    console.log("formData:", formData);

    document.getElementById('statusMsg').innerText = 'Uploading and analyzing...';

    fetch('../src/form_handler.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        console.log("Azure Data:", data)
        if (data.fullName) {
            document.getElementById('nameField').value = data.fullName;
        }
        document.getElementById('statusMsg').innerText = 'Data extracted successfully!';
    })
    .catch(err => {
        console.error("Fetch failed:", err);
        document.getElementById('statusMsg').innerText = 'Failed to extract data.';
    });
});
</script>
