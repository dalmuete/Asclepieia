<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Plants - Plant Dictionary Admin</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background-image: radial-gradient(circle, #5C8374 1px, transparent 1px);
            background-size: 30px 30px;
            background-position: 0 0, 10px 10px; 
            background-color: #fff; 
            padding-bottom: 100px;
        }
        #logo{
            padding-top: 20px;
            width: 30%;
        }
        h2{
            padding-top: 20px;
            padding-bottom: 10px;
        }
        .list-group-item {
            cursor: pointer;
        }

        button {
            margin-top: 33px;
            margin-right: 20px;
        }

        #searchPlant {
            width: 100%;
        }

        .form-row {
            background-color: #fff;
            border: 1px solid rgba(0, 0, 0, 0.125);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        
        @media (min-width: 576px) {
            .form-row {
                max-width: 1500px; 
                margin: auto;
            }
        }

        .card-img-top {
            max-width: 100%;
            height: auto;
            display: block;
            margin: auto; 
        }

        #searchPlant {
            margin-bottom: 20px;
        }

        /* New styles for displaying top 10 most clicked plants */
        #topClickedContainer {
            overflow: hidden;
            position: relative;
        }

        #topClickedPlants {
            display: flex;
            gap: 20px;
            white-space: nowrap;
            animation: scrollAnimation 20s linear infinite;
            cursor: grab;
        }

        .top-plant-card {
            flex: 0 0 calc(30% - 20px);
            max-width: calc(30% - 20px);
            background-color: #fff;
            border: 1px solid rgba(0, 0, 0, 0.125);
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            padding: 10px;
            transition: transform 0.3s ease-out;
        }

        .top-plant-card img {
            max-width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 5px;
        }

        .top-plant-card h5 {
            margin-top: 10px;
            color: #007bff;
        }

        /* Animation for the most clicked plants */
        @keyframes scrollAnimation {
            0% {
                transform: translateX(0);
            }
            100% {
                transform: translateX(calc(-100% + (30% - 20px)));
            }
        }

        .top-plant-card:hover {
            transform: scale(1.1);
        }
        .scrollable-content {
            display: flex;
            gap: 20px;
            transition: transform 30s linear;
            animation: scrollAnimation 30s linear infinite;
            will-change: transform;
        }
    </style>
</head>
<body>
    <?php include("sidebar.php"); echo'<img id="logo" src="logo/logo.png" alt="Image"class="card-img-top mb-3">'?>
    <?php include("search_plants.php");
    ?>
    <div class="container mt-3">
        
        <?php
            $plantDataFile = 'plant_json.json';

            if (file_exists($plantDataFile)) {
                $jsonContent = file_get_contents($plantDataFile);
                $herbData = json_decode($jsonContent, true);

                if (is_array($herbData['herb'])) {
                    // Filter plants with clickCount key
                    $plantsWithClickCount = array_filter($herbData['herb'], function ($plant) {
                        return isset($plant['clickCount']);
                    });

                    // Sort plants by clickCount in descending order
                    usort($plantsWithClickCount, function ($a, $b) {
                        return $b['clickCount'] - $a['clickCount'];
                    });

                    if (!empty($plantsWithClickCount)) {
                        // Get the top 10 most clicked plants
                        $topClickedPlants = array_slice($plantsWithClickCount, 0, 10);

                        // Display the top 10 most clicked plants in a grid
                        echo '<div id="topClickedContainer" onmousedown="startDragging(event)" onmouseup="stopDragging()" onmouseleave="stopDragging()">';
                        echo '<h2>Most Viewed Plants</h2>';
                        echo '<div id="topClickedPlants" class="top-clicked-plants-container">';
                        foreach ($topClickedPlants as $plant) {
                            echo '<a href="manage_plants.php?plant=' . urlencode($plant['name']) . '" class="top-plant-card">';
                            echo '<img src="' . $plant['images'][0] . '" alt="' . $plant['name'] . '" class="card-img-top">';
                            echo '<h5 class="card-title">' . $plant['name'] . '</h5>';
                            echo '</a>';
                        }
                        echo '</div>';
                        echo '</div>';
                    }
                }
            }
            if (!empty($herbData['herb'])) {
                // Filter plants with commentCount key
                $plantsWithCommentCount = array_filter($herbData['herb'], function ($plant) {
                    return isset($plant['commentCount']);
                });
        
                // Sort plants by commentCount in descending order
                usort($plantsWithCommentCount, function ($a, $b) {
                    return $b['commentCount'] - $a['commentCount'];
                });
        
                if (!empty($plantsWithCommentCount)) {
                    // Get the top 5 most commented plants
                    $topCommentedPlants = array_slice($plantsWithCommentCount, 0, 5);
        
                    // Display the top 5 most commented plants in a horizontally scrollable grid
                    echo '<div id="topCommentedContainer" class="scrollable-container overflow-hidden">';
                    echo '<h2>Top 5 Most Commented Plants</h2>';
                    echo '<div id="topCommentedPlants" class="scrollable-content top-clicked-plants-container">';
                    foreach ($topCommentedPlants as $plant) {
                        echo '<a href="manage_plants.php?plant=' . urlencode($plant['name']) . '" class="top-plant-card">';
                        echo '<img src="' . $plant['images'][0] . '" alt="' . $plant['name'] . '" class="card-img-top">';
                        echo '<h5 class="card-title">' . $plant['name'] . '</h5>';
                        echo '<p>'. $plant['commentCount'] . ' comments</p>';
                        echo '</a>';
                    }
                    echo '</div>';
                    echo '</div>';
                }
            }
        ?>
<script>
    var isDragging = false;
    var initialMouseX, initialScrollX;
    var topClickedPlants = document.getElementById('topClickedPlants');
    var topClickedContainer = document.getElementById('topClickedContainer');

    function startDragging(event) {
        isDragging = true;
        initialMouseX = event.clientX;
        initialScrollX = topClickedPlants.scrollLeft;
        topClickedPlants.style.cursor = 'grabbing';

        // Disable animation during dragging
        topClickedPlants.style.transition = 'none';

        document.addEventListener('mousemove', handleDragging);
        document.addEventListener('mouseup', stopDragging);
    }

    function stopDragging() {
        isDragging = false;
        topClickedPlants.style.cursor = 'grab';

        // Re-enable animation after dragging
        topClickedPlants.style.transition = '';

        document.removeEventListener('mousemove', handleDragging);
        document.removeEventListener('mouseup', stopDragging);
    }

    // Add event listener for drag and drop on the container
    topClickedContainer.addEventListener('mousedown', function (event) {
        startDragging(event);
    });

    // Add a check for isDragging to prevent unwanted behavior on mouse move
    document.addEventListener('mousemove', function (event) {
        if (isDragging) {
            handleDragging(event);
        }
    });
</script>
    </div>
</body>
</html>
