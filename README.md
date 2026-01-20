PHUZZ-EXPANDED
=====================================

PHUZZ-EXPANDED is a grey-box coverage-guided fuzzer for PHP web applications based on PHUZZ. PHUZZ was developed by Sebastian Neef, Lorenz Kleissner & Jean-Pierre Seifert and was published at AsiaCCS 2024 [0]. 

## New Features in PHUZZ-EXPANDED

- Support for five more vulnerabilities: HTTP-Header-Injection, SSRF, SSTI, NoSQL-Injection, LDAP-Injection.
- Updated black box scanners: ZAP, Wapiti.
- One more web application added for testing purposes.

## Abstract 

> This work extends the academic PHP fuzzer Phuzz to support five additional security vulnerabilities: HTTP header injection, SSRF, SSTI, NoSQL injection, and LDAP injection. The structure and functionality of \textit{Phuzz}, particularly its modular design and the function hooking approach using UOPZ, are described alongside the implementation. The evaluation ultimately shows that Phuzz reliably detects all new vulnerabilities and significantly outperforms common black-box scanners. The results thus confirm the effectiveness, extensibility, and practical relevance of Phuzz and highlight ways to identify vulnerabilities in PHP web applications more efficiently.

## Structure of this repo

This repository contains the code to run PHUZZ-EXPANDED and other blackbox fuzzers in headless Docker containers. To learn how to use PHUZZ-EXPANDED, please check the README in `./code/`.

The results of the previous experiments of Neef et al. are available in `./experiments/`.

Please also check the subfolders as many have a README with additional information.

## Quick run

```
# Clone the repository
git clone https://github.com/viktoraby/phuzz_expanded.git
cd phuzz_expanded/code/

sudo docker-compose up -d db --build --force-recreate
sleep 15s # give the DB some time to start up - might be shorter or longer depending on your hardware. When in doubt, check with docker-compose logs -f db.
sudo docker-compose up -d mongodb --build --force-recreate
sleep 15s
sudo docker-compose up -d openldap --build --force-recreate
sleep 15s
sudo docker-compose up -d web --build --force-recreate
sleep 15s

sudo docker-compose up fuzzer-dvwa-sqli-low-1 --build --force-recreate
# Let the fuzzer run for a while and terminate it with ctrl+c
# View the results with
less fuzzer/output/fuzzer-1/vulnerable-candidates.json
```

## Problems

If you encounter issues with write permissions, the `chmod 777` command may help. Apply it to all directories and subdirectories within the project folder.

## References

- [0] https://dl.acm.org/doi/10.1145/3634737.3661137
