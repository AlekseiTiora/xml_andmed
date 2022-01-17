<?php
$temp=simplexml_load_file("temp.xml");
//osting nimituse järgi
function searchByName($query){
    global $temp;
    $result=array();
    foreach ($temp->temp as $tempe){
        if(substr(strtolower($tempe->temperatuur),0,strlen($query))==
            strtolower($query)){
            array_push($result,$tempe);
        }
    }
    return $result;
}


//andemete lisamine xml-i
if(isset($_POST['submit'])){
    $xmlDoc= new DOMDocument("1.0","UTF-8");

    $temp=$_POST['tempe'];
    $date=$_POST['date'];
    $maanimi=$_POST['maanimi'];
    $maakeskus=$_POST['maakeskus'];


    $xml_temp=$temp->addChild('temp');
    $xml_temp->addChild('temperatuur',$temp);
    //addchild('xml struktuur',$nimi-tekst väli)
    $xml_temp->addChild('aeg',$date);


    //andemete lisamine <kaup><kaubagrupp> alla

    $xml_maakond=$xml_temp->addChild('maakond');
    $xml_maakond->addChild('maakonnanimi',$maanimi);
    $xml_maakond->addChild('maakonnakeskus',$maakeskus);



    $xmlDoc -> loadXML($temp->asXML(),LIBXML_NOBLANKS);
    $xmlDoc ->preserveWhiteSpace=false;
    $xmlDoc->formatOutput = true;
    $xmlDoc->save('tempindex.xml');
    header("refresh: 0;");
}


?>
<!doctype html>
<html>
<link rel="stylesheet" href="css/style.css">
    <head>
        <title>XML andmed</title>
    </head>
    <body>
    <h1>XML failist andmed</h1>
    <form action="?" method="post">
        <label for="otsing">Otsing</label>
        <input type="text" name="otsing" id="aasta" placeholder="nimetus">
        <input type="submit" value="otsi">
    </form>
    <?php
    if(!empty($_POST['otsing'])){
        $result=searchByName($_POST['otsing']);
        foreach ($result as $temper){
            echo"<li>".$temper->maakond->maakonnanimi;
            echo",".$temper->maakond->makonnakeskus;
            echo",".$temper->temperatuur;
            echo",".$temper->temperatuur."</li>";
        }
    }
    ?>
    +
    <table class="table">
        <tr>
            <th>temperatuur</th>
            <th>Kuupaev</th>
            <th>maakonnanimi</th>
            <th>maakonnakeskus</th>

        </tr>
        <?php
        foreach ($temp->kaup as $temper){
            echo"<tr>";
            echo"<td>".($temper->temperatuur)."</td>";
            echo"<td>".($temper->aeg)."</td>";
            echo"<td>".($temper->maakond->maakonnanimi)."</td>";
            echo"<td>".($temper->maakond->makonnakeskus)."</td>";
            echo"</tr>";
        }
        ?>
    </table>
    <h1>Andmete lisamine xml faili sisse</h1>
    <form action="" method="post">
        <table border="1">
            <tr>
                <td><label for="tempe">temperatuur</label></td>
                <td><input type="number" id="tempe" name="tempe"</td>
            </tr>
            <tr>
                <td><label for="date">Kuupaev</label></td>
                <td><input type="date" id="date" name="date"</td>
            </tr>
            <tr>
                <td><label for="maanimi"> maakonnanimi</label></td>
                <td><input type="text" id="maanimi" name="maanimi"</td>
            </tr>
            <tr>
                <td><label for="maakeskus">maakonnakeskus</label></td>
                <td><input type="text" id="maakeskus" name="maakeskus"</td>
            </tr>
            <tr>
                <td colspan="2" align="left">
                    <input type="submit" value="lisa" name="submit">
                </td>
            </tr>
        </table>
    </form>

    <h1>RSS - Rich Summary Site/ üks XML andmevormingust</h1>
    <h2>RSS uudised londonworld.com lehelt</h2>
    <ul>
        <?php
        $feed = simplexml_load_file('https://www.londonworld.com/rss');
        $linkide_arv=3;
        $loendur=1;
        foreach ($feed->channel->item as $item){
            if($loendur<=$linkide_arv){
                echo"<li>";
                echo "<a href='$item->link' target='blank'> $item->title</a>";
                echo "</li>";
                $loendur++;
            }
        }
        ?>
    </ul>

    </body>
</html>