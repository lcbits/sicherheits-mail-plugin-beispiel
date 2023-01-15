# Attack Emails WordPress Plugin
![preview](https://user-images.githubusercontent.com/98620308/212559747-19f6365d-cb5f-4ed1-b37e-3af0b46139d1.jpg)

Das Attack Emails WordPress Plugin ist ein Tool zur Sammlung von Statistiken über Sicherheitslücken in Ihrer Website. Es sendet gezielt Phishing-E-Mails an ausgewählte Benutzer und erfasst, wie viele E-Mails geöffnet und geklickt werden.

## Funktionsweise

Das Plugin fügt einen Menüpunkt "My Email Sender" im WordPress-Admin-Bereich hinzu. Der Administrator kann Benutzer auswählen, die Phishing-E-Mails erhalten sollen. Das Plugin speichert auch Metadaten wie die Anzahl der gesendeten E-Mails, geöffneten E-Mails und angeklickten E-Mails für jeden Benutzer.

## Installation

1. Lade das Plugin-Verzeichnis auf deinen lokalen Computer herunter.
2. Lade das Plugin-Verzeichnis in das Verzeichnis `/wp-content/plugins/` auf deinem WordPress-Server hoch.
3. Aktiviere das Plugin im WordPress-Admin-Bereich unter "Plugins".
4. Gehe zu "My Email Sender" im WordPress-Admin-Bereich, um Benutzer auszuwählen, die Phishing-E-Mails erhalten sollen.

## Code-Erklärung

### attack-plugin.php

Dies ist das Haupt-Skript des Plugins. Es fügt einen Menüpunkt "My Email Sender" im WordPress-Admin-Bereich hinzu und erstellt die Benutzeroberfläche, über die der Administrator Benutzer auswählen kann, die Phishing-E-Mails erhalten sollen.

```
function my_email_sender_admin_menu() {
add_menu_page( 'My Email Sender', 'My Email Sender', 'manage_options', 'my-email-sender', 'my_email_sender_admin_page', 'dashicons-email', 6 );
}
add_action( 'admin_menu', 'my_email_sender_admin_menu' );
```
Dieser Code erstellt einen Menüpunkt "My Email Sender" im WordPress-Admin-Bereich und verknüpft ihn mit der Funktion "my_email_sender_admin_page" die das Benutzerinterface für die Auswahl der Benutzer die Phishing-E-Mails erhalten sollen erstellt.

```
function my_email_sender_admin_page() {
    ?>
    <div class="wrap">
        <h1>My Email Sender</h1>
        <form method="post" action="">
            <?php wp_nonce_field( 'my_email_sender_send_email' ); ?>
            <table class="wp-list-table widefat fixed striped users">
                <thead>
                    <tr>
                        <th scope="col" id="email" class="manage-column column-email">Email</th>
                        <th scope="col" id="emails-sent" class="manage-column column-emails-sent">Emails Sent</th>
                        <th scope="col" id="email-opened" class="manage-column column-email-opened">Emails Opened</th>
						<th scope="col" id="email-clicked" class="manage-column column-email-clicked">Emails Clicked</th>
                        <th scope="col" id="receive-email" class="manage-column column-receive-email">Receive Email</th>
                    </tr>
                </thead>
                <tbody id="the-list">
                    <?php
                    $users = get_users();
                    foreach ( $users as $user ) {
                        $email = $user->user_email;
                        $emails_sent = get_user_meta( $user->ID, 'my_email_sender_emails_sent', true );
						$email_opened = get_user_meta( $user->ID, 'my_email_sender_email_opened', true );
                        $email_clicked = get_user_meta( $user->ID, 'my_email_sender_email_clicked', true );
                        $receive_email = get_user_meta( $user->ID, 'my_email_sender_receive_email', true );
                        ?>
                        <tr>
                            <td><?php echo $email; ?></td>
                            <td><?php echo $emails_sent ? $emails_sent : 0; ?></td>
							<td><?php echo $email_opened ? $email_opened : 0; ?></td>
                            <td><?php echo $email_clicked ? $email_clicked : 0; ?></td>
                            <td>
                                <input type="checkbox" name="my_email_sender_receive_email[<?php echo $user->ID; ?>]" value="1" <?php checked( $receive_email, 1 ); ?>>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
            <input type="submit" name="my_email_sender_send_email" class="button button-primary" value="Send Emails">
        </form>
    </div>
    <?php
}
```
Dieser Code erstellt die Tabelle in der Benutzer ausgewählt werden können. Es zeigt die E-Mail-Adresse des Benutzers, die Anzahl der gesendeten E-Mails, die Anzahl der geöffneten E-Mails und die Anzahl der geklickten E-Mails. Es gibt auch eine Checkbox, die es dem Administrator ermöglicht, auszuwählen, ob der Benutzer Phishing-E-Mails erhalten soll. Wenn der Administrator auf "Send Emails" klickt, werden die ausgewählten E-Mails an die Benutzer gesendet.

### email-opened.php

Dieses Skript wird aufgerufen, wenn ein Benutzer eine Phishing-E-Mail öffnet. Es erhöht die Anzahl der geöffneten E-Mails für diesen Benutzer um 1.
```
<?php

require_once( '../../../wp-load.php' );

$user_id = $_GET['user_id'];

$email_opened = get_user_meta( $user_id, 'my_email_sender_email_opened', true );
$email_opened = $email_opened ? $email_opened + 1 : 1;
update_user_meta( $user_id, 'my_email_sender_email_opened', $email_opened );

?>
```

### email-clicked.php

Dieses Skript wird aufgerufen, wenn ein Benutzer einen Link in einer Phishing-E-Mail anklickt. Es erhöht die Anzahl der geklickten E-Mails für diesen Benutzer um 1.
```
<?php

require_once( '../../../wp-load.php' );

$user_id = $_GET['user_id'];

$email_clicked = get_user_meta( $user_id, 'my_email_sender_email_clicked', true );
$email_clicked = $email_clicked ? $email_clicked + 1 : 1;
update_user_meta( $user_id, 'my_email_sender_email_clicked', $email_clicked );

?>

## Warnung

Ich weise darauf hin, dass dieses Plugin nicht auf einer produktiven Website verwendet werden sollte, da es illegal und unsicher sein kann. 
Stellen Sie sicher, dass Sie die Gesetze und Best Practices in Bezug auf Phishing und Datenschutz einhalten, bevor Sie dieses Plugin verwenden.
Bitte beachten Sie auch, dass dies nur ein Beispiel-Plugin ist, dass ich für die Bewerbung geschrieben habe und nicht für eine Produktionsumgebung geeignet ist!!!
```
