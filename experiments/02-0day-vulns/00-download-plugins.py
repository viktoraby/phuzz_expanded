import os
import urllib.request
from concurrent.futures import ThreadPoolExecutor

import requests

MAX_PAGES = 350
CURRENT_PAGE = 1

INSTALL_MIN = 100 * 1000
INSTALL_MAX = 200 * 1000

DOWNLOAD_WORKERS = 5

DESTDIR = "./plugins/"
DESTFILE = DESTDIR + "download.zip"

if not os.path.exists(DESTDIR):
    os.mkdir(DESTDIR)

session = requests.Session()


def download_plugin(plugin):
    try:
        print(f"[*] Starting download: {plugin['download_link']}")

        urllib.request.urlretrieve(
            plugin['download_link'],
            DESTDIR
            + str(plugin['active_installs'])
            + "-"
            + plugin['download_link'].replace(
                "https://downloads.wordpress.org/plugin/",
                ""
            )
        )

        print(f"[+] Downloaded: {plugin['name']}")

    except Exception as e:
        print(f"[-] Download failed: {plugin['name']} - {e}")


with ThreadPoolExecutor(max_workers=DOWNLOAD_WORKERS) as executor:

    while CURRENT_PAGE <= MAX_PAGES:
        try:
            print(f"[*] Sending request to API for page {CURRENT_PAGE}")

            r = session.get(
                f"https://api.wordpress.org/plugins/info/1.2/"
                f"?action=query_plugins"
                f"&request[per_page]=3000"
                f"&request[page]={CURRENT_PAGE}"
            )

            result = r.json()
            MAX_PAGES = result['info']['pages']

            print(f"[*] Obtained page {CURRENT_PAGE}")

            for plugin in result['plugins']:

                if not INSTALL_MIN <= plugin['active_installs'] <= INSTALL_MAX:
                    continue

                with open("plugin-download.log", "a") as f:
                    f.write(
                        f"{plugin['active_installs']},{plugin['name']}\n"
                    )

                print(
                    f"[+] Found plugin: "
                    f"{plugin['name']} / {plugin['active_installs']}"
                )

                executor.submit(download_plugin, plugin)

            CURRENT_PAGE += 1

        except Exception as e:
            print(e)
