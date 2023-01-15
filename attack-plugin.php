<?php
/**
 * Plugin Name: Attack Emails
 * Description: Ein Plugin das Phishing-Emails an ausgewählte User sendet, um Statistiken zu Sicherheitslücken zu sammeln.
 * Version: 1.0.0
 * Author: Charles Bradley Logan
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; 
}

function my_email_sender_admin_menu() {
    add_menu_page( 'My Email Sender', 'My Email Sender', 'manage_options', 'my-email-sender', 'my_email_sender_admin_page', 'dashicons-email', 6 );
}
add_action( 'admin_menu', 'my_email_sender_admin_menu' );


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
            <input type="submit" name="my_email_sender_send_email" class="button button-primary" value="Send Email to Selected Users">
        </form>
    </div>
    <?php
}

function my_email_sender_send_email() {
    if ( isset( $_POST['my_email_sender_send_email'] ) && check_admin_referer( 'my_email_sender_send_email' ) ) {
        if ( isset( $_POST['my_email_sender_receive_email'] ) && is_array( $_POST['my_email_sender_receive_email'] ) ) {
            $users = get_users( array( 'include' => array_keys( $_POST['my_email_sender_receive_email'] ) ) );
            foreach ( $users as $user ) {
                $email = $user->user_email;
                $subject = 'Phishing Email Falle';
				$message = '<img src="' . plugin_dir_url( __FILE__ ) . 'email-opened.php?user_id=' . $user->ID . '" width="1" height="1" alt="" />';
				$message = 'Das ist ein Phishing Email. <a href="' . plugin_dir_url( __FILE__ ) . 'email-clicked.php?user_id=' . $user->ID . '">Weitere FALLE</a>';
                $headers = array('Content-Type: text/html; charset=UTF-8');
                
                wp_mail( $email, $subject, $message, $headers );
              
                $emails_sent = get_user_meta( $user->ID, 'my_email_sender_emails_sent', true );
if(empty($emails_sent))
{
    $emails_sent = 0;
}
update_user_meta( $user->ID, 'my_email_sender_emails_sent', intval($emails_sent) + 1 );

            }
            echo '<div class="notice notice-success is-dismissible"><p>Emails wurden an alle User gesendet.</p></div>';
        } else {
            echo '<div class="notice notice-error is-dismissible"><p>Keine User gewählt.</p></div>';
        }
    }
}


function update_email_opened_counter($user_id) {
    $email_clicked = get_user_meta($user_id, 'my_email_sender_email_opened', true);
    if (empty($email_opened)) {
        $email_opened = 0;
    }
    update_user_meta($user_id, 'my_email_sender_email_opened', intval($email_opened) + 1);
}


function update_email_clicked_counter($user_id) {
    $email_clicked = get_user_meta($user_id, 'my_email_sender_email_clicked', true);
    if (empty($email_clicked)) {
        $email_clicked = 0;
    }
    update_user_meta($user_id, 'my_email_sender_email_clicked', intval($email_clicked) + 1);
}

add_action( 'admin_init', 'my_email_sender_send_email' );
