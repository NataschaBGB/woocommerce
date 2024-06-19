<?php
    /* Template name: Animal Table */
    
    get_header();

    global $wpdb;
    $table_name = "animals";
    // find prefix in phpMyadmin db name
    $table_name = $wpdb->prefix .$table_name;

    // $count = $wpdb->query("SELECT COUNT(*) FROM $table_name");
    // echo "<pre>";
    // var_dump($count);
    // echo "</pre>";
?>

<body <?php body_class(); ?>>
    <div class="animals">
        <div class="container">
        <!-- WHERE TO PUT CSS -->
            <h1>Create Animal</h1>

            <div id="confirmation">
                <h2>Are you sure you want to delete this animal?</h2>
                <button class="confirm_delete">Yes</button>
                <button class="cancel_delete">No</button>
            </div>
            
            <div id="error_create"></div>

            <div id="new_animal">
                <label>Species</label>
                <input type="text" class="species" name="species">
                <label>Race</label>
                <input type="text" class="race" name="race">
                <label>Color</label>
                <input type="color" class="color" name="color">
                <label>Gender</label>
                <select class="gender" name="gender">
                    <option value="select_gender" disabled selected>select gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
                <label>Birthdate</label>
                <input type="date" class="birthdate" name="birthdate">
                <button class="create_animal">Create</button>
            </div>

            <hr>
            <hr>

            <div class="display_animals">

                <h1>Animals</h1>

                <div id="error_update"></div>

                <table>
                    <thead>
                        <tr>
                            <th>Species</th>
                            <th>Race</th>
                            <th>Color</th>
                            <th>Gender</th>
                            <th>Birthdate</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            // get all rows in db and DESC order (makes sure all new animals is at top of list)
                            $animals = $wpdb->get_results("SELECT * FROM $table_name ORDER BY created_at DESC");
                            // if db is not empty - loop through all rows and display in table
                            if($animals) {
                                foreach ($animals as $key => $animal) { ?>
                                    <tr attr-animal_id="<?php echo $animal->id ?>">
                                        <td>
                                            <input type="text" class="species_id" value="<?php echo $animal->species ?>">
                                        </td>
                                        <td>
                                            <input type="text" class="race_id" value="<?php echo $animal->race ?>">
                                        </td>
                                        <td>
                                            <input type="color" class="color_id" value="<?php echo $animal->color ?>">
                                        </td>
                                        <td>
                                            <select name="gender" class="gender_id">
                                                <?php if(!$animal->gender) { ?>
                                                    <option value="#">#</option>
                                                <?php } ?>
                                                <option value="Male" <?php echo $animal->gender=='Male'?"selected":""?>>Male</option>
                                                <option value="Female" <?php echo $animal->gender=='Female'?"selected":""?>>Female</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="date" class="birthdate_id" value="<?php echo $animal->birthdate ?>">
                                        </td>
                                        <td>
                                            <button class="animal_update">Update</button>
                                            <button class="animal_delete">Delete</button>
                                        </td>
                                    </tr>
                                <?php }
                            }
                        ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</body>

<?php
    get_footer();
?>