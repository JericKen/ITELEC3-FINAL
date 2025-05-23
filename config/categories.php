<?php
require_once 'database.php';

try {
    // Default categories for the bookstore
    $categories = [
        [
            'name' => 'Fiction',
            'description' => 'Novels, short stories, and other fictional works'
        ],
        [
            'name' => 'Non-Fiction',
            'description' => 'Educational books, biographies, and reference materials'
        ],
        [
            'name' => 'Textbooks',
            'description' => 'Academic books for students of all levels'
        ],
        [
            'name' => 'School Supplies',
            'description' => 'Notebooks, pens, pencils, and other stationery items'
        ],
        [
            'name' => 'Art Supplies',
            'description' => 'Drawing materials, paints, and craft supplies'
        ],
        [
            'name' => 'Children\'s Books',
            'description' => 'Books for young readers and picture books'
        ],
        [
            'name' => 'Reference Books',
            'description' => 'Dictionaries, encyclopedias, and study guides'
        ],
        [
            'name' => 'Office Supplies',
            'description' => 'Folders, binders, and other office materials'
        ]
    ];

    // Insert categories if they don't exist
    $stmt = $pdo->prepare("INSERT IGNORE INTO categories (name, description) VALUES (?, ?)");
    
    foreach ($categories as $category) {
        $stmt->execute([$category['name'], $category['description']]);
    }

    echo "Categories have been set up successfully!";
} catch(PDOException $e) {
    die("Error setting up categories: " . $e->getMessage());
}
?> 