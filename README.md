# Elvi Project Skeleton

1. Clone this repository
2. `composer install`
3. `bin/robo skeleton:init <git-remote-url>`
4. Review and add missing parameter values in `.env` and `.env.(elvidev|elvitest|elviapp)` files. Hint - follow common bundles configs:
   - [auth bundle](https://github.com/vision-group-ag/elvi-auth-bundle#configuration)
   - [health check bundle](https://github.com/vision-group-ag/elvi-health-check-bundle#configuration)
5. Double check that you have updated required role in `security.yaml` `access_control` section
6. Have a look around with some demo data. When done, remove demo data with `bin/robo demo:remove`
7. Optionally, if you are not intending to use Robo, remove `consolidation/robo` from `composer.json`, delete `RoboFile.php` and re-run `composer update`
8. Add relevant data to `README.md` file
9. Commit and push everything to the new repository
10. Setup repository configuration for the context
    - [config repository](https://github.com/vision-group-ag/elvi-project-config/tree/master/config/repos)
    - enables automated deploying
    - configures health checks
    - maps to the status page
    - [results shown in status.elvi.io](https://status.elvi.io)