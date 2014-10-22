<?php
    $couleurs = array();
    $couleurs['blue'] = "#0074D9";
    $couleurs['aqua'] = "#7FDBFF";
    $couleurs['green'] = "#2ECC40";
    $couleurs['lime'] = "#01FF70";
    $couleurs['yellow'] = "#FFDC00";
    $couleurs['orange'] = "#FF851B";
    $couleurs['red'] = "#FF4136";
    $couleurs['fuchsia'] = "#F012BE";
    $couleurs['gray'] = "#AAAAAA";
    $couleurs['silver'] = "#DDDDDD";


    echo '<input type="hidden" name="couleur" id="couleur" value="' . $employeCourant->getColor() . '">';

    echo '<div class="colorpicker-swatches colorpicker-border">';
    foreach ($couleurs as $couleur => $couleurHtml) {
        echo '<div class="colorpicker-swatch';

        if($employeCourant->getColor() == $couleurHtml) {
            echo " selected";
        }

        echo '" title="' . $couleur . '" style="background-color: ' . $couleurHtml . '" onclick="changeColor(this)"></div>';
    }
    echo '</div>';
?>

<script language="JavaScript">
    var hexDigits = new Array
    ("0","1","2","3","4","5","6","7","8","9","a","b","c","d","e","f");

    //Function to convert hex format to a rgb color
    function rgb2hex(rgb) {
        rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
        return "#" + hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3]);
    }

    function hex(x) {
        return isNaN(x) ? "00" : hexDigits[(x - x % 16) / 16] + hexDigits[x % 16];
    }

    console.warn(rgb2hex(document.getElementsByClassName("colorpicker-swatch").item(0).style.backgroundColor));

    function changeColor(thisDiv) {
        document.getElementById('couleur').value = rgb2hex(thisDiv.style.backgroundColor).toUpperCase();
        var elements = document.getElementsByClassName("colorpicker-swatch");

        for(var i = 0; i < elements.length ; i++) {
            elements.item(i).className = "colorpicker-swatch";
        }

        thisDiv.className += ' selected';
    }
</script>