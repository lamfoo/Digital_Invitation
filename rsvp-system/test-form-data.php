<?php
// Quick test script to debug form submission
// Place this in your Laravel public directory and access via browser

echo "<h2>Form Data Test</h2>";

if ($_POST) {
    echo "<h3>POST Data Received:</h3>";
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
    
    echo "<h3>Specific Fields:</h3>";
    echo "rsvp_status: " . ($_POST['rsvp_status'] ?? 'NOT SET') . "<br>";
    echo "_token: " . ($_POST['_token'] ?? 'NOT SET') . "<br>";
    
} else {
    echo "<p>No POST data received. Testing form:</p>";
}
?>

<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <input type="hidden" name="_token" value="test-token">
    
    <h3>Test Form:</h3>
    <button type="submit" name="rsvp_status" value="yes">Test Yes</button>
    <button type="submit" name="rsvp_status" value="no">Test No</button>
    <button type="submit" name="rsvp_status" value="maybe">Test Maybe</button>
</form>

<hr>

<h3>Alternative Form Test:</h3>
<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <input type="hidden" name="_token" value="test-token">
    <input type="hidden" name="rsvp_status" value="yes">
    <button type="submit">Test with Hidden Field</button>
</form>