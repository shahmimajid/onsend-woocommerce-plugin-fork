## Checksums

This repository publishes release artifacts via GitHub Releases.

### What gets checksummed
For each release tag (e.g. `v1.1.0`), GitHub Actions builds a WordPress-installable plugin zip:

- `onsend-vX.Y.Z.zip` (top-level folder: `onsend/`)

It then generates and uploads:

- `SHA256SUMS` (SHA-256 checksums for the release zip)

### Where to find the official checksums
The canonical checksums for each release are the `SHA256SUMS` file attached to that release on GitHub.

To verify, download both `onsend-vX.Y.Z.zip` and `SHA256SUMS` from the same GitHub Release, then run:

```bash
sha256sum -c SHA256SUMS