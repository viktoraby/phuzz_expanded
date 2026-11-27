Fuzzing 0-days in WordPress plugins
=========================

First, you can download the WordPress plugins through the WordPress Plugin API with `00-download-plugins.py`:

- Use the WordPress Plugin API to get metadata of all plugins
- Download all plugins that have between `INSTALL_MIN` and `INSTALL_MAX` active installs
- Now you have successfully downloaded the plugins

The original compressed WordPress plugin dataset used in the underlying research project is not included in this repository.

Please note that a fresh download may differ from the original research dataset in plugin versions and availability.

Then you can analyze the plugins with `01-analyze-all.sh` to extract the plugins' API endpoints and parameters, and generate `docker-compose.yml` files for each endpoint. 
You can define the number of instances in `01-analyze-plugin.py` with `N_FUZZERS=list(range(1,10+1)) #+1 to include the upper bound` and define the fuzzing timeout with `FUZZ_TIME=180`.

On our server, we first copied one of the generated configs to the parent directory and then pre-built the other containers (`docker-compose build`). 
Then, we brought up the database with `docker-compose up -d db` in the parent directory. 
Finally, we executed `./02-fuzz-all-plugins.sh` in this directory to automatically fuzz all API endpoints with PHUZZ. 

We override some of these functions to enable PHUZZ to achieve more code coverage. See `web/applications/wordpress/_overrides/`.

| Function              | Description                                                                    | Docs                                                                     |
| --------------------- | ------------------------------------------------------------------------------ | ------------------------------------------------------------------------ |
| `check_admin_referer` | Verifies that a user was referred with the correct security nonce.             | https://developer.wordpress.org/reference/functions/check_admin_referer/ |
| `check_ajax_referer`  | Verifies the Ajax request to prevent processing requests external of the blog. | https://developer.wordpress.org/reference/functions/check_ajax_referer/  |
| `current_user_can`    | Returns whether the current user has the specified capability                  | https://developer.wordpress.org/reference/functions/current_user_can/    |
| `get_current_user_id` | Gets the current user’s ID                                                     | https://developer.wordpress.org/reference/functions/get_current_user_id/ |
| `get_user_meta`       | Retrieves user meta field for a user                                           | https://developer.wordpress.org/reference/functions/get_user_meta/       |
| `is_admin`            | Determines whether the current request is for an administrative interface page | https://developer.wordpress.org/reference/functions/is_admin/            |
| `is_super_admin`      | Determines whether user is a site admin                                        | https://developer.wordpress.org/reference/functions/is_super_admin/      |
| `is_user_logged_in`   | Determines whether the current visitor is a logged-in user                     | https://developer.wordpress.org/reference/functions/is_user_logged_in/   |
| `user_can`            | Returns whether a particular user has the specified capability                 | https://developer.wordpress.org/reference/functions/user_can/            |
| `wp_get_current_user` | Retrieves the current user object                                              | https://developer.wordpress.org/reference/functions/wp_get_current_user/ |

The `vuln-analysis` folder contains the scripts to analyze the results and categorize the vulnerabilities.
