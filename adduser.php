<?php
// Clear the screen
function clearScreen() {
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        system('cls');
    } else {
        system('clear');
    }
}

// Print a message in green
function printGreen($message) {
    echo "\033[1;32m$message\033[0m\n";
}

// Extract user ID from referral link
function extractReferralId($link) {
    if (preg_match('/startapp=f(\d+)/', $link, $matches)) {
        return $matches[1];
    }
    return false;
}

// Save user ID to file
function saveUserId($usersFile, $userId) {
    $users = file_exists($usersFile) ? json_decode(file_get_contents($usersFile), true) : [];
    if (isset($users[$userId])) {
        printGreen("Error: ID already saved!");
        printGreen("User ID: {$userId}\nSaved At: {$users[$userId]['saved_at']}");
        return $users;
    }

    $users[$userId] = [
        'tg_id' => $userId,
        'saved_at' => date('Y-m-d H:i:s')
    ];
    file_put_contents($usersFile, json_encode($users, JSON_PRETTY_PRINT));
    printGreen("Success: ID saved!");
    return $users;
}

// Display all saved user IDs
function displaySavedUsers($users) {
    if (empty($users)) {
        printGreen("No IDs saved .");
    } else {
        printGreen("\nSaved IDs:");
        foreach ($users as $id => $data) {
            echo "User ID: $id | Saved At: {$data['saved_at']}\n";
        }
    }
}

// Main script starts here
clearScreen();
printGreen(". Open Not Pixel");
printGreen(". Copy Not Pixel referral link");
printGreen(". Unlimited accounts supported");

$usersFile = 'users.json';

while (true) {
    printGreen("\nSend Not Pixel referral link:");
    $referralLink = trim(fgets(STDIN));

    $userId = extractReferralId($referralLink);
    if (!$userId) {
        printGreen("Error: Invalid referral link! Please try again.");
        continue;
    }

    $users = saveUserId($usersFile, $userId);

    printGreen("Do you want to save more referral links? (y/n):");
    $continue = strtolower(trim(fgets(STDIN)));

    if ($continue !== 'y') {
        break;
    }
}

// Final output
clearScreen();
$users = file_exists($usersFile) ? json_decode(file_get_contents($usersFile), true) : [];
displaySavedUsers($users);

printGreen("\nThank you for using the script!");
