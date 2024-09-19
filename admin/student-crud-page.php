<?php
global $wpdb;
$table_name = $wpdb->prefix . 'students';

// Handle form submission for creating/updating a student
if (isset($_POST['submit'])) {
    $name = sanitize_text_field($_POST['name']);
    $email = sanitize_email($_POST['email']);
    $age = intval($_POST['age']);

    if (isset($_POST['id']) && $_POST['id'] != '') {
        // Update student
        $wpdb->update(
            $table_name,
            array('name' => $name, 'email' => $email, 'age' => $age),
            array('id' => intval($_POST['id']))
        );
    } else {
        // Insert new student
        $wpdb->insert(
            $table_name,
            array('name' => $name, 'email' => $email, 'age' => $age)
        );
    }
}

// Delete a student
if (isset($_GET['delete'])) {
    $wpdb->delete($table_name, array('id' => intval($_GET['delete'])));
}

// Fetch all students
$students = $wpdb->get_results("SELECT * FROM $table_name");

?>

<div class="wrap">
    <h1>Student Management</h1>

    <form method="post">
        <input type="hidden" name="id" value="<?php echo isset($_GET['edit']) ? intval($_GET['edit']) : ''; ?>">
        <table class="form-table">
            <tr>
                <th>Name</th>
                <td><input type="text" name="name" required value="<?php echo isset($_GET['edit']) ? esc_attr($wpdb->get_var("SELECT name FROM $table_name WHERE id = " . intval($_GET['edit']))) : ''; ?>"></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><input type="email" name="email" required value="<?php echo isset($_GET['edit']) ? esc_attr($wpdb->get_var("SELECT email FROM $table_name WHERE id = " . intval($_GET['edit']))) : ''; ?>"></td>
            </tr>
            <tr>
                <th>Age</th>
                <td><input type="number" name="age" required value="<?php echo isset($_GET['edit']) ? esc_attr($wpdb->get_var("SELECT age FROM $table_name WHERE id = " . intval($_GET['edit']))) : ''; ?>"></td>
            </tr>
        </table>
        <p>
            <input type="submit" name="submit" class="button-primary" value="<?php echo isset($_GET['edit']) ? 'Update' : 'Add'; ?> Student">
        </p>
    </form>

    <h2>Existing Students</h2>
    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Age</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($students as $student) : ?>
                <tr>
                    <td><?php echo esc_html($student->id); ?></td>
                    <td><?php echo esc_html($student->name); ?></td>
                    <td><?php echo esc_html($student->email); ?></td>
                    <td><?php echo esc_html($student->age); ?></td>
                    <td>
                        <a href="?page=student-crud&edit=<?php echo esc_attr($student->id); ?>">Edit</a> | 
                        <a href="?page=student-crud&delete=<?php echo esc_attr($student->id); ?>" onclick="return confirm('Are you sure?');">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
