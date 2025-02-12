# Elvi Project Skeleton

1. Clone this repository
2. `composer install`
3. `composer update` (This is needed for symfony/flex to restrict all symfony packages to the same minor version of Symfony in order to avoid conflicts; Decline any recipe suggestions during the process)
4. `composer skeleton:init` (It will ask interactively for a git repository URL)
5. Review and add missing parameter values in `.env` and `.env.(elvidev|elviapp)` files. Hint - follow common bundles configs:
   - [auth bundle](https://github.com/vision-group-ag/elvi-auth-bundle#configuration)
   - [health check bundle](https://github.com/vision-group-ag/elvi-health-check-bundle#configuration)
6. Double check that you have updated required role in `security.yaml` `access_control` section
7. Have a look around with some demo data. When done, remove demo data with `composer skeleton:demo:remove`
8. Add relevant data to `README.md` file
9. Commit and push everything to the new repository
10. Setup repository configuration for the context
    - [config repository](https://github.com/vision-group-ag/elvi-project-config/tree/master/config/repos)
    - enables automated deploying
    - configures health checks
    - maps to the status page
    - [results shown in status.elvi.io](https://status.elvi.io)
