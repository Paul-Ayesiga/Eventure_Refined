<?php
// Check if Imagick extension is loaded
if (extension_loaded('imagick')) {
    echo "✅ Imagick extension is loaded.\n";
    
    // Check Imagick version
    $imagick = new Imagick();
    echo "Imagick version: " . $imagick->getVersion()['versionString'] . "\n";
    
    // Check if we can create a simple image
    try {
        $image = new Imagick();
        $image->newImage(100, 100, new ImagickPixel('white'));
        $image->setImageFormat('png');
        echo "✅ Successfully created a test image.\n";
    } catch (Exception $e) {
        echo "❌ Error creating test image: " . $e->getMessage() . "\n";
    }
} else {
    echo "❌ Imagick extension is NOT loaded.\n";
    
    // Show loaded extensions for reference
    echo "\nLoaded extensions:\n";
    $extensions = get_loaded_extensions();
    sort($extensions);
    echo implode(", ", $extensions) . "\n";
}
?>
