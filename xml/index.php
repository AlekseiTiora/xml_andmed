<?php
$kaubad=simplexml_load_file('andmed.xml');
//otsing nimetuse järgi

function search_ByName($query){
    global $kaubad;
    $result=array();
    foreach ($kaubad->kaup as $kaup) {


        if (substr(strtolower($kaup->nimetus), 0,strlen($query))==
        strtolower($query)){
            array_push($result, $kaup);
        }
    }
    return $result;
}
//andmebaas lisamine xml-1
if (isset($_POST['submit'])){
    $xmlDoc=new DOMDocument("1.0", "UTF-8");


    $nimetus=$_POST['nimi'];
    $hind=$_POST['hind'];
    $aasta=$_POST['aasta'];
    $grupinimi=$_POST['grupinimi'];
    $kirjeldus=$_POST['kirjeldus'];

    $xml_kaubad=$kaubad->addChild('kaup');
    $xml_kaubad->addChild('nimetus', $nimetus);
    //addchild('xml struktuur', $nimi - tekst väli)
    $xml_kaubad->addChild('hind', $hind);
    $xml_kaubad->addChild('aasta', $aasta);

    $xml_kaubagrupp=$xml_kaubad->addChild('kaubagrupp');
    $xml_kaubagrupp->addChild('grupinimi',$grupinimi );
    $xml_kaubagrupp->addChild('kirjeldus',$kirjeldus );


    $xmlDoc->loadXML($kaubad->asXML(), LIBXML_NOBLANKS);
    $xmlDoc -> preserveWhiteSpace=false;
    $xmlDoc->formatOutput = true;
    $xmlDoc->save('andmed.xml');
    header("refresh: 0;");
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>XML andmed</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>XML failist andmed</h1>
    <form action="?" method="post">
        <label for="otsing">Otsing</label>
        <input type="text" name="otsing" id="otsing" placeholder="nimetus">
        <input type="submit" value="otsi" name="otsi">
    </form>
    <?php
    if (!empty($_POST['otsing'])){
        $result=search_ByName($_POST['otsing']);
        foreach ($result as $kaup){
            echo"<li>".$kaup->nimetus;
            echo ", ".$kaup->aasta.", ";
            echo $kaup->hind.", ";
            echo $kaup->kaubagrupp->grupinimi."</li>";

        }
    }
    ?>
    <table class="table">
        <tr>
            <th>Kaubanimetus</th>
            <th>Hind</th>
            <th>Väljastamise aasta</th>
            <th>Kaubagrupp</th>
            <th>Kirjeldus</th>
        </tr>
        <?php
        foreach ($kaubad->kaup as $kaup){
            echo"<tr>";
            echo"<td>". ($kaup->nimetus). "</td>";
            echo"<td>". ($kaup->hind). "</td>";
            echo"<td>". ($kaup->aasta). "</td>";
            echo"<td>". ($kaup->kaubagrupp->grupinimi). "</td>";
            echo"<td>". ($kaup->kaubagrupp->kirjeldus). "</td>";

            echo"</tr>";
        }
        ?>
    </table>
        <form action="" method="post">
            <h1>Andmete lisamine xml faili sisse</h1>
            <table>
                <tr>
                    <td><label for="nimi">Kauba nimetus</label></td>
                    <td><input for="text" id="nimi" name="nimi"></td>

                </tr>
                <tr>
                    <td><label for="hind">hind</label></td>
                    <td><input for="text" id="hind" name="hind"></td>

                </tr>
                <tr>
                    <td><label for="aasta">aasta</label></td>
                    <td><input for="text" id="aasta" name="aasta"></td>

                </tr>
                <tr>
                    <td><label for="grupinimi">grupinimi</label></td>
                    <td><input for="text" id="grupinimi" name="grupinimi"></td>

                </tr>
                <tr>
                    <td><label for="kirjeldus">kirjeldus</label></td>
                    <td><input for="text" id="kirjeldus" name="kirjeldus"></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="submit" value="lisa" name="submit">
                    </td>
                </tr>
            </table>
        </form>
<h1>RSS - Rich Summary Site / ükse xml andmevormingust uudised</h1>
<h2>RSS uudised postimees.ee lehelt</h2>
<ul>
    <?php
    $feed= simplexml_load_file("https://rus.err.ee/rss");
    $linkide_arv=10;
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

</ul>

</body>
</html>