<?php
/**
 * Plugin Name: Notify connect par JM Créa
 * Plugin URI: https://wordpress.org/plugins/notify-connect-par-jm-crea/
 * Description: Soyez notifié à chaque connexion d'un administrateur sur votre site. Notification par mail et/ou sms pour les abonnés freemobile.
 * Version: 2.5
 * Author: JM Créa
 * Author URI: http://www.jm-crea.com/
 */
#################################### INSTALLATION DU PLUGIN

//On créé la table mysql
function creer_table_ncfbx() {
global $wpdb;
$table_acd = $wpdb->prefix . 'ncfbx';
$creer_table = "CREATE TABLE IF NOT EXISTS $table_acd (
id_ncfbx int(11) NOT NULL AUTO_INCREMENT,
actif_mail text DEFAULT NULL,
actif_fbx text DEFAULT NULL,
email text DEFAULT NULL,
fbx_identifiant text DEFAULT NULL,
fbx_mdp text DEFAULT NULL,
admin text DEFAULT NULL,
editor text DEFAULT NULL,
author text DEFAULT NULL,
contributor text DEFAULT NULL,
shop_manager text DEFAULT NULL,
execution_url text DEFAULT NULL,
UNIQUE KEY id (id_ncfbx)
);";
require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
dbDelta( $creer_table );
}

//On insere les infos dans la table
function insert_table_ncfbx() {
global $wpdb;
$table_acd = $wpdb->prefix . 'ncfbx';
$wpdb->insert($table_acd, 
array('id_ncfbx'=>'','actif_mail'=>'ON','actif_fbx'=>'ON','email'=>'' . get_option("admin_email") . '','fbx_identifiant'=>'Identifiant freemobile','fbx_mdp'=>'Mot de passe freemobile','admin'=>'ON','editor'=>'ON','author'=>'ON','contributor'=>'ON','shop_manager'=>'ON','execution_url'=>'CURL'), 
array('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s'));
require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
dbDelta( $sql );
}

register_activation_hook( __FILE__, 'creer_table_ncfbx' );
register_activation_hook( __FILE__, 'insert_table_ncfbx' );


####### ON AFFICHE LES INFOS DE LA BDD

function ncfbx() {
global $wpdb;

$table_ncfbx = $wpdb->prefix . "ncfbx";
$voir_ncfbx = $wpdb->get_row("SELECT * FROM $table_ncfbx WHERE id_ncfbx='1'");
require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
dbDelta( $sql );

echo "<h1>Notify connect par JM Créa</h1>
<h2>Soyez notifié lors d'une connexion sur votre administration Wordpress</h2>
<p>Ce plugin vous enverra une notification par mail ou par sms (pour les abonnés Freemobile) lorsqu'une connexion sur votre backoffice Wordpress sera établie.</p>
<p>PS : Merci de noter ce plugin sur <a href='https://wordpress.org/plugins/notify-connect-par-jm-crea/' target='_blank'>wordpress.org</a> si ce n'est pas déjà fait :)</p>";

if (isset($_GET['action'])&&($_GET['action'] == 'maj-ok')) {
echo '<div class="updated"><p>Notify Connect mis à jour avec succès !.</p></div>';		
}
if (isset($_GET['action'])&&($_GET['action'] == 'reinitialiser')) {
echo '<div class="updated"><p>Notify Connect réinitialisé avec succès !.</p></div>';		
}

echo '<div class="wrap">
<h2 class="nav-tab-wrapper">';

if ( (isset($_GET['tab']))&&($_GET['tab'] == 'notifications') ) {
echo '<a class="nav-tab nav-tab-active" href="' . admin_url() . 'admin.php?page=ncfbx&tab=notifications">Pramètres de notifications</a>';
}
else {
echo '<a class="nav-tab" href="' . admin_url() . 'admin.php?page=ncfbx&tab=notifications">Pramètres de notifications</a>';
}

if ( (isset($_GET['tab']))&&($_GET['tab'] == 'aide') ) {
echo '<a class="nav-tab nav-tab-active" href="' . admin_url() . 'admin.php?page=ncfbx&tab=aide">Aide Free Mobile</a>';
}
else {
echo '<a class="nav-tab" href="' . admin_url() . 'admin.php?page=ncfbx&tab=aide">Aide Free Mobile</a>';	
}

if ( (isset($_GET['tab']))&&($_GET['tab'] == 'deboeug') ) {
echo '<a class="nav-tab nav-tab-active" href="' . admin_url() . 'admin.php?page=ncfbx&tab=deboeug">Réinitialiser</a>';
}
else {
echo '<a class="nav-tab" href="' . admin_url() . 'admin.php?page=ncfbx&tab=deboeug">Réinitialiser</a>';	
}

if ( (isset($_GET['tab']))&&($_GET['tab'] == 'autres_plugins') ) {
echo '<a class="nav-tab nav-tab-active" href="' . admin_url() . 'admin.php?page=ncfbx&tab=autres_plugins">Nos autres plugins</a>';
}
else {
echo '<a class="nav-tab" href="' . admin_url() . 'admin.php?page=ncfbx&tab=autres_plugins">Nos autres plugins</a>';	
}
echo '</h2></div>';


if ( (isset($_GET['page']))&&($_GET['page'] == 'ncfbx') ) {

/* TABS NOTIFICATIONS */
if ( (isset($_GET['tab']))&&($_GET['tab'] == 'notifications') ) {
echo "
<div id='cadre_blanc'>
<form id='form1' name='form1' method='post' action=''>
<table border='0' cellspacing='8' cellpadding='0'>
<tr>
<td colspan='2'><h3>Paramètres email</h3></td>
</tr>
<tr>
<td>Etre notifié par email :</td>
<td>";
if ($voir_ncfbx->actif_mail == 'ON') {
echo "<input type='radio' name='actif_mail' id='radio' value='ON' checked='checked'> OUI  <input type='radio' name='actif_mail' id='radio2' value='OFF'>NON";
}
else {
echo "<input type='radio' name='actif_mail' id='radio' value='ON'> OUI  <input type='radio' name='actif_mail' id='radio2' value='OFF' checked='checked'>NON";	
}
echo "
</td>
</tr>
<tr>
<td>Email de notification : <code>(par défaut : ". get_option( 'admin_email' ) . ")</code></td>
<td>
<input type='text' name='email' id='email' value='". $voir_ncfbx->email . "'></td>
</tr>
<tr>
<td colspan='2'><h3>Paramètres SMS (Freemobile)</h3></td>
</tr>
<tr>
<td>Etre notifié par SMS :</td>
<td>";
if ($voir_ncfbx->actif_fbx == 'ON') {
echo "<input type='radio' name='actif_fbx' id='radio3' value='ON' checked='checked'> OUI <input type='radio' name='actif_fbx' id='radio4' value='OFF'> NON";
}
else {
echo "<input type='radio' name='actif_fbx' id='radio3' value='ON'> OUI <input type='radio' name='actif_fbx' id='radio4' value='OFF' checked='checked'> NON";
}
echo "
</td>
</tr>
<tr>
<td>Identifiant Freemobile :</td>
<td><input type='password' name='fbx_identifiant' id='fbx_identifiant' value='" . $voir_ncfbx->fbx_identifiant . "'></td>
</tr>
<tr>
<td>Votre clé d'identification au service Freemobile :</td>
<td><input type='password' name='fbx_mdp' id='fbx_mdp' value='" . $voir_ncfbx->fbx_mdp . "'></td>
</tr>
<tr>
<td>Execution du script de notification :</td>
<td>
";
if ($voir_ncfbx->execution_url == 'CURL') {
echo "<input type='radio' name='execution_url' id='radio3' value='CURL' checked='checked'> CURL <input type='radio' name='execution_url' id='radio4' value='FGC'> file_get_contents";
}
else {
echo "<input type='radio' name='execution_url' id='radio3' value='CURL'> CURL <input type='radio' name='execution_url' id='radio4' value='FGC' checked='checked'> file_get_contents ";
}
echo "
</td>
</tr>
<tr>
<td colspan='2'><h3>Paramètres de notification</h3></td>
</tr>
<tr>
<td>L'orsqu'un admin se connecte : <code>(administrator)</code></td>
<td>";
if ($voir_ncfbx->admin == 'ON') {
echo "<input type='radio' name='admin' id='radio5' value='ON' checked='checked'> OUI <input type='radio' name='admin' id='radio6' value='OFF'> NON";
}
else {
echo "<input type='radio' name='admin' id='radio5' value='ON'> OUI <input type='radio' name='admin' id='radio6' value='OFF' checked='checked'> NON";
}
echo "
</td>
</tr>
<tr>
<td>Lorsqu'un éditeur se connecte : <code>(editor)</code></td>
<td>";
if ($voir_ncfbx->editor == 'ON') {
echo "<input type='radio' name='editor' id='radio7' value='ON' checked='checked'> OUI <input type='radio' name='editor' id='radio8' value='OFF'> NON";
}
else {
echo "<input type='radio' name='editor' id='radio7' value='ON'> OUI <input type='radio' name='editor' id='radio8' value='OFF' checked='checked'> NON";	
}
echo "
</td>
</tr>
<tr>
<td>Lorsqu'un autheur se connecte : <code>(author)</code></td>
<td>";
if ($voir_ncfbx->author == 'ON') {
echo "<input type='radio' name='author' id='radio9' value='ON' checked='checked'> OUI <input type='radio' name='author' id='radio10' value='OFF'> NON";
}
else {
echo "<input type='radio' name='author' id='radio9' value='ON'> OUI <input type='radio' name='author' id='radio10' value='OFF' checked='checked'> NON";
}
echo "
</td>
</tr>
<tr>
<td>Lorsqu'un contributeur se connecte : <code>(contributor)</code></td>
<td>";
if ($voir_ncfbx->contributor == 'ON') {
echo "<input type='radio' name='contributor' id='radio13' value='ON' checked='checked'> OUI <input type='radio' name='contributor' id='radio14' value='OFF'> NON";
}
else {
echo "<input type='radio' name='contributor' id='radio13' value='ON'> OUI <input type='radio' name='contributor' id='radio14' value='OFF' checked='checked'> NON";
}
echo "
</td>
</tr>
<tr>
<td>Lorsqu'un manager Woocommerce se connecte : <code>(shop_manager)</code></td>
<td>";
if ($voir_ncfbx->shop_manager == 'ON') {
echo "<input type='radio' name='shop_manager' id='radio11' value='ON' checked='checked'> OUI <input type='radio' name='shop_manager' id='radio12' value='OFF'> NON";
}
else {
echo "<input type='radio' name='shop_manager' id='radio11' value='ON'> OUI <input type='radio' name='shop_manager' id='radio12' value='OFF' checked='checked'> NON";
}
echo "
</td>
</tr>
<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
<tr>
<td colspan='2' align='right'><input type='submit' name='maj' id='maj' value='Mettre à jour' class='button button-primary' /></td>
</tr>
</table>
</form>
</div>";
}		
}

if ( (isset($_GET['tab']))&&($_GET['tab'] == 'aide') ) {
echo "<h1>Aide au paramètrage freemobile</h1>
<div id='cadre_blanc'>
<h2>Connectez-vous sur votre espace freemobile</h2>
<p>Rendez-vous sur votres <a href='https://mobile.free.fr/moncompte/' target='_blank'>espace abonné freemobile</a> puis identifiez-vous.</p>
<h2>Activez vos notifications SMS</h2>
<p>Cliquez sur <strong><u>Mes options</u></strong> puis activez <strong><u>Notification par SMS</u></strong> et récupérez <strong><u>Votre clé d'identification au service</u></strong>
<h2>Information importante</h2>
<p>Votre identifiant freemobile ainsi que votre clé d'activation ne peuvent être cryptés dans la base de données Wordpress. L'identifiant et la clé d'activation sont affichés en brut dans la base de données de votre site Wordpress.</p>
<p>Le développeur du plugin (JM Créa) se dégage de toute responsabilité si vous utilisez ce plugin et que votre site se fait hacké.</p>
</div>";
}


if ( (isset($_GET['tab']))&&($_GET['tab'] == 'deboeug') ) {
/* TABS REINITIALISER */
echo "<h1>Réinitialiser le plugin</h1>";
echo "<div id='cadre_blanc'>";
echo "<p>En cliquant sur le boutton 'Réinitialiser le plugin', le plugin sera reinitialisé et vos paramètres seront perdus. Il faudra les renseigner de nouveau.</p>";
echo "<p><u>NOTE</u> : Il est conseillé de reinitialiser ce plugin au moins 1 fois afin de proffiter de l'option qui vous permet de choisir la méthode d'envoi des sms de notification (<code>CURL</code> ou <code>file_get_contents</code>). </p>";
echo "
<form id='form2' name='form2' method='post' action=''>
<input type='submit' name='reinitialiser' id='reinitialiser' value='Réinitialiser le plugin' class='button button-primary' />
</div>";	
}




if ( (isset($_GET['tab']))&&($_GET['tab'] == 'autres_plugins') ) {
	echo '
	<div id="listing_plugins">
	<h3>Social Share</h3>
	<img src="' . plugins_url( 'autres-plugins-jm-crea/social-share-par-jm-crea.jpg', __FILE__ ) . '" alt="Social Share par JM Créa" />
	<p>Social Share par JM Créa vous permet de partager votre contenu sur les réseaux sociaux.</p>
	<div align="center"><a href="https://fr.wordpress.org/plugins/social-share-by-jm-crea/" target="_blank"><button class="button button-primary">Télécharger</button></a></div>
	</div>
	
    <div id="listing_plugins">
	<h3>Search box Google</h3>
	<img src="' . plugins_url( 'autres-plugins-jm-crea/search-box-google-par-jm-crea.jpg', __FILE__ ) . '" alt="Search Box Google par JM Créa" />
	<p>Search Box Google permet d’intégrer le mini moteur de recherche de votre site dans les résultats Google.</p>
	<div align="center"><a href="https://fr.wordpress.org/plugins/search-box-google-par-jm-crea/" target="_blank"><button class="button button-primary">Télécharger</button></a></div>
	</div>
	
	<div id="listing_plugins">
	<h3>Notify Update</h3>
	<img src="' . plugins_url( 'autres-plugins-jm-crea/notify-update-par-jm-crea.jpg', __FILE__ ) . '" alt="Notify Update par JM Créa" />
	<p> Notify Update par JM Créa vous notifie par email et sms (pour les abonnés freemobile) lors d’une mise à jour de votre WordPress.</p>
	<div align="center"><a href="https://fr.wordpress.org/plugins/notify-update-par-jm-crea/" target="_blank"><button class="button button-primary">Télécharger</button></a></div>
	</div>
	
	
	<div id="listing_plugins">
	<h3>Notify Connect</h3>
	<img src="' . plugins_url( 'autres-plugins-jm-crea/notify-connect-par-jm-crea.jpg', __FILE__ ) . '" alt="Notify Connect par JM Créa" />
	<p>Notify connect créé par JM Créa permet d’être notifié par email et sms (pour les abonnés freemobile) lorsqu’un admin se connecte sur l\'admin.</p>
	<div align="center"><a href="https://fr.wordpress.org/plugins/notify-connect-par-jm-crea/" target="_blank"><button class="button button-primary">Télécharger</button></a></div>
	</div>
	
	
	<div id="listing_plugins">
	<h3>Simple Google Adsense</h3>
	<img src="' . plugins_url( 'autres-plugins-jm-crea/simple-google-adsense-par-jm-crea.jpg', __FILE__ ) . '" alt="Simple Google Adsense par JM Créa" />
	<p>Simple Google Adsense par JM Créa permet d’afficher vos publicités Google Adsense avec de simples shortcodes.</p>
	<div align="center"><a href="https://fr.wordpress.org/plugins/simple-google-adsense-par-jm-crea/" target="_blank"><button class="button button-primary">Télécharger</button></a></div>
	</div>
	
	<div id="listing_plugins">
	<h3>Scan Upload</h3>
	<img src="' . plugins_url( 'autres-plugins-jm-crea/scan-upload-par-jm-crea.jpg', __FILE__ ) . '" alt="Scan Upload par JM Créa" />
	<p>Scan Upload par JM Créa détecte les fichiers suspects de votre wp-upload et permet de les supprimer en 1 clic.</p>
	<div align="center"><a href="https://fr.wordpress.org/plugins/scan-upload-par-jm-crea/" target="_blank"><button class="button button-primary">Télécharger</button></a></div>
	</div>
	
	
	<div id="listing_plugins">
	<h3>Knowledge Google</h3>
	<img src="' . plugins_url( 'autres-plugins-jm-crea/knowledge-google-par-jm-crea.jpg', __FILE__ ) . '" alt="Knowledge Google par JM Créa" />
	<p>Knowledge Google par JM Créa permet d\'afficher les liens de vos réseaux sociaux directement dans les résultats Google.</p>
	<div align="center"><a href="https://wordpress.org/plugins/knowledge-google-par-jm-crea/" target="_blank"><button class="button button-primary">Télécharger</button></a></div>
	</div>';
}



//REINITIALISATION DU PLUGIN
if (isset($_POST['reinitialiser'])) {

//On supprime la table
global $wpdb;
$table_ncfbx = $wpdb->prefix . "ncfbx";
$wpdb->query($wpdb->prepare("DROP TABLE $table_ncfbx"));

//On recréé la table et on insert
creer_table_ncfbx();
insert_table_ncfbx();

echo '<script>document.location.href="admin.php?page=ncfbx&tab=deboeug&action=reinitialiser"</script>';
}

elseif (!isset($_GET['tab'])) {
echo '<script>document.location.href="admin.php?page=ncfbx&tab=notifications"</script>';
}


//MIS A JOUR DES PARAMETRES
if (isset($_POST['maj'])) {
$actif_mail = stripslashes($_POST['actif_mail']);
$actif_fbx = stripslashes($_POST['actif_fbx']);
$email = stripslashes($_POST['email']);
$fbx_identifiant = stripslashes($_POST['fbx_identifiant']);
$fbx_mdp = stripslashes($_POST['fbx_mdp']);
$admin = stripslashes($_POST['admin']);
$editor = stripslashes($_POST['editor']);
$author = stripslashes($_POST['author']);
$contributor = stripslashes($_POST['contributor']);
$shop_manager = stripslashes($_POST['shop_manager']);
$execution_url = stripslashes($_POST['execution_url']);

global $wpdb;
$table_ncfbx = $wpdb->prefix . "ncfbx";
$wpdb->query($wpdb->prepare("UPDATE $table_ncfbx SET actif_mail='$actif_mail',actif_fbx='$actif_fbx',email='$email',fbx_identifiant='$fbx_identifiant',fbx_mdp='$fbx_mdp',admin='$admin',editor='$editor',author='$author',contributor='$contributor',shop_manager='$shop_manager',execution_url='$execution_url'  WHERE id_ncfbx='1'",APP_POST_TYPE));
echo '<script>document.location.href="admin.php?page=ncfbx&tab=notifications&action=maj-ok"</script>';

}
}

//ON VERIFIE SI LA SESSION EXISTE
if(session_id() == '') { session_start(); }

function envoi_sms(){
	
//ON RECCUPERE LES INFOS DE CONNEXION	
global $display_name , $user_login, $user_email;
get_currentuserinfo();

global $wpdb;
$table_ncfbx = $wpdb->prefix . "ncfbx";
$voir_ncfbx = $wpdb->get_row("SELECT * FROM $table_ncfbx WHERE id_ncfbx='1'");

//ON ENVOI LE SMS
if ($voir_ncfbx->actif_fbx == 'ON') {
if ( (current_user_can('administrator'))&&($voir_ncfbx->admin == 'ON')&&(!isset($_SESSION['session_sms']))||(current_user_can('editor'))&&($voir_ncfbx->editor == 'ON')&&(!isset($_SESSION['session_sms']))||(current_user_can('author'))&&($voir_ncfbx->author == 'ON')&&(!isset($_SESSION['session_sms']))||(current_user_can('contributor'))&&($voir_ncfbx->contributor == 'ON')&&(!isset($_SESSION['session_sms']))||(current_user_can('shop_manager'))&&($voir_ncfbx->shop_manager == 'ON')&&(!isset($_SESSION['session_sms'])) ) {
$_SESSION['session_sms'] = 1;

$url_freemobile = "https://smsapi.free-mobile.fr/sendmsg?user=" . $voir_ncfbx->fbx_identifiant . "&pass=" . $voir_ncfbx->fbx_mdp . "&msg=Connexion depuis le site : " .   get_bloginfo("name") . " avec l'identifiant : " . $user_login . " le " . date('d/m/Y') . " à " . date('H:i') . "h avec l'adresse IP : " . $_SERVER['REMOTE_ADDR'] . "";


if ($voir_ncfbx->execution_url == 'CURL') {
//Envoi en CURL
$handle = curl_init($url_freemobile);
curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);
$response = curl_exec($handle);
$httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
curl_close($handle);

}
else {
//Envoi avec file_get_contents
file_get_contents($url_freemobile);
}

}
}

//ON ENVOI LE MAIL
if ($voir_ncfbx->actif_mail == 'ON') {
if ( (current_user_can('administrator'))&&($voir_ncfbx->admin == 'ON')&&(!isset($_SESSION['session_email']))||(current_user_can('editor'))&&($voir_ncfbx->editor == 'ON')&&(!isset($_SESSION['session_email']))||(current_user_can('author'))&&($voir_ncfbx->author == 'ON')&&(!isset($_SESSION['session_email']))||(current_user_can('contributor'))&&($voir_ncfbx->contributor == 'ON')&&(!isset($_SESSION['session_email']))||(current_user_can('shop_manager'))&&($$voir_ncfbx->shop_manager == 'ON')&&(!isset($_SESSION['session_email'])) ) {
$_SESSION['session_email'] = 1;
if ($voir_ncfbx->email == '') {
$email_envoi = 	get_option( 'admin_email' );
}
else {
$email_envoi = $voir_ncfbx->email;
}
$sujet = "Alerte " . get_bloginfo('name')  . "";
$from  = "From:$email_envoi\n";
$from .= "MIME-version: 1.0\n";
$from .= "Reply-To: $email_envoi\n";
$from .= "Return-Path: <$email_envoi>\n";
$from .= "X-Mailer: Notify Connect Wordpress\n";
$from .= "Content-type: text/html; charset= utf-8\n";
$message = "<strong><u>Connexion depuis le site</u> :</strong> " .   get_bloginfo("name") . "<br><strong><u>Identifiant utilisé</u> :</strong> " . $user_login . "<br><strong><u>Date</u> :</strong> " . date('d/m/Y') . " à " . date('H:i') . "h<br><strong><u>Adresse IP</u> :</strong> " . $_SERVER['REMOTE_ADDR'] . "";
$message .= "<p><small><u>PS</u> : Si vous trouvez ce plugin utile, merci de laisser une note sur <a href='https://wordpress.org/plugins/notify-connect-par-jm-crea/' target='_blank'>Wordpress.org :)</a></small></p>";
mail($email_envoi,$sujet,$message,$from);
}
}
}	
function menu_ncfbx_jm() {
add_menu_page('Notify connect', 'Notify connect', 'manage_options', 'ncfbx', 'ncfbx', plugins_url('notify-connect-par-jm-crea/notify.png') );
}
add_action('admin_menu', 'menu_ncfbx_jm');
add_action('admin_notices', 'envoi_sms');



add_action( 'admin_enqueue_scripts', 'style_ncfbx_jm_crea' );
//Appel du css
function style_ncfbx_jm_crea() {
wp_register_style('css_ncfbx_jm_crea', plugins_url( 'css/style.css', __FILE__ ));
wp_enqueue_style('css_ncfbx_jm_crea');	
}

function head_meta_ncfbx_jm_crea() {
echo("<meta name='Notify Connect par JM Créa' content='2.5' />\n");
}
add_action('wp_head', 'head_meta_ncfbx_jm_crea');
?>