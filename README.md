# Elvi Project Skeleton

1. Clone this repository
2. `composer install`
3. `composer update` (This is needed for symfony/flex to restrict all symfony packages to ^5.4 to avoid conflicts; Decline any recipe suggestions during the process)
4. `bin/robo skeleton:init <git-remote-url>`
5. Review and add missing parameter values in `.env` and `.env.(elvidev|elvitest|elviapp)` files (Hint: [follow this for auth config](https://gitlab.com/elviapp/elvi-auth-bundle/-/blob/master/README.md#configuration]))
6. Have a look around with some demo data. When done, remove demo data with `bin/robo demo:remove`
7. Add relevant data to `README.md` file
8. Commit and push everything to the new repository