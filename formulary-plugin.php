<?php
/**
 * Plugin Name: Formulary
 * Author: Maria Ezquerra
 * Description: Plugin zum Erhöhen eines Formulars mit einem Shortcode [formulary_plugin_form]
 */

register_activation_hook(__File__,'Formulary_Plugin_init');

function Formulary_Plugin_init()
{
    global $wpdb;
    $tabla_kunde = $wpdb->prefix . 'kunde';
    $charset_collate = $wpdb->get_charset_collate();
    $query = "CREATE TABLE IF NOT EXISTS $tabla_kunde (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        vorname varchar(50) NOT NULL,
        nachname varchar(50) NOT NULL,
        mail varchar(100) NOT NULL,
        firma varchar(100) NULL,
        website_übersichtlich smallint(4) NOT NULL,
        gewünschte_produkt smallint(4) NOT NULL,
        produkte_übersichtlich smallint(4) NOT NULL,
        preise_homepage smallint(4) NOT NULL,
        wenn_ja smallint(4) NOT NULL,
        deutlich_anbieten smallint(4) NOT NULL,
       /**   
        besonders_gefallen text,
       */
        anerkennung smallint(4) NOT NULL,
       /**
        ip varchar(300),
        */
        created_at datetime NOT NULL,
        UNIQUE (id)
        ) $charset_collate";
        include_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($query);
}

add_shortcode('formulary_plugin_form', 'Formulary_Plugin_form');


function Formulary_Plugin_form()
{

    if (!empty($_POST)
        && $_POST['vorname'] != ''
        && $_POST['nachname'] != ''
        && is_email($_POST['mail'])
        && $_POST['firma'] != ''
        && $_POST['website_übersichtlich'] != ''
        && $_POST['gewünschte_produkt'] != ''
        && $_POST['nivel_php'] != ''
        && $_POST['nivel_wp'] != ''
        && $_POST['aceptacion'] == '1'
        ) {
        $tabla_kunde = $wpdb->prefix . 'kunde';
        $vorname = sanitize_text_field($_POST['vorname']);
        $nachname = sanitize_text_field($_POST['nachname']);
        $mail = $_POST['mail'];
        $firma = sanitize_text_field($_POST['firma']);
        $website_übersichtlich = (int) $_POST['website_übersichtlich'];
        $gewünschte_produkt = (int) $_POST['gewünschte_produkt'];
        $produkte_übersichtlich = (int) $_POST['produkte_übersichtlich'];
        $preise_homepage = (int) $_POST['preise_homepage'];
        $wenn_ja = (int) $_POST['wenn_ja'];
        $deutlich_anbieten = (int) $_POST['deutlich_anbieten'];
        $besonders_gefallen = sanitize_text_field($_POST['besonders_gefallen']);
        $anerkennung = (int) $_POST['anerkennung'];
        $ip = Kfp_Obtener_IP_usuario();
        $created_at = date('Y-m-d H:i:s');

        $wpdb->insert(
            $tabla_kunde,
            array(
                'vorname' => $vorname,
                'nachname' => $nachname,
                'mail' => $mail,
                'firma' => $firma,
                'website_übersichtlich' => $website_übersichtlich,
                'gewünschte_produkt' => $gewünschte_produkt,
                'produkte_übersichtlich' => $produkte_übersichtlich,
                'preise_homepage' => $preise_homepage,
                'wenn_ja' => $wenn_ja,
                'deutlich_anbieten' => $deutlich_anbieten,
                'besonders_gefallen' => $besonders_gefallen,
                'anerkennung' => $anerkennung,
                'ip' => $ip,
                'created_at' => $created_at,
            )
        );
        echo "<p class='exito'><b>Ihre Daten wurden registriert</b>
              <b>. Vielen Dank für Ihren Beitrag.</b>
              Wir freuen uns über Ihre Meinung.<p>";
    }

 }
    ob_start();
    ?>
    <form action="<?php get_the_permalink(); ?>" method="post" 
        class= "fragebogen">
        <div class="form-input">
        <label for="vorname">Vorname</label>
        <input type="text" name="vorname" id="vorname" required>
    </div>

    <div class="form-input">
       <label for="nachname">Nachname</label>
       <input type="text" name="nachname" id="nachname" required>
    </div>

    <div class="form-input">
        <label for='mail'>EMail</label>
        <input type="email" name="mail" id="mail" required>
    </div>

    <div class="form-input">
       <label for="firma">Firma</label>
       <input type="text" name="firma" id="firma" not required>
    </div>

    <div class="form-input">
        <label for="website_übersichtlich">Ist die Website übersichtlich?</label>
        <br><input type="radio" name="website_übersichtlich" value="1" required> Ja
        <br><input type="radio" name="website_übersichtlich" value="2" required> Nein
        <br><input type="radio" name="website_übersichtlich" value="3" required> Sonstiges
    </div>

    <div class="form-input">
        <label for="gewünschte_produkt">Hast du das gewünschte Produkt gefunden?</label>
        <br><input type="radio" name="gewünschte_produkt" value="1" required> Ja
        <br><input type="radio" name="gewünschte_produkt" value="2" required> Nein
        <br><input type="radio" name="gewünschte_produkt" value="3" required> Sonstiges
    </div>

    <div class="form-input">
        <label for="produkte_übersichtlich">Sind die Produkte übersichtlich zu finden?</label>
        <br><input type="radio" name="produkte_übersichtlich" value="1" required> Ja
        <br><input type="radio" name="produkte_übersichtlich" value="2" required> Nein
    </div>

    <div class="form-input">
        <label for="preise_homepage">Ist es dir aufgefallen, dass die Preise direkt bei der Homepage angezeigt werden?</label>
        <br><input type="radio" name="preise_homepage" value="1" required> Ja
        <br><input type="radio" name="preise_homepage" value="2" required> Nein
    </div>

    <div class="form-input">
        <label for="wenn_ja">Wenn ja, hat es dir geholfen?</label>
        <br><input type="radio" name="wenn_ja" value="1" required> Ja
        <br><input type="radio" name="wenn_ja" value="2" required> Nein
    </div>

    <div class="form-input">
        <label for="deutlich_anbieten">Ist es deutlich, was wir anbieten?</label>
        <br><input type="radio" name="deutlich_anbieten" value="1" required> Ja
        <br><input type="radio" name="deutlich_anbieten" value="2" required> Nein
        <br><input type="radio" name="deutlich_anbieten" value="3" required> Sonstiges
    </div>

    <div class="form-input">
        <label for="besonders_gefallen">Was hat dir besonders gefallen an der Website?</label>
        <br><input type="text" name="besonders_gefallen" id="besonders_gefallen" required>
    </div>

    <div class="form-input">
        <label for="anerkennung">Das Unternehmen verpflichtet sich, die von ihm übermittelten Daten verantwortungsvoll zu behandeln.
         die Sie senden werden. 
         <br> Ihr einziger Zweck ist die Durchführung einer Studie über die Zufriedenheit unserer Kunden.
         <br>Sie können jederzeit den Zugang, die Berichtigung oder die Löschung Ihrer Daten auf dieser Website beantragen.</label>
         <br><input type="checkbox" id="anerkennung" name="anerkennung" value="1" required> Ich verstehe und akzeptiere die Bedingungen
    </div>

    <div class="form-input">
         <input type="submit" value="Senden">
    </div>
   
    </form>
    <?php

    return ob_get_clean();



