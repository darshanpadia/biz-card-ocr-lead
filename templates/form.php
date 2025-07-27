<h2>Upload Business Card</h2>
<form method="post" enctype="multipart/form-data">
    <label>Full Name:</label><br>
    <input type="text" name="name" value="<?= isset($lead['fullName']) ? htmlspecialchars($lead['fullName']) : '' ?>" required>
    <br><br>

    <label>Select an image of the business card:</label><br><br>
    <input type="file" name="business_card" accept="image/*" required>
    <br><br>
    <button type="submit" name="submit_card">Submit</button>
</form>


<?php
$lead = []; // Ensure it's defined early

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['business_card']) && isset($_POST['submit_card'])) {
    $tmpPath = $_FILES['business_card']['tmp_name'];

    // Step 1: Send to Azure
    $operationUrl = sendToAzure($tmpPath, $apiKey, $modelUrl);

    // Step 2: Get Parsed Fields
    $fields = getAzureResult($operationUrl, $apiKey);

    // Step 3: Build Lead Object
    $lead = [
        'fullName' => trim(
            ($fields['ContactNames']['valueArray'][0]['valueObject']['FirstName']['content'] ?? '') . ' ' .
            ($fields['ContactNames']['valueArray'][0]['valueObject']['LastName']['content'] ?? '')
        ),
        'email' => $fields['Emails']['valueArray'][0]['content'] ?? '',
        'phone' => $fields['MobilePhones']['value'][0]['valuePhoneNumber']
            ?? $fields['WorkPhones']['valueArray'][0]['valuePhoneNumber']
            ?? '',
        'company' => $fields['CompanyNames']['valueArray'][0]['content'] ?? '',
        'address' => $fields['Addresses']['valueArray'][0]['content'] ?? '',
    ];

    // Debug output
    printDebug("Parsed Lead", $lead);
}
?>

<!-- HTML Form After PHP Parsing -->
<h2>Upload Business Card</h2>
<form method="post" enctype="multipart/form-data">
    <label>Full Name:</label><br>
    <input type="text" name="name" value="<?= isset($lead['fullName']) ? htmlspecialchars($lead['fullName']) : '' ?>" required>
    <br><br>

    <label>Select an image of the business card:</label><br><br>
    <input type="file" name="business_card" accept="image/*" required>
    <br><br>
    <button type="submit" name="submit_card">Submit</button>
</form>

