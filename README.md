# cpmmon-libs/user

Create, manipulate & manage user by using php objects:
````php
/* Create */
$u           = new user("username");
$u->password = "hallo";
$u->email    = "hallo@web.tld";
$u->setRole("admin");

/* Find */
$u = user::find("username");
echo $u->email; //hallo@web.tld
echo $u->getRole(); //admin

/* Update */
$u = user::find("username");
$u->email = new@web.tld

/* Remove */
$u = user::find("username");
$u->remove();

/* Auth */
auth::check(); //is authenticated?
auth::login($username, $password); //log in a user
auth::logout(); //log out the current user
auth::require("role"); //check if current user has role "role"
````

Requirements:
 - php: ^5.4
 - composer
 - RedBeanPHP: ^5.0


Functions:
 - less configuration needed
 - OpenSource
 - Roles & Permissions
 - easy to use
 - semantic usage
