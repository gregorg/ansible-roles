<?php
$CONF['configured'] = true;
//$CONF['setup_password'] = 'ac10fda531537b1601845f9cceae7e79:2e1f078bbca8e9727578885d2a7e34279c0e46b3';
$CONF['default_language'] = 'fr';
$CONF['admin_email'] = '';

// Encrypt
// In what way do you want the passwords to be crypted?
// md5crypt = internal postfix admin md5
// md5 = md5 sum of the password
// system = whatever you have set as your PHP system default
// cleartext = clear text passwords (ouch!)
// mysql_encrypt = useful for PAM integration
// authlib = support for courier-authlib style passwords
// dovecot:CRYPT-METHOD = use dovecotpw -s 'CRYPT-METHOD'. Example: dovecot:CRAM-MD5
//   (WARNING: don't use dovecot:* methods that include the username in the hash - you won't be able to login to PostfixAdmin in this case)
$CONF['encrypt'] = 'dovecot:{{ password_scheme }}';

// In what flavor should courier-authlib style passwords be encrypted?
// md5 = {md5} + base64 encoded md5 hash
// md5raw = {md5raw} + plain encoded md5 hash
// SHA = {SHA} + base64-encoded sha1 hash
// crypt = {crypt} + Standard UNIX DES-encrypted with 2-character salt
$CONF['authlib_default_flavor'] = 'SHA';

// If you use the dovecot encryption method: where is the dovecotpw binary located?
// for dovecot 1.x
// $CONF['dovecotpw'] = "/usr/sbin/dovecotpw";
// for dovecot 2.x (dovecot 2.0.0 - 2.0.7 is not supported!)
$CONF['dovecotpw'] = "/usr/bin/doveadm pw";

// Password validation
// New/changed passwords will be validated using all regular expressions in the array.
// If a password doesn't match one of the regular expressions, the corresponding
// error message from $PALANG (see languages/*) will be displayed.
// See http://de3.php.net/manual/en/reference.pcre.pattern.syntax.php for details
// about the regular expression syntax.
// If you need custom error messages, you can add them using $CONF['language_hook'].
// If a $PALANG text contains a %s, you can add its value after the $PALANG key
// (separated with a space).
$CONF['password_validation'] = array(
#    '/regular expression/' => '$PALANG key (optional: + parameter)',
    '/.{5}/'                => 'password_too_short 5',      # minimum length 5 characters
    '/([a-zA-Z].*){3}/'     => 'password_no_characters 3',  # must contain at least 3 characters
    '/([0-9].*){2}/'        => 'password_no_digits 2',      # must contain at least 2 digits
);

// Generate Password
// Generate a random password for a mailbox or admin and display it.
// If you want to automagically generate passwords set this to 'YES'.
$CONF['generate_password'] = 'NO';

// Show Password
// Always show password after adding a mailbox or admin.
// If you want to always see what password was set set this to 'YES'.
$CONF['show_password'] = 'YES';

// Page Size
// Set the number of entries that you would like to see
// in one page.
$CONF['page_size'] = '30';

// Default Aliases
// The default aliases that need to be created for all domains.
// You can specify the target address in two ways:
// a) a full mail address
// b) only a localpart ('postmaster' => 'admin') - the alias target will point to the same domain
$CONF['default_aliases'] = array (
    'abuse' => 'abuse@{{ mta_domain }}',
    'hostmaster' => 'hostmaster@{{ mta_domain }}',
    'postmaster' => 'postmaster@{{ mta_domain }}',
    'webmaster' => 'webmaster@{{ mta_domain }}'
);


// Default Domain Values
// Specify your default values below. Quota in MB.
$CONF['aliases'] = '10';
$CONF['mailboxes'] = '10';
$CONF['maxquota'] = '10';
$CONF['domain_quota_default'] = '2048';

// Quota
// When you want to enforce quota for your mailbox users set this to 'YES'.
$CONF['quota'] = 'NO';
// If you want to enforce domain-level quotas set this to 'YES'.
$CONF['domain_quota'] = 'YES';
// You can either use '1024000' or '1048576'
$CONF['quota_multiplier'] = '1024000';


// Backup
// If you don't want backup tab set this to 'NO';
$CONF['backup'] = 'NO';

$CONF['show_header_text'] = 'NO';
$CONF['header_text'] = ':: Postfix Admin ::';

// Footer
// Below information will be on all pages.
// If you don't want the footer information to appear set this to 'NO'.
$CONF['show_footer_text'] = 'YES';
$CONF['footer_text'] = 'Return to www.{{ mta_domain }}';
$CONF['footer_link'] = 'http://www.{{ mta_domain }}';


// Welcome Message
// This message is send to every newly created mailbox.
// Change the text between EOM.
$CONF['welcome_text'] = <<<EOM
Salut,

Bienvenue sur ta nouvelle adresse email !
EOM;


// Theme Config
// Specify your own logo and CSS file
$CONF['theme_logo'] = 'images/logo-default.png';
$CONF['theme_css'] = 'css/default.css';
// If you want to customize some styles without editing the $CONF['theme_css'] file,
// you can add a custom CSS file. It will be included after $CONF['theme_css'].
$CONF['theme_custom_css'] = '';

/* vim: set expandtab softtabstop=4 tabstop=4 shiftwidth=4: */
