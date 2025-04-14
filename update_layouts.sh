#!/bin/bash

# Find all blade files in the organisation directory
files=$(find ./resources/views/organisation -name "*.blade.php" | xargs grep -l "x-layouts.organiser")

# Loop through each file and replace the layout tags
for file in $files; do
  echo "Updating $file"
  sed -i '' 's/<x-layouts.organiser/<x-layouts.organisation/g' "$file"
  sed -i '' 's/<\/x-layouts.organiser>/<\/x-layouts.organisation>/g' "$file"
done

echo "All files updated successfully!"
