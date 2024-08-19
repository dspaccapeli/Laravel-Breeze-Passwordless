<h1 align="center" style="color: red;">Laravel Breeze Passwordless</h1>

This project builds upon the Blade + Alpine Starter Kit, but replaces the whole authentication workflow to be passwordless.

There is no password for the user to save or memorize, they receive a notification with their one-time password. When they click the link they get automatically logged in.

You can review the flow on your own, it adheres as close as possible to the Laravel conventions and builds around authentication related features without breaking them (i.e., remember me).

The main difference besides in the logic lies on the fact that it doesn't rely on the assumption of the Illuminate and Laravel framework that the user needs to be logged in to verify their email.

Besides that a slightly different User model and custom Notifications and Requests are added.
