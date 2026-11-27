#!/bin/bash
set -x

find ../configs -type f -name '*vulnerable*.json' | sort -u > vulnerable-endpoints.txt

python3 01-analyze-endpoints.py

## Get server-side debug info for SQLi and PathTraversal. There's no such info for open redirection and XSS, since these are detected on the client-side.
find ../configs -type f -name 'SQLi-*.json' > vulnerable-candidates-debuginfo-SQLi.txt
find ../configs -type f -name 'PathTraversal-*.json' > vulnerable-candidates-debuginfo-PathTraversal.txt

python3 02-analyze-vulns.py