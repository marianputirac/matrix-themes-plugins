<div>
    <p><?php echo sprintf(__("You must be <a href=\"%s\">logged in</a> to view tickets.", STG_HELPDESK_TEXT_DOMAIN_NAME), wp_login_url()); ?></p>
    <?php if($_SERVER["HTTP_HOST"] == "demo.mycatchers.com"):?>
    <p><?php echo sprintf(__("Oops :( Looks like Cookies issue. Cookies are blocked or not supported by your browser. You must <a href=\"%s\">enable cookies</a> to use WordPress.", STG_HELPDESK_TEXT_DOMAIN_NAME), "https://codex.wordpress.org/Cookies"); ?></p>
    <?php endif;?>
</div>