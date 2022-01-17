<?php
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Uudised</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h2>RSS uudised postimees.ee lehelt</h2>
<ul>
    <?php
    $feed= simplexml_load_file("https://lenta.ru/rss");
    $linkide_arv=3;
    $loendur=1;
    foreach ($feed->channel->item as $item){
        if ($loendur<=$linkide_arv){
            echo "<li>";
            echo "<a href='$item->link' target='_blank'>$item->title</a>";
            echo "</li>";
            $loendur++;
        }
    }


    ?>
</body>
</html>
