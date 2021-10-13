<?php
/**
 * Plugin Name: Formulary 
 * Author: Maria Ezquerra
 * Description: Plugin zum Erhöhen eines Formulars mit einem Shortcode 
 * [formulary_plugin_form]
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
        nachname varchar(80) NOT NULL,
        mail varchar(100) NOT NULL,
        firma varchar(100) NOT NULL,
        website_übersichtlich smallint(3) NOT NULL,
        gewünschte_produkt smallint(3) NOT NULL,
        produkte_übersichtlich smallint(2) NOT NULL,
        preise_homepage smallint(2) NOT NULL,
        wenn_ja smallint(2) NOT NULL,
        deutlich_anbieten smallint(3) NOT NULL, 
        besonders_gefallen text,
        anerkennung smallint(1) NOT NULL,
        created_at datetime NOT NULL,
        UNIQUE (id)
        ) $charset_collate";

    include_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($query);
}

add_shortcode('formulary_plugin_form', 'Formulary_Plugin_form'); 

function Formulary_Plugin_form()
{
    global $wpdb;
    
    if (!empty($_POST)
        && $_POST['vorname'] != ''
        && $_POST['nachname'] != ''
        && is_email($_POST['mail'])
        && $_POST['firma'] != ''
        && $_POST['website_übersichtlich'] != ''
        && $_POST['gewünschte_produkt'] != ''
        && $_POST['produkte_übersichtlich'] != ''
        && $_POST['preise_homepage'] != ''
        && $_POST['wenn_ja'] != ''
        && $_POST['deutlich_anbieten'] != ''
        && $_POST['besonders_gefallen'] != ''
        && $_POST['anerkennung'] == '1'
        ){
        $tabla_kunde = $wpdb->prefix . 'kunde';
        $vorname = sanitize_text_field($_POST['vorname']);
        $nachname = sanitize_text_field($_POST['nachname']);
        $mail = sanitize_email($_POST['mail']);
        $firma = sanitize_text_field($_POST['firma']);
        $website_übersichtlich = (int) $_POST['website_übersichtlich'];
        $gewünschte_produkt = (int) $_POST['gewünschte_produkt'];
        $produkte_übersichtlich = (int) $_POST['produkte_übersichtlich'];
        $preise_homepage = (int) $_POST['preise_homepage'];
        $wenn_ja = (int) $_POST['wenn_ja'];
        $deutlich_anbieten = (int) $_POST['deutlich_anbieten'];
        $besonders_gefallen = sanitize_text_field($_POST['besonders_gefallen']);
        $anerkennung = (int) $_POST['anerkennung'];
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
                'created_at' => $created_at, 
            )
        );
        echo "<p class='Erfolg'><b>Ihre Daten wurden registriert</b>
              <b>. Vielen Dank für Ihren Beitrag</b>
              <b>. Wir freuen uns über Ihre Meinung.</b><p>";
    }

    wp_enqueue_style('css_kunde', plugins_url('style.css', __FILE__));

    ob_start();
    ?>
    <form action="<?php get_the_permalink();?>"method="post"
        class= "fragebogen">
        <?php wp_nonce_field('speichern_kunde', 'kunde_nonce');?>
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
            <input type="text" name="firma" id="firma" required>
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
}

add_action("admin_menu", "Formulary_Plugin_menu");

function Formulary_Plugin_menu()
{
    add_menu_page("Kundenfragebogen", "Kunden", "manage_options",
    "formulary_plugin_menu", "Formulary_Plugin_admin", "dashicons-feedback", 75);
}

function Formulary_Plugin_admin()
{
    global $wpdb;
    
    $tabla_kunde = $wpdb->prefix . 'kunde';
    $kunde = $wpdb->get_results("SELECT * FROM $tabla_kunde");
    echo '<div class="wrap"><h1>Kundenliste</h1>';
    echo '<table class="wp-list-table widefat fixed striped">';
    echo '<thead><tr><th width="30%">Nachname</th><th width="20%">Mail</th>';
    echo '<th>HTML</th><th>CSS</th><th>JS</th><th>PHP</th><th>WP</th><th>Total</th>';
    echo '</tr></thead>';
    echo '<tbody id="the-list">';
    foreach ($kunde as $kunde) {
        $vorname = esc_textarea($kunde->vorname);
        $nachname = esc_textarea($kunde->nachname);
        $mail = esc_textarea($kunde->mail);
        $firma = esc_textarea($kunde->firma);
        $besonders_gefallen = esc_textarea($kunde->besonders_gefallen);
        $website_übersichtlich = (int) $kunde->website_übersichtlich;
        $gewünschte_produkt = (int) $kunde->gewünschte_produkt;
        $produkte_übersichtlich = (int) $kunde->produkte_übersichtlich;
        $preise_homepage = (int) $kunde->preise_homepage;
        $wenn_ja= (int) $kunde->wenn_ja;
        $deutlich_anbieten= (int) $kunde->deutlich_anbieten;
        $total = $besonders_gefallen + $website_übersichtlich + $gewünschte_produkt + $produkte_übersichtlich + $preise_homepage + $wenn_ja + $deutlich_anbieten;
        echo "<tr><td><a href='#' title='$anerkennung'>$vorname'>$nachname</a></td>";
        echo "<td>$firma</td><td>$mail</td><td>$besonders_gefallen</td><td>$website_übersichtlich</td>";
        echo "<td>$gewünschte_produkt</td><td>$produkte_übersichtlich</td>";
        echo "<td>$preise_homepage</td><td>$wenn_ja</td><td>$deutlich_anbieten</td>";
        echo "<td>$total</td></tr>";
    }
    echo '</tbody></table></div>';

}