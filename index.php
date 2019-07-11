<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>List of houses</title>
    <link rel="stylesheet" href="style/style.css">
</head>

<body>
    <?php
    require_once 'database.php';
    $error = "";
    ?>
    <section class="elementCenter width46">
        <span class="error"><?php echo $error; ?></span>
        <?php
        $conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD);
        $dbFound = mysqli_select_db($conn, DB_NAME);
        if (!$dbFound) {
            $error = 'Problem to connect to the database!!';
        } else {
            $query = "SELECT h.title, h.photo, h.address, h.city, h.pc, h.area, h.price, t.description as type, h.description FROM housing h INNER JOIN typeHousing t on h.id_type = t.id_type ORDER BY h.title";
            $results = mysqli_query($conn, $query);
            while ($row = mysqli_fetch_assoc($results)) {
                ?>
                <article>
                    <img src="<?php echo $row['photo']; ?>" alt="<?php echo $row['title']; ?>">
                    <ul>
                        <li>
                            <h2><?php echo $row['title']; ?></h2>
                        </li>
                        <li><strong>Address:</strong><?php echo strlen($row['address']) > 50 ? substr($row['address'], 0, 50) . '...' : $row['address']; ?></li>
                        <li><strong>City:</strong><?php echo $row['city']; ?></li>
                        <li><strong>Postcode:</strong><?php echo $row['pc']; ?></li>
                        <li><strong>Surface area:</strong><?php echo $row['area']; ?></li>
                        <li><strong>Price:</strong><?php echo $row['price']; ?>€</li>
                        <li><strong>Type:</strong><?php echo $row['type']; ?></li>
                        <li><strong>Description:</strong><?php echo strlen($row['description']) > 50 ? substr($row['description'], 0, 50) . '...' : $row['description'];; ?></li>
                    </ul>
                </article>
                <br>
            <?php
            }
        }
        mysqli_close($conn);
        ?>
    </section>
</body>

</html>