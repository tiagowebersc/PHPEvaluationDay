<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Housing</title>
    <link rel="stylesheet" href="style/style.css">
</head>

<body>
    <?php
    require_once 'database.php';
    $title = "";
    $address = "";
    $city = "";
    $pc = "";
    $area = "";
    $price = "";
    $photo = "";
    $type = "";
    $description = "";
    $error = "";
    $stored = false;
    if (isset($_POST['addHousingSubmit'])) {
        // get all the values
        $title = trim(htmlspecialchars($_POST['title']));
        $address = trim(htmlspecialchars($_POST['address']));
        $city = trim(htmlspecialchars($_POST['city']));
        $pc = trim(htmlspecialchars($_POST['pc']));
        $area = intval(trim(htmlspecialchars($_POST['area'])));
        $price = intval(trim(htmlspecialchars($_POST['price'])));
        $type = trim(htmlspecialchars($_POST['type']));
        $description = trim(htmlspecialchars($_POST['description']));
        // validate all mandatory fields
        if (empty($title)) $error = "Title must be informed!";
        else if (empty($address)) $error = "Address must be informed!";
        else if (empty($city)) $error = "City must be informed!";
        else if (empty($pc)) $error = "Postcode must be informed!";
        else if (strlen($pc) !== 4) $error = "Format incorrect of postcode!";
        else if (empty($area)) $error = "Surface area must be informed!";
        else if (!is_integer($area)) $error = "Surface area must be a number!";
        else if (empty($price)) $error = "Price must be informed!";
        else if (!is_integer($price)) $error = "Price must be a number!";
        else if (empty($type)) $error = "Type must be informed!";


        // if everthing is good store the data
        if (empty($error)) {
            $conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD);
            $dbFound = mysqli_select_db($conn, DB_NAME);
            if (!$dbFound) {
                $error = 'Problem to connect to the database!!';
            } else {
                // next housing id
                $query = "SELECT ifnull(max(id_housing),0)+1 as next FROM housing";
                $results = mysqli_query($conn, $query);
                $row = mysqli_fetch_assoc($results);
                $id = $row['next'];

                // check the image
                if (empty($error) && !empty($_FILES['photo']['name'])) {
                    if ($_FILES['photo']['error'] != UPLOAD_ERR_OK) {
                        $error = "Problem to upload the file selected!";
                    } else {
                        $extensionArray = ['jpg' => 'image/jpeg', 'png' => 'image/png', 'gif' => 'image/gif'];
                        $extFile = array_search($_FILES['photo']['type'], $extensionArray);
                        if ($extFile) {
                            $fullPath = './uploads/housing_' . $id . '.' . $extFile;
                            $moved = move_uploaded_file($_FILES['photo']['tmp_name'], $fullPath);
                            if ($moved)
                                $photo = $fullPath;
                            else
                                $error = 'Error while transfering the photo!';
                        } else {
                            $error = 'File isn\'t a image!';
                        }
                    }
                }
                if (empty($error)) {
                    $query = "INSERT INTO housing (id_housing,title,address,city,pc,area,price,photo,id_type,description) VALUES ($id,'$title','$address','$city','$pc',$area,$price,'$photo',$type,'$description')";
                    $results = mysqli_query($conn, $query);
                    if ($results) {
                        $stored = true;
                    } else {
                        $error = "Error storing the data!";
                    }
                }
            }
            mysqli_close($conn);
        }
    }
    if ($stored) {
        ?>
        <form class="elementCenter width22" action="" method="post">
            <span>Housing successfuly stored!</span>
            <input type="submit" value="OK">
        </form>
    <?php
    } else {
        ?>
        <form class="elementCenter width22" enctype="multipart/form-data" action="" method="post">
            <label for="title">Title:</label>
            <input type="text" name="title" id="title" placeholder="Enter the title" value="<?php echo $title; ?>">
            <label for="address">Address:</label>
            <textarea name="address" id="adress" placeholder="Enter the address"><?php echo $address; ?></textarea>
            <label for="city">City:</label>
            <input type="text" name="city" id="city" placeholder="Enter the city" value="<?php echo $city; ?>">
            <label for="pc">Postcode:</label>
            <input type="text" name="pc" id="pc" placeholder="Enter the pc" value="<?php echo $pc; ?>">
            <label for="area">Surface area:</label>
            <input type="number" name="area" id="area" placeholder="Enter the surface area" value="<?php echo $area; ?>">
            <label for="price">Price:</label>
            <input type="number" name="price" id="price" placeholder="Enter the price" value="<?php echo $price; ?>">
            <label for="photo">Select a photo:</label>
            <input type="hidden" name="MAX_FILE_SIZE" value="250000"> <!-- 250kb -->
            <input type="file" name="photo" id="photo">
            <label for="type">Type:</label>
            <select name="type">
                <?php
                $conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD);
                $dbFound = mysqli_select_db($conn, DB_NAME);
                if (!$dbFound) {
                    $error = 'Problem to connect to the database!!';
                } else {
                    $query = "SELECT * FROM typeHousing ORDER BY description";
                    $results = mysqli_query($conn, $query);
                    while ($row = mysqli_fetch_assoc($results)) {
                        echo '<option value="' . $row['id_type'] . '">' . $row['description'] . '</option>';
                    }
                }
                mysqli_close($conn);
                ?>
            </select>
            <label for="description">Description:</label>
            <textarea name="description" id="description" placeholder="Enter the description"><?php echo $description; ?></textarea>
            <span class="error"><?php echo $error; ?></span>
            <input type="submit" value="Add" name="addHousingSubmit">
        </form>
    <?php } ?>
</body>

</html>