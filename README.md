# Elvi Project Skeleton

1. Clone this repository
2. `composer install`
3. `composer update` (This is needed for symfony/flex to restrict all symfony packages to ^6.1 to avoid conflicts; Decline any recipe suggestions during the process)
4. `bin/robo skeleton:init <git-remote-url>`
5. Review and add missing parameter values in `.env` and `.env.(elvidev|elvitest|elviapp)` files. Hint - follow common bundles configs:
   - [auth bundle](https://github.com/vision-group-ag/elvi-auth-bundle#configuration)
   - [health check bundle](https://github.com/vision-group-ag/elvi-health-check-bundle#configuration)
6. Have a look around with some demo data. When done, remove demo data with `bin/robo demo:remove`
7. Add relevant data to `README.md` file
8. Commit and push everything to the new repository
